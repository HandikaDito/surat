<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'disposition_id',
        'title',
        'message',
        'url',
        'is_read',
        'read_at',
    ];

    protected $attributes = [
        'is_read' => false
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // ================= RELATION =================

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function disposition()
    {
        return $this->belongsTo(\App\Models\Disposition::class);
    }

    // ================= HELPER =================

    public function isRead()
    {
        return $this->is_read;
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    // ================= SCOPE =================

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}