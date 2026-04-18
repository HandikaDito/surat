<?php

namespace App\Services;

use App\Models\Disposition;

class DispositionService
{
    // ================= STORE =================
    public function store(array $data)
    {
        return Disposition::create([
            'surat_id'     => $data['surat_id'],
            'from_user_id' => auth()->id(),
            'to_user_id'   => $data['to_user_id'],
            'status'       => 'unread',
            'catatan'      => $data['catatan'] ?? null,
            'deadline'     => $data['deadline'] ?? null
        ]);
    }

    // ================= FORWARD =================
    public function forward(Disposition $disposition, array $data)
    {
        // 🔒 hanya penerima yang boleh forward
        if ($disposition->to_user_id !== auth()->id()) {
            throw new \Exception('Tidak punya akses');
        }

        // 🔥 ubah status lama (kalau belum selesai)
        if ($disposition->status !== 'done') {
            $disposition->update([
                'status' => 'on_progress'
            ]);
        }

        // 🔥 buat disposisi lanjutan (tetap 1 alur)
        $new = Disposition::create([
            'surat_id'     => $disposition->surat_id,
            'from_user_id' => auth()->id(),
            'to_user_id'   => $data['to_user_id'],
            'status'       => 'unread',
            'catatan'      => $data['catatan'] ?? null,
            'deadline'     => $data['deadline'] ?? $disposition->deadline
        ]);

        return $new;
    }

    // ================= DONE =================
    public function done(Disposition $disposition, array $data, $file = null)
    {
        // 🔒 hanya penerima yang boleh menyelesaikan
        if ($disposition->to_user_id !== auth()->id()) {
            throw new \Exception('Tidak punya akses');
        }

        // 🔥 format laporan
        $laporan = "[Laporan - " . now()->format('d M Y H:i') . "]\n" . ($data['catatan'] ?? '');

        // 🔥 gabungkan dengan catatan lama (aman dari null)
        $catatan = $disposition->catatan
            ? $disposition->catatan . "\n\n" . $laporan
            : $laporan;

        $disposition->update([
            'status'        => 'done',
            'catatan'       => $catatan,
            'file_laporan'  => $file
        ]);

        return $disposition;
    }

    // ================= MARK AS READ =================
    public function markAsRead(Disposition $disposition)
    {
        // 🔒 hanya penerima
        if ($disposition->to_user_id !== auth()->id()) {
            return;
        }

        // 🔥 hanya jika unread
        if ($disposition->status === 'unread') {
            $disposition->update([
                'status' => 'on_progress'
            ]);
        }

        return $disposition;
    }
}