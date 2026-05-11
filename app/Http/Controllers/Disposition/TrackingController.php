<?php

namespace App\Http\Controllers\Disposition;

use App\Http\Controllers\Controller;
use App\Models\Disposition;
use App\Models\SuratMasuk;

class TrackingController extends Controller
{
    public function show(SuratMasuk $surat)
    {
        $user = auth()->user();

        $query = Disposition::with([
            'surat',
            'fromUser',
            'targets.user',
            'reports'
        ])
        ->where('surat_id', $surat->id)
        ->orderBy('created_at');

        // staff hanya lihat alur yang relevan
        if ($user->isStaff()) {
            $query->whereHas('fromUser', function ($q) {
                $q->where('role_level', '>=', 3);
            });
        }

        $histories = $query->get();

        return view('disposisi.tracking', [
            'surat' => $surat,
            'histories' => $histories
        ]);
    }
}