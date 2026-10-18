<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN  = 'admin';
    case TELLER = 'teller';
    case SAVER  = 'saver';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN  => 'Administrator & Supervisor',
            self::TELLER => 'Piket Teller Loket',
            self::SAVER  => 'Nasabah Tabungan Siswa',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
