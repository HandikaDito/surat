<?php

namespace App\Models;

use App\Enums\Config as ConfigEnum;
use App\Enums\LetterType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'filename',
        'extension',
        'letter_id',
        'user_id',
    ];

    protected $appends = [
        'path_url',
    ];

    /**
     * Accessor path_url
     * Mengembalikan URL ke file, fallback ke filename jika path null
     */
    public function getPathUrlAttribute(): string
    {
        // Gunakan path jika ada dan file benar-benar ada
        if (!empty($this->path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->path)) {
            return asset('storage/' . $this->path);
        }

        // fallback ke folder attachments dengan filename
        if (!empty($this->filename) && \Illuminate\Support\Facades\Storage::disk('public')->exists('attachments/' . $this->filename)) {
            return asset('storage/attachments/' . $this->filename);
        }

        // fallback aman, tetap mengembalikan URL walau file hilang
        return asset('storage/attachments/' . ($this->filename ?? 'file_not_found.txt'));
    }

    public function scopeType($query, LetterType $type)
    {
        return $query->whereHas('letter', function ($query) use ($type) {
            return $query->where('type', $type->type());
        });
    }

    public function scopeIncoming($query)
    {
        return $this->scopeType($query, LetterType::INCOMING);
    }

    public function scopeOutgoing($query)
    {
        return $this->scopeType($query, LetterType::OUTGOING);
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($query, $find) {
            return $query->where(function ($q) use ($find) {
                $q->where('filename', 'LIKE', '%' . $find . '%')
                  ->orWhereHas('letter', function ($q2) use ($find) {
                      $q2->where('reference_number', 'LIKE', '%' . $find . '%');
                  });
            });
        });
    }

    public function scopeRender($query, $search)
    {
        return $query
            ->with(['letter'])
            ->search($search)
            ->latest('created_at')
            ->paginate(Config::getValueByCode(ConfigEnum::PAGE_SIZE) ?? 10)
            ->appends([
                'search' => $search,
            ]);
    }

    /**
     * Relasi ke Letter
     */
    public function letter(): BelongsTo
    {
        return $this->belongsTo(Letter::class);
    }

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
