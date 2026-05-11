<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DispositionTarget;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | DISPOSISI
        |--------------------------------------------------------------------------
        */

        if ($user->role_level == 0) {

            // admin lihat semua
            $unread = DispositionTarget::where('status', 'unread')->count();

            $aktif = DispositionTarget::whereIn('status', [
                'unread',
                'on_progress'
            ])->count();

        } else {

            // user biasa hanya miliknya
            $unread = DispositionTarget::where('user_id', $user->id)
                ->where('status', 'unread')
                ->count();

            $aktif = DispositionTarget::where('user_id', $user->id)
                ->whereIn('status', [
                    'unread',
                    'on_progress'
                ])
                ->count();
        }

        /*
        |--------------------------------------------------------------------------
        | STATISTIK SURAT
        |--------------------------------------------------------------------------
        */

        // total semua surat masuk
        $suratMasuk = SuratMasuk::count();

        // sedang diproses
        $suratProses = SuratMasuk::where('status', 'diproses')->count();

        // selesai
        $suratSelesai = SuratMasuk::where('status', 'selesai')->count();

        /*
        |--------------------------------------------------------------------------
        | CHART
        |--------------------------------------------------------------------------
        */

        $year = now()->year;

        $months = collect(range(1, 12))->map(
            fn ($m) =>
            Carbon::create()->month($m)->translatedFormat('F')
        );

        // chart surat masuk
        $chartMasuk = SuratMasuk::selectRaw('MONTH(tanggal_masuk) as bulan, COUNT(*) as total')
            ->whereYear('tanggal_masuk', $year)
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        // chart surat keluar
        $chartKeluar = SuratKeluar::selectRaw('MONTH(tanggal_surat) as bulan, COUNT(*) as total')
            ->whereYear('tanggal_surat', $year)
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $dataMasuk = [];
        $dataKeluar = [];

        for ($i = 1; $i <= 12; $i++) {
            $dataMasuk[] = $chartMasuk[$i] ?? 0;
            $dataKeluar[] = $chartKeluar[$i] ?? 0;
        }

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view('dashboard.index', [

            // statistik surat
            'suratMasuk'   => $suratMasuk,
            'suratProses'  => $suratProses,
            'suratSelesai' => $suratSelesai,

            // disposisi
            'aktif'        => $aktif,
            'unread'       => $unread,

            // chart
            'months'       => $months,
            'chartMasuk'   => $dataMasuk,
            'chartKeluar'  => $dataKeluar,
        ]);
    }
}