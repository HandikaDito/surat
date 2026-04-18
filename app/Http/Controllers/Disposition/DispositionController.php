<?php

namespace App\Http\Controllers\Disposition;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $query = Disposition::with([
            'surat',
            'fromUser',
            'targets.user'
        ]);

        // 🔒 staff hanya lihat sampai Kabag (level >= 3)
        if ($user->isStaff()) {
            $query->whereHas('fromUser', function ($q) {
                $q->where('role_level', '>=', 3);
            });
        }

        // 🔍 FILTER BULAN (pakai created_at)
        if (request('bulan')) {
            $query->whereMonth('created_at', request('bulan'));
        }

        // 🔍 FILTER TAHUN
        if (request('tahun')) {
            $query->whereYear('created_at', request('tahun'));
        }

        // 🔥 DEFAULT: bulan sekarang
        if (!request('bulan') && !request('tahun')) {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        }

        $dispositions = $query->latest()->paginate(10);

        // 🔥 biar filter tidak hilang saat pagination
        $dispositions->appends(request()->query());

        return view('disposisi.index', compact('dispositions'));
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // 🚫 admin tidak boleh disposisi
        if ($user->isAdmin()) {
            abort(403, 'Admin tidak boleh membuat disposisi');
        }

        $request->validate([
            'surat_id' => 'required|exists:surat_masuk,id',
            'target_users' => 'required|array',
            'target_users.*' => 'exists:users,id',
            'catatan' => 'nullable|string',
            'deadline' => 'nullable|date'
        ]);

        $surat = SuratMasuk::findOrFail($request->surat_id);

        // buat disposisi
        $disposition = Disposition::create([
            'surat_id' => $surat->id,
            'from_user_id' => $user->id,
            'catatan' => $request->catatan,
            'deadline' => $request->deadline,
        ]);

        // 🔥 multi target
        foreach ($request->target_users as $targetId) {

            $target = User::findOrFail($targetId);

            if (!$user->canSendTo($target)) {
                return back()->with('error', 'Tidak boleh kirim ke user tersebut');
            }

            DispositionTarget::create([
                'disposition_id' => $disposition->id,
                'user_id' => $target->id,
                'status' => 'unread'
            ]);
        }

        // update status surat
        $surat->update([
            'status' => 'diproses',
            'is_disposisi' => true
        ]);

        return back()->with('success', 'Disposisi berhasil dibuat');
    }

    // ================= FORWARD =================
    public function forward(Request $request, Disposition $disposition)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $request->validate([
            'target_users' => 'required|array',
            'target_users.*' => 'exists:users,id',
            'catatan' => 'nullable|string',
            'deadline' => 'nullable|date'
        ]);

        // 🔒 pastikan user adalah target
        $target = DispositionTarget::where('disposition_id', $disposition->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$target) {
            abort(403);
        }

        // buat disposisi baru
        $new = Disposition::create([
            'surat_id' => $disposition->surat_id,
            'from_user_id' => $user->id,
            'catatan' => $request->catatan,
            'deadline' => $request->deadline,
        ]);

        foreach ($request->target_users as $targetId) {

            $targetUser = User::findOrFail($targetId);

            if (!$user->canSendTo($targetUser)) {
                return back()->with('error', 'Tidak boleh kirim ke user tersebut');
            }

            DispositionTarget::create([
                'disposition_id' => $new->id,
                'user_id' => $targetUser->id,
                'status' => 'unread'
            ]);
        }

        return back()->with('success', 'Disposisi diteruskan');
    }

    // ================= DONE =================
    public function done(Request $request, Disposition $disposition)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $request->validate([
            'keterangan' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,mp4,mov|max:10240'
        ]);

        // 🔒 pastikan user adalah target
        $target = DispositionTarget::where('disposition_id', $disposition->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$target) {
            abort(403);
        }

        // update status target
        $target->update(['status' => 'done']);

        // upload file (opsional)
        if ($request->file('file')) {

            $file = $request->file('file');
            $path = $file->store('reports', 'public');

            $type = str_contains($file->getMimeType(), 'image')
                ? 'image'
                : (str_contains($file->getMimeType(), 'video') ? 'video' : 'pdf');

            DispositionReport::create([
                'disposition_id' => $disposition->id,
                'user_id' => $user->id,
                'keterangan' => $request->keterangan,
                'file_path' => $path,
                'file_type' => $type,
            ]);
        }

        // 🔥 cek semua target selesai
        if ($disposition->targets()->where('status', '!=', 'done')->count() === 0) {
            $disposition->surat->update([
                'status' => 'selesai'
            ]);
        }

        return back()->with('success', 'Tugas selesai');
    }

    // ================= DESTROY =================
    public function destroy(Disposition $disposition)
    {
        /** @var \App\Models\User $user */
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