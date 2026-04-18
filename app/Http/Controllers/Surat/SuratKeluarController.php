<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;

class SuratKeluarController extends Controller
{
    // ================= INDEX =================
    public function index()
    {
        $query = SuratKeluar::with('creator');

        // 🔍 FILTER BULAN (tanggal_surat)
        if (request('bulan')) {
            $query->whereMonth('tanggal_surat', request('bulan'));
        }

        // 🔍 FILTER TAHUN
        if (request('tahun')) {
            $query->whereYear('tanggal_surat', request('tahun'));
        }

        // 🔥 DEFAULT: bulan sekarang
        if (!request('bulan') && !request('tahun')) {
            $query->whereMonth('tanggal_surat', now()->month)
                  ->whereYear('tanggal_surat', now()->year);
        }

        $data = $query->latest()->paginate(10);

        // 🔥 biar filter tidak hilang saat pagination
        $data->appends(request()->query());

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
            'nomor_surat'   => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tujuan'        => 'required|string|max:255',
            'perihal'       => 'required|string|max:255',
            'isi_surat'     => 'required|string',
        ]);

        SuratKeluar::create([
            'nomor_surat'   => $request->nomor_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'tujuan'        => $request->tujuan,
            'perihal'       => $request->perihal,
            'isi_surat'     => $request->isi_surat,
            'created_by'    => auth()->id(),
            'status'        => 'draft',
        ]);

        return redirect()
            ->route('surat-keluar.index')
            ->with('success', 'Surat keluar berhasil dibuat');
    }

    // ================= SHOW =================
    public function show(SuratKeluar $suratKeluar)
    {
        return view('surat.keluar.show', compact('suratKeluar'));
    }

    // ================= WORKFLOW =================

    // 🔄 SUBMIT KE REVIEW
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

    // ✅ APPROVE
    public function approve(SuratKeluar $suratKeluar)
    {
        if ($suratKeluar->status !== 'review') {
            return back()->with('error', 'Status tidak valid');
        }

        $suratKeluar->update([
            'status' => 'approved'
        ]);

        return back()->with('success', 'Surat disetujui');
    }

    // ❌ REJECT
    public function reject(SuratKeluar $suratKeluar)
    {
        if (!in_array(auth()->user()->role_level, [1, 2])) {
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
            abort(403, 'Tidak punya akses');
        }

        $suratKeluar->delete();

        return redirect()
            ->route('surat-keluar.index')
            ->with('success', 'Surat keluar berhasil dihapus');
    }
}