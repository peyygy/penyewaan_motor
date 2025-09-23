<?php

namespace App\Enums;

enum MotorType: string
{
    case CC_100 = '100';
    case CC_125 = '125';
    case CC_150 = '150';

    public function label(): string
    {
        return match($this) {
            self::CC_100 => '100cc',
            self::CC_125 => '125cc',
            self::CC_150 => '150cc',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}