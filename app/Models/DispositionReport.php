<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DispositionReport extends Model
{
    use HasFactory;

    protected $table = 'disposition_reports';

    protected $fillable = [
        'disposition_id',
        'user_id',
        'keterangan',
        'file_path',
        'file_type',
    ];

    protected $appends = [
        'file_url',
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

    // ================= FILE =================

    public function getFileUrlAttribute()
    {
        return $this->file_path
            ? asset('storage/' . $this->file_path)
            : null;
    }

    // ================= HELPER =================

    public function isImage()
    {
        return $this->file_type === 'image';
    }

    public function isVideo()
    {
        return $this->file_type === 'video';
    }

    public function isPdf()
    {
        return $this->file_type === 'pdf';
    }

    // ================= SCOPE =================

    public function scopeByDisposition($query, $id)
    {
        return $query->where('disposition_id', $id);
    }

    public function scopeLatest($query)
    {
        return $query->latest();
    }
}