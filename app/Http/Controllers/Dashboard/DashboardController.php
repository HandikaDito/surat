<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Disposition;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ================= DISPOSISI =================
        if ($user->role_level == 0) {

            $unread = Disposition::count();
            $aktif  = Disposition::count();

        } else {

            $unread = Disposition::whereHas('targets', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->where('status', 'unread');
            })->count();

            $aktif = Disposition::whereHas('targets', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->where('status', 'on_progress');
            })->count();
        }

        // ================= STATUS SURAT =================
        $suratCollection = SuratMasuk::with('dispositions')->get();

        $suratMasuk = $suratCollection->count();

        $suratSelesai = $suratCollection
            ->filter(fn($s) => $s->status === 'selesai')
            ->count();

        $suratProses = $suratCollection
            ->filter(fn($s) => $s->status === 'diproses')
            ->count();

        // ================= CHART (FIXED 🔥) =================

        // label bulan Indonesia
        $months = collect(range(1, 12))->map(fn($m) =>
            \Carbon\Carbon::create()->month($m)->translatedFormat('F')
        );

        // 🔥 pakai field yang benar
        $chartMasuk = SuratMasuk::selectRaw('MONTH(tanggal_masuk) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $chartKeluar = SuratKeluar::selectRaw('MONTH(tanggal_surat) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $dataMasuk = [];
        $dataKeluar = [];

        for ($i = 1; $i <= 12; $i++) {
            $dataMasuk[]  = $chartMasuk[$i] ?? 0;
            $dataKeluar[] = $chartKeluar[$i] ?? 0;
        }

        return view('dashboard.index', [
            'suratMasuk'   => $suratMasuk,
            'suratSelesai' => $suratSelesai,
            'suratProses'  => $suratProses,

            'aktif'        => $aktif,
            'unread'       => $unread,

            // 🔥 chart
            'months'       => $months,
            'chartMasuk'   => $dataMasuk,
            'chartKeluar'  => $dataKeluar,
        ]);
    }
}