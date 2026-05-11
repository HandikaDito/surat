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
        'file_pdf',        // FIX
        'created_by',
        'status',
        'is_disposisi',
        'deadline',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_masuk' => 'date',
        'deadline'      => 'date',
        'is_disposisi'  => 'boolean',
    ];

    protected $appends = [
        'status_label',
        'status_color',
        'file_url',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function dispositions()
    {
        return $this->hasMany(Disposition::class, 'surat_id');
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'baru'     => 'Belum Diproses',
            'diproses' => 'Sedang Diproses',
            'selesai'  => 'Selesai',
            default    => ucfirst($this->status ?? 'baru'),
        };
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'baru'     => 'secondary',
            'diproses' => 'warning',
            'selesai'  => 'success',
            default    => 'primary',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | FILE
    |--------------------------------------------------------------------------
    */

    public function getFileUrlAttribute()
    {
        return $this->file_pdf
            ? asset('storage/' . $this->file_pdf)
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    public function isDone()
    {
        return ! $this->dispositions()
            ->whereHas('targets', function ($q) {
                $q->where('status', '!=', 'done');
            })
            ->exists();
    }

    public function isOnProgress()
    {
        return $this->dispositions()
            ->whereHas('targets', function ($q) {
                $q->whereIn('status', ['unread', 'on_progress']);
            })
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPE
    |--------------------------------------------------------------------------
    */

    public function scopeBulan($query, $bulan)
    {
        return $query->whereMonth('tanggal_masuk', $bulan);
    }

    public function scopeTahun($query, $tahun)
    {
        return $query->whereYear('tanggal_masuk', $tahun);
    }
}