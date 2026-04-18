<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'surat_masuk';

    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'tanggal_masuk',
        'pengirim',
        'perihal',
        'sifat',
        'file_path',
        'created_by',
        'status',
        'is_disposisi',
        'deadline',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_masuk' => 'date',
        'deadline' => 'date',
        'is_disposisi' => 'boolean',
    ];

    protected $appends = [
        'status_label',
        'status_color',
        'file_url',
    ];

    // ================= RELASI =================

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function dispositions()
    {
        return $this->hasMany(Disposition::class, 'surat_id');
    }

    // ================= STATUS =================

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'baru' => 'Belum Diproses',
            'diproses' => 'Sedang Diproses',
            'selesai' => 'Selesai',
            default => 'Unknown',
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'baru' => 'secondary',
            'diproses' => 'warning',
            'selesai' => 'success',
            default => 'primary',
        };
    }

    // ================= FILE =================

    public function getFileUrlAttribute()
    {
        return $this->file_path
            ? asset('storage/' . $this->file_path)
            : null;
    }

    // ================= HELPER =================

    // 🔥 cek apakah semua target sudah selesai
    public function isDone()
    {
        return ! $this->dispositions()
            ->whereHas('targets', function ($q) {
                $q->where('status', '!=', 'done');
            })
            ->exists();
    }

    // 🔥 cek apakah masih proses
    public function isOnProgress()
    {
        return $this->dispositions()
            ->whereHas('targets', function ($q) {
                $q->whereIn('status', ['unread','on_progress']);
            })
            ->exists();
    }
}