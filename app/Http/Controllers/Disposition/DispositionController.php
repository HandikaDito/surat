<?php

namespace App\Http\Controllers\Disposition;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Disposition;
use App\Models\DispositionTarget;
use App\Models\DispositionReport;
use App\Models\User;
use App\Models\SuratMasuk;

class DispositionController extends Controller
{
    // ================= INDEX =================
    public function index()
    {
        $user = auth()->user();

        $query = Disposition::with([
            'surat',
            'fromUser',
            'targets.user'
        ]);

        // 🔒 staff hanya lihat dari Kabag ke atas
        if ($user->isStaff()) {
            $query->whereHas('fromUser', function ($q) {
                $q->where('role_level', '>=', 3);
            });
        }

        // 🔍 filter bulan
        if (request('bulan')) {
            $query->whereMonth('created_at', request('bulan'));
        }

        // 🔍 filter tahun
        if (request('tahun')) {
            $query->whereYear('created_at', request('tahun'));
        }

        // 🔥 default bulan sekarang
        if (!request('bulan') && !request('tahun')) {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        }

        $dispositions = $query->latest()->paginate(10)->withQueryString();

        // ================= FIX PENTING =================

        // 🔥 dropdown surat
        $surat = SuratMasuk::latest()->get();

        // 🔥 dropdown user (exclude diri sendiri & hanya aktif)
        $users = User::where('id', '!=', $user->id)
            ->where('is_active', true)
            ->get()
            ->filter(fn($u) => $user->canSendTo($u)); // 🔒 sesuai rule role

        return view('disposisi.index', compact(
            'dispositions',
            'surat',
            'users'
        ));
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            abort(403, 'Admin tidak boleh membuat disposisi');
        }

        $request->validate([
            'surat_id' => 'required|exists:surat_masuk,id',
            'target_users' => 'required|array|min:1',
            'target_users.*' => 'exists:users,id',
            'catatan' => 'nullable|string',
            'deadline' => 'nullable|date'
        ]);

        DB::transaction(function () use ($request, $user) {

            $surat = SuratMasuk::lockForUpdate()->findOrFail($request->surat_id);

            $targets = User::whereIn('id', $request->target_users)->get();

            foreach ($targets as $target) {
                if (!$user->canSendTo($target)) {
                    abort(403, 'Tidak boleh kirim ke user tersebut');
                }
            }

            $disposition = Disposition::create([
                'surat_id' => $surat->id,
                'from_user_id' => $user->id,
                'catatan' => $request->catatan,
                'deadline' => $request->deadline,
            ]);

            $rows = [];
            $now = now();

            foreach ($targets as $target) {
                $rows[] = [
                    'disposition_id' => $disposition->id,
                    'user_id' => $target->id,
                    'status' => 'unread',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            DispositionTarget::insert($rows);

            $surat->update([
                'status' => 'diproses',
                'is_disposisi' => true
            ]);
        });

        return back()->with('success', 'Disposisi berhasil dibuat');
    }

    // ================= FORWARD =================
    public function forward(Request $request, Disposition $disposition)
    {
        $user = auth()->user();

        $request->validate([
            'target_users' => 'required|array|min:1',
            'target_users.*' => 'exists:users,id',
            'catatan' => 'nullable|string',
            'deadline' => 'nullable|date'
        ]);

        DB::transaction(function () use ($request, $user, $disposition) {

            $currentTarget = DispositionTarget::where('disposition_id', $disposition->id)
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            if (!$currentTarget) {
                abort(403);
            }

            $targets = User::whereIn('id', $request->target_users)->get();

            foreach ($targets as $target) {
                if (!$user->canSendTo($target)) {
                    abort(403);
                }
            }

            $new = Disposition::create([
                'surat_id' => $disposition->surat_id,
                'from_user_id' => $user->id,
                'catatan' => $request->catatan,
                'deadline' => $request->deadline,
            ]);

            $rows = [];
            $now = now();

            foreach ($targets as $target) {
                $rows[] = [
                    'disposition_id' => $new->id,
                    'user_id' => $target->id,
                    'status' => 'unread',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            DispositionTarget::insert($rows);

            $currentTarget->update([
                'status' => 'done'
            ]);
        });

        return back()->with('success', 'Disposisi diteruskan');
    }

    // ================= DONE =================
    public function done(Request $request, Disposition $disposition)
    {
        $user = auth()->user();

        $request->validate([
            'keterangan' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,mp4,mov|max:10240'
        ]);

        DB::transaction(function () use ($request, $user, $disposition) {

            $target = DispositionTarget::where('disposition_id', $disposition->id)
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            if (!$target) {
                abort(403);
            }

            if ($target->status === 'done') {
                abort(400, 'Sudah diselesaikan');
            }

            $file = $request->file('file');
            $path = $file->store('reports', 'public');

            $mime = $file->getMimeType();

            $type = match (true) {
                str_contains($mime, 'image') => 'image',
                str_contains($mime, 'video') => 'video',
                default => 'pdf',
            };

            DispositionReport::create([
                'disposition_id' => $disposition->id,
                'user_id' => $user->id,
                'keterangan' => $request->keterangan,
                'file_path' => $path,
                'file_type' => $type,
            ]);

            $target->update(['status' => 'done']);

            if (!$disposition->targets()->where('status', '!=', 'done')->exists()) {
                $disposition->surat->update([
                    'status' => 'selesai'
                ]);
            }
        });

        return back()->with('success', 'Laporan berhasil dikirim');
    }

    // ================= DESTROY =================
    public function destroy(Disposition $disposition)
    {
        $user = auth()->user();

        if (
            $disposition->from_user_id !== $user->id &&
            !$user->isAdmin()
        ) {
            abort(403);
        }

        $disposition->delete();

        return back()->with('success', 'Disposisi dihapus');
    }
}