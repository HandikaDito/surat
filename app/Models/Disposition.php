<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Disposition extends Model
{
    use HasFactory;

    protected $table = 'dispositions';

    protected $fillable = [
        'surat_id',
        'from_user_id',
        'catatan',
        'deadline',
    ];

    // ================= RELASI =================

    public function surat()
    {
        return $this->belongsTo(SuratMasuk::class, 'surat_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    // 🔥 MULTI TARGET (inti sistem)
    public function targets()
    {
        return $this->hasMany(DispositionTarget::class, 'disposition_id');
    }

    // 🔥 shortcut ke user
    public function targetUsers()
    {
        return $this->belongsToMany(
            User::class,
            'disposition_targets',
            'disposition_id',
            'user_id'
        )->withPivot('status')->withTimestamps();
    }

    // 📎 laporan
    public function reports()
    {
        return $this->hasMany(DispositionReport::class, 'disposition_id');
    }

    // ================= HELPER =================

    // 🔥 semua status target
    public function getStatusesAttribute()
    {
        return $this->targets->pluck('status');
    }

    // 🔥 cek semua selesai
    public function isDone()
    {
        return $this->targets->every(fn ($t) => $t->status === 'done');
    }

    // 🔥 ada yang belum dibaca
    public function hasUnread()
    {
        return $this->targets->contains(fn ($t) => $t->status === 'unread');
    }

    // 🔥 sedang berjalan
    public function isOnProgress()
    {
        return $this->targets->contains(fn ($t) => 
            in_array($t->status, ['unread','on_progress'])
        );
    }

    // ================= SCOPE =================

    public function scopeBySurat($query, $suratId)
    {
        return $query->where('surat_id', $suratId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at');
    }
}