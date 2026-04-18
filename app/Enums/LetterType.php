<?php

namespace App\Enums;

enum LetterType: string
{
    case INCOMING = 'incoming';
    case OUTGOING = 'outgoing';

    /**
     * 🔥 Label untuk ditampilkan di UI
     */
    public function label(): string
    {
        return match ($this) {
            self::INCOMING => 'Surat Masuk',
            self::OUTGOING => 'Surat Keluar',
        };
    }
}