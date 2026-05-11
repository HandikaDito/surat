<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SuratKeluar extends Model
{
    use HasFactory;

    protected $table = 'surat_keluar';

    // ================= CONSTANT STATUS =================
    const STATUS_DRAFT    = 'draft';
    const STATUS_REVIEW   = 'review';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // ================= FILLABLE =================
    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'tujuan',
        'perihal',
        'isi_surat',
        'file_path',     // 🔥 NEW
        'status',
        'created_by',
        'approved_by',   // 🔥 NEW
        'approved_at',   // 🔥 NEW
    ];

    // ================= CAST =================
    protected $casts = [
        'tanggal_surat' => 'date',
        'approved_at'   => 'datetime', // 🔥 NEW
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    // ================= DEFAULT =================
    protected $attributes = [
        'status' => self::STATUS_DRAFT,
    ];

    // ================= APPENDS =================
    protected $appends = [
        'status_label',
        'status_color',
        'file_url', // 🔥 NEW
    ];

    // ================= RELASI =================

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // 🔥 NEW
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ================= SCOPE =================

    public function scopeByUser($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeReview($query)
    {
        return $query->where('status', self::STATUS_REVIEW);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    // ================= ACCESSOR =================

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_DRAFT    => 'Draft',
            self::STATUS_REVIEW   => 'Menunggu Review',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
            default               => 'Unknown',
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_DRAFT    => 'secondary',
            self::STATUS_REVIEW   => 'warning',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
            default               => 'primary',
        };
    }

    // 🔥 FILE ACCESSOR
    public function getFileUrlAttribute()
    {
        return $this->file_path
            ? asset('storage/' . $this->file_path)
            : null;
    }

    // ================= HELPER =================

    public function isDraft()
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isReview()
    {
        return $this->status === self::STATUS_REVIEW;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }
}