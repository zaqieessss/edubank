<?php

namespace App\Enums;

enum GradeLevel: string
{
    case TENTH    = '10';
    case ELEVENTH = '11';
    case TWELFTH  = '12';
    case ALUMNI   = 'alumni';

    public function label(): string
    {
        return match ($this) {
            self::TENTH    => 'Kelas X (Sepuluh)',
            self::ELEVENTH => 'Kelas XI (Sebelas)',
            self::TWELFTH  => 'Kelas XII (Dua Belas)',
            self::ALUMNI   => 'Alumni / Lulus',
        };
    }

    public function roman(): string
    {
        return match ($this) {
            self::TENTH    => 'X',
            self::ELEVENTH => 'XI',
            self::TWELFTH  => 'XII',
            self::ALUMNI   => 'Alumni',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
