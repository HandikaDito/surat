<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DispositionTarget extends Model
{
    use HasFactory;

    protected $table = 'disposition_targets';

    protected $fillable = [
        'disposition_id',
        'user_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // 🔥 AUTO APPEND (biar gampang di blade)
    protected $appends = [
        'status_label',
        'status_color',
    ];

    // ================= RELASI =================

    public function disposition()
    {
        return $this->belongsTo(Disposition::class, 'disposition_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ================= STATUS =================

    const STATUS_UNREAD = 'unread';
    const STATUS_PROGRESS = 'on_progress';
    const STATUS_DONE = 'done';

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_UNREAD => 'Belum Dibaca',
            self::STATUS_PROGRESS => 'Sedang Dikerjakan',
            self::STATUS_DONE => 'Selesai',
            default => 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_UNREAD => 'secondary',
            self::STATUS_PROGRESS => 'warning',
            self::STATUS_DONE => 'success',
            default => 'primary',
        };
    }

    // ================= HELPER =================

    public function isUnread()
    {
        return $this->status === self::STATUS_UNREAD;
    }

    public function isOnProgress()
    {
        return $this->status === self::STATUS_PROGRESS;
    }

    public function isDone()
    {
        return $this->status === self::STATUS_DONE;
    }

    // ================= SCOPE =================

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', '!=', self::STATUS_DONE);
    }
}