<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case PEMILIK = 'pemilik';
    case PENYEWA = 'penyewa';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::PEMILIK => 'Pemilik Kendaraan',
            self::PENYEWA => 'Penyewa',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}