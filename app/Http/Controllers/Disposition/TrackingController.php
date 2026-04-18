<?php

namespace App\Http\Controllers\Disposition;

use App\Http\Controllers\Controller;
use App\Models\Disposition;
use App\Models\User;

class TrackingController extends Controller
{
    public function show(Disposition $disposition)
    {
        $user = auth()->user();

        $query = Disposition::with([
            'fromUser',
            'targets.user',
            'reports'
        ])
        ->where('surat_id', $disposition->surat_id)
        ->orderBy('created_at');

        // 🔒 staff hanya lihat sampai Kabag
        if ($user->isStaff()) {
            $query->whereHas('fromUser', function ($q) {
                $q->where('role_level', '>=', 3);
            });
        }

        $histories = $query->get();
        $last = $histories->last();

        return view('disposisi.tracking', compact('histories', 'last'));
    }
}