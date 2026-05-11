<?php

namespace App\Services;

use App\Models\Disposition;
use App\Models\DispositionTarget;
use App\Models\DispositionReport;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DispositionService
{
    // ================= STORE =================
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            $user = auth()->user();

            // 🔒 ambil target
            $targets = User::whereIn('id', $data['target_users'])->get();

            foreach ($targets as $target) {
                if (!$user->canSendTo($target)) {
                    throw new \Exception('Tidak boleh kirim ke user tersebut');
                }
            }

            // 🔥 create disposition
            $disposition = Disposition::create([
                'surat_id'     => $data['surat_id'],
                'from_user_id' => $user->id,
                'catatan'      => $data['catatan'] ?? null,
                'deadline'     => $data['deadline'] ?? null,
            ]);

            // 🔥 insert target (multi user)
            $rows = [];
            foreach ($targets as $target) {
                $rows[] = [
                    'disposition_id' => $disposition->id,
                    'user_id'        => $target->id,
                    'status'         => 'unread',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            }

            DispositionTarget::insert($rows);

            return $disposition;
        });
    }

    // ================= FORWARD =================
    public function forward(Disposition $disposition, array $data)
    {
        return DB::transaction(function () use ($disposition, $data) {

            $user = auth()->user();

            // 🔒 pastikan user adalah target aktif
            $currentTarget = DispositionTarget::where('disposition_id', $disposition->id)
                ->where('user_id', $user->id)
                ->first();

            if (!$currentTarget) {
                throw new \Exception('Tidak punya akses');
            }

            // 🔒 ambil target baru
            $targets = User::whereIn('id', $data['target_users'])->get();

            foreach ($targets as $target) {
                if (!$user->canSendTo($target)) {
                    throw new \Exception('Target tidak valid');
                }
            }

            // 🔥 buat disposisi baru
            $new = Disposition::create([
                'surat_id'     => $disposition->surat_id,
                'from_user_id' => $user->id,
                'catatan'      => $data['catatan'] ?? null,
                'deadline'     => $data['deadline'] ?? $disposition->deadline,
            ]);

            // 🔥 insert target baru
            $rows = [];
            foreach ($targets as $target) {
                $rows[] = [
                    'disposition_id' => $new->id,
                    'user_id'        => $target->id,
                    'status'         => 'unread',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            }

            DispositionTarget::insert($rows);

            // 🔥 tandai target lama selesai
            $currentTarget->update([
                'status' => 'done'
            ]);

            return $new;
        });
    }

    // ================= DONE =================
    public function done(Disposition $disposition, array $data, $file)
    {
        return DB::transaction(function () use ($disposition, $data, $file) {

            $user = auth()->user();

            // 🔒 pastikan user target
            $target = DispositionTarget::where('disposition_id', $disposition->id)
                ->where('user_id', $user->id)
                ->first();

            if (!$target) {
                throw new \Exception('Tidak punya akses');
            }

            // 🔥 update status target
            $target->update([
                'status' => 'done'
            ]);

            // 🔥 simpan laporan
            DispositionReport::create([
                'disposition_id' => $disposition->id,
                'user_id'        => $user->id,
                'keterangan'     => $data['catatan'] ?? null,
                'file_path'      => $file,
                'file_type'      => $this->detectFileType($file),
            ]);

            return $target;
        });
    }

    // ================= MARK AS READ =================
    public function markAsRead(Disposition $disposition)
    {
        $user = auth()->user();

        $target = DispositionTarget::where('disposition_id', $disposition->id)
            ->where('user_id', $user->id)
            ->first();

        if ($target && $target->status === 'unread') {
            $target->update([
                'status' => 'on_progress'
            ]);
        }

        return $target;
    }

    // ================= HELPER =================
    protected function detectFileType($path)
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return match (true) {
            in_array($ext, ['jpg','jpeg','png']) => 'image',
            in_array($ext, ['mp4','mov']) => 'video',
            default => 'pdf',
        };
    }
}