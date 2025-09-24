<?php

namespace App\Enums;

enum MotorStatus: string
{
    case PENDING = 'pending';
    case VERIFIED = 'verified';
    case AVAILABLE = 'available';
    case RENTED = 'rented';
    case MAINTENANCE = 'maintenance';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Menunggu Verifikasi',
            self::VERIFIED => 'Terverifikasi',
            self::AVAILABLE => 'Tersedia',
            self::RENTED => 'Sedang Disewa',
            self::MAINTENANCE => 'Maintenance',
            self::REJECTED => 'Ditolak',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'yellow',
            self::VERIFIED => 'blue',
            self::AVAILABLE => 'green',
            self::RENTED => 'red',
            self::MAINTENANCE => 'gray',
            self::REJECTED => 'red',
        };
    }
}