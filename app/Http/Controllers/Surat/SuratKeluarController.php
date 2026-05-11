<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    // ================= INDEX =================
    public function index()
    {
        $query = SuratKeluar::with(['creator', 'approver']);

        if (request('bulan')) {
            $query->whereMonth('tanggal_surat', request('bulan'));
        }

        if (request('tahun')) {
            $query->whereYear('tanggal_surat', request('tahun'));
        }

        if (!request('bulan') && !request('tahun')) {
            $query->whereMonth('tanggal_surat', now()->month)
                  ->whereYear('tanggal_surat', now()->year);
        }

        $data = $query->latest()->paginate(10)->withQueryString();

        return view('surat.keluar.index', compact('data'));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('surat.keluar.create');
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat'   => 'required|string|max:100|unique:surat_keluar,nomor_surat',
            'tanggal_surat' => 'required|date',
            'tujuan'        => 'required|string|max:255',
            'perihal'       => 'required|string|max:255',
            'isi_surat'     => 'required|string',
            'file'          => 'nullable|mimes:pdf|max:2048',
        ]);

        $data = $request->only([
            'nomor_surat',
            'tanggal_surat',
            'tujuan',
            'perihal',
            'isi_surat',
        ]);

        $data['created_by'] = auth()->id();

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('surat_keluar', 'public');
        }

        SuratKeluar::create($data);

        return redirect()
            ->route('surat-keluar.index')
            ->with('success', 'Surat keluar berhasil dibuat');
    }

    // ================= SHOW =================
    public function show(SuratKeluar $suratKeluar)
    {
        $suratKeluar->load(['creator', 'approver']);

        return view('surat.keluar.show', compact('suratKeluar'));
    }

    // ================= EDIT =================
    public function edit(SuratKeluar $suratKeluar)
    {
        if ($suratKeluar->created_by !== auth()->id()) {
            abort(403, 'Tidak punya akses');
        }

        if ($suratKeluar->status !== 'draft') {
            return redirect()
                ->route('surat-keluar.index')
                ->with('error', 'Hanya draft yang bisa diedit');
        }

        return view('surat.keluar.edit', compact('suratKeluar'));
    }

    // ================= UPDATE =================
    public function update(Request $request, SuratKeluar $suratKeluar)
    {
        if ($suratKeluar->created_by !== auth()->id()) {
            abort(403);
        }

        if ($suratKeluar->status !== 'draft') {
            return back()->with('error', 'Tidak bisa update selain draft');
        }

        $request->validate([
            'nomor_surat'   => 'required|string|max:100|unique:surat_keluar,nomor_surat,' . $suratKeluar->id,
            'tanggal_surat' => 'required|date',
            'tujuan'        => 'required|string|max:255',
            'perihal'       => 'required|string|max:255',
            'isi_surat'     => 'required|string',
            'file'          => 'nullable|mimes:pdf|max:2048',
        ]);

        $data = $request->only([
            'nomor_surat',
            'tanggal_surat',
            'tujuan',
            'perihal',
            'isi_surat',
        ]);

        // 🔥 update file
        if ($request->hasFile('file')) {

            if ($suratKeluar->file_path && Storage::disk('public')->exists($suratKeluar->file_path)) {
                Storage::disk('public')->delete($suratKeluar->file_path);
            }

            $data['file_path'] = $request->file('file')->store('surat_keluar', 'public');
        }

        $suratKeluar->update($data);

        return redirect()
            ->route('surat-keluar.index')
            ->with('success', 'Surat berhasil diupdate');
    }

    // ================= WORKFLOW =================

    public function submit(SuratKeluar $suratKeluar)
    {
        if ($suratKeluar->created_by !== auth()->id()) {
            abort(403);
        }

        if ($suratKeluar->status !== 'draft') {
            return back()->with('error', 'Status tidak valid');
        }

        $suratKeluar->update([
            'status' => 'review'
        ]);

        return back()->with('success', 'Surat dikirim ke review');
    }

    public function approve(SuratKeluar $suratKeluar)
    {
        $user = auth()->user();

        if (!in_array($user->role_level, [1, 2])) {
            abort(403, 'Tidak punya akses approve');
        }

        if ($suratKeluar->status !== 'review') {
            return back()->with('error', 'Status tidak valid');
        }

        DB::transaction(function () use ($suratKeluar, $user) {
            $suratKeluar->update([
                'status'      => 'approved',
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);
        });

        return back()->with('success', 'Surat disetujui');
    }

    public function reject(SuratKeluar $suratKeluar)
    {
        $user = auth()->user();

        if (!in_array($user->role_level, [1, 2])) {
            abort(403);
        }

        if ($suratKeluar->status !== 'review') {
            return back()->with('error', 'Status tidak valid');
        }

        $suratKeluar->update([
            'status' => 'rejected'
        ]);

        return back()->with('success', 'Surat ditolak');
    }

    // ================= DESTROY =================
    public function destroy(SuratKeluar $suratKeluar)
    {
        if ($suratKeluar->created_by !== auth()->id()) {
            abort(403);
        }

        if ($suratKeluar->file_path && Storage::disk('public')->exists($suratKeluar->file_path)) {
            Storage::disk('public')->delete($suratKeluar->file_path);
        }

        $suratKeluar->delete();

        return redirect()
            ->route('surat-keluar.index')
            ->with('success', 'Surat keluar berhasil dihapus');
    }
}