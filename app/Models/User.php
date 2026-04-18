<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_level',
        'jabatan',
        'parent_id',
        'unit',
        'no_hp',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'role_level' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'role_name',
    ];

    // ================= RBAC =================

    public function isAdmin()
    {
        return $this->role_level === 0;
    }

    public function isDirut()
    {
        return $this->role_level === 1;
    }

    public function isDirektur()
    {
        return $this->role_level === 2;
    }

    public function isKabag()
    {
        return $this->role_level === 3;
    }

    public function isKasubbag()
    {
        return $this->role_level === 4;
    }

    public function isStaff()
    {
        return $this->role_level === 5;
    }

    public function hasRoleLevel($level)
    {
        return $this->role_level === $level;
    }

    // ================= RULE DISPOSISI =================

    public function canSendTo(self $target)
    {
        // 🚫 Admin tidak boleh disposisi
        if ($this->isAdmin()) {
            return false;
        }

        // 🚫 Staff tidak boleh kirim ke atas
        if ($this->isStaff() && $target->role_level < 5) {
            return false;
        }

        // ✅ hanya boleh ke level sama atau lebih bawah
        return $target->role_level >= $this->role_level;
    }

    // ================= ACCESSOR =================

    public function getRoleNameAttribute()
    {
        return match ($this->role_level) {
            0 => 'Admin Sekretariat',
            1 => 'Direktur Utama',
            2 => 'Direktur',
            3 => 'Kabag / Kacab',
            4 => 'Kasubbag',
            5 => 'Staff',
            default => 'Unknown',
        };
    }

    // ================= RELASI =================

    // 📥 surat masuk
    public function suratMasuk()
    {
        return $this->hasMany(SuratMasuk::class, 'created_by');
    }

    // 📤 surat keluar (opsional)
    public function suratKeluar()
    {
        return $this->hasMany(SuratKeluar::class, 'created_by');
    }

    // 🔽 target disposisi (pivot)
    public function dispositionTargets()
    {
        return $this->hasMany(DispositionTarget::class, 'user_id');
    }

    // 🔽 disposisi yang diterima (multi-user)
    public function dispositionsReceived()
    {
        return $this->belongsToMany(
            Disposition::class,
            'disposition_targets',
            'user_id',
            'disposition_id'
        )->withPivot('status')->withTimestamps();
    }

    // 🔼 disposisi yang dikirim
    public function dispositionsSent()
    {
        return $this->hasMany(Disposition::class, 'from_user_id');
    }

    // 📎 laporan user
    public function dispositionReports()
    {
        return $this->hasMany(DispositionReport::class, 'user_id');
    }

    // ================= HIERARCHY =================

    // 🔼 atasan
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    // 🔽 bawahan
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}