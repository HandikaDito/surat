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

    protected $appends = [
        'statuses',
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

    public function targets()
    {
        return $this->hasMany(DispositionTarget::class, 'disposition_id');
    }

    public function targetUsers()
    {
        return $this->belongsToMany(
            User::class,
            'disposition_targets',
            'disposition_id',
            'user_id'
        )->withPivot('status')->withTimestamps();
    }

    public function reports()
    {
        return $this->hasMany(DispositionReport::class, 'disposition_id');
    }

    // ================= HELPER =================

    public function getStatusesAttribute()
    {
        if ($this->relationLoaded('targets')) {
            return $this->targets->pluck('status');
        }

        return $this->targets()->pluck('status');
    }

    public function isDone()
    {
        if ($this->relationLoaded('targets')) {
            return $this->targets->every(fn ($t) => $t->status === 'done');
        }

        return ! $this->targets()
            ->where('status', '!=', 'done')
            ->exists();
    }

    public function hasUnread()
    {
        if ($this->relationLoaded('targets')) {
            return $this->targets->contains(fn ($t) => $t->status === 'unread');
        }

        return $this->targets()
            ->where('status', 'unread')
            ->exists();
    }

    public function isOnProgress()
    {
        if ($this->relationLoaded('targets')) {
            return $this->targets->contains(fn ($t) =>
                in_array($t->status, ['unread','on_progress'])
            );
        }

        return $this->targets()
            ->whereIn('status', ['unread','on_progress'])
            ->exists();
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

    // 🔥 BONUS: eager load default (opsional)
    public function scopeWithRelations($query)
    {
        return $query->with(['fromUser', 'targets.user']);
    }
}