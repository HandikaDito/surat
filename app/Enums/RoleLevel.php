<?php

namespace App\Enums;

enum RoleLevel: int
{
    case ADMIN = 0;
    case DIRUT = 1;
    case DIREKTUR = 2;
    case KABAG = 3;
    case KASUBBAG = 4;
    case STAFF = 5;

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin Sekretariat',
            self::DIRUT => 'Direktur Utama',
            self::DIREKTUR => 'Direktur',
            self::KABAG => 'Kepala Bagian',
            self::KASUBBAG => 'Kepala Sub Bagian',
            self::STAFF => 'Staff',
        };
    }
}