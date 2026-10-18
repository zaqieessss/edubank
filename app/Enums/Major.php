<?php

namespace App\Enums;

enum Major: string
{
    case TKJ = 'TKJ'; // Teknik Komputer dan Jaringan (2 kelas)
    case RPL = 'RPL'; // Rekayasa Perangkat Lunak (2 kelas)
    case DKV = 'DKV'; // Desain Komunikasi Visual (2 kelas)
    case AKL = 'AKL'; // Akuntansi dan Lembaga (4 kelas)
    case LPS = 'LPS'; // Lembaga Perbankan Syariah (1 kelas)
    case MP  = 'MP';  // Manajemen Perkantoran (2 kelas)
    case BR  = 'BR';  // Bisnis Retail (2 kelas)
    case BD  = 'BD';  // Bisnis Digital (1 kelas)

    public function label(): string
    {
        return match ($this) {
            self::TKJ => 'Teknik Komputer dan Jaringan',
            self::RPL => 'Rekayasa Perangkat Lunak',
            self::DKV => 'Desain Komunikasi Visual',
            self::AKL => 'Akuntansi dan Lembaga',
            self::LPS => 'Lembaga Perbankan Syariah',
            self::MP  => 'Manajemen Perkantoran',
            self::BR  => 'Bisnis Retail',
            self::BD  => 'Bisnis Digital',
        };
    }

    public function classCount(): int
    {
        return match ($this) {
            self::TKJ => 2,
            self::RPL => 2,
            self::DKV => 2,
            self::AKL => 4,
            self::LPS => 1,
            self::MP  => 2,
            self::BR  => 2,
            self::BD  => 1,
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
