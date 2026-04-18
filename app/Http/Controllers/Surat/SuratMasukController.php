<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Surat\StoreSuratMasukRequest;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    // ================= INDEX =================
    public function index()
    {
        $query = SuratMasuk::with('creator');

        // 🔍 FILTER BULAN
        if (request('bulan')) {
            $query->whereMonth('tanggal_masuk', request('bulan'));
        }

        // 🔍 FILTER TAHUN
        if (request('tahun')) {
            $query->whereYear('tanggal_masuk', request('tahun'));
        }

        // 🔥 DEFAULT: bulan sekarang
        if (!request('bulan') && !request('tahun')) {
            $query->whereMonth('tanggal_masuk', now()->month)
                  ->whereYear('tanggal_masuk', now()->year);
        }

        $suratMasuk = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('surat.masuk.index', compact('suratMasuk'));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('surat.masuk.create');
    }

    // ================= STORE =================
    public function store(StoreSuratMasukRequest $request)
    {
        $data = $request->validated();

        $data['created_by'] = Auth::id();

        if ($request->hasFile('file_pdf')) {
            $data['file_pdf'] = $request->file('file_pdf')->store('surat', 'public');
        }

        SuratMasuk::create($data);

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', 'Surat berhasil ditambahkan');
    }

    // ================= SHOW =================
    public function show(SuratMasuk $suratMasuk)
    {
        $suratMasuk->load([
            'creator',
            'dispositions.fromUser',
            'dispositions.targets.user' // 🔥 FIX
        ]);

        return view('surat.masuk.show', [
            'surat' => $suratMasuk
        ]);
    }

    // ================= DESTROY =================
    public function destroy(SuratMasuk $suratMasuk)
    {
        if ($suratMasuk->file_pdf && Storage::disk('public')->exists($suratMasuk->file_pdf)) {
            Storage::disk('public')->delete($suratMasuk->file_pdf);
        }

        $suratMasuk->delete();

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', 'Surat berhasil dihapus');
    }
}