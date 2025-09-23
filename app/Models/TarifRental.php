<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TarifRental extends Model
{
    use HasFactory;

    protected $fillable = [
        'motor_id',
        'tarif_harian',
        'tarif_mingguan',
        'tarif_bulanan',
    ];

    protected function casts(): array
    {
        return [
            'tarif_harian' => 'integer',
            'tarif_mingguan' => 'integer',
            'tarif_bulanan' => 'integer',
        ];
    }

    /**
     * Get the motor that owns this tariff
     */
    public function motor(): BelongsTo
    {
        return $this->belongsTo(Motor::class);
    }

    /**
     * Calculate price based on duration type and days
     */
    public function calculatePrice(string $durationType, int $days = 1): int
    {
        return match($durationType) {
            'harian' => $this->tarif_harian * $days,
            'mingguan' => $this->tarif_mingguan * ceil($days / 7),
            'bulanan' => $this->tarif_bulanan * ceil($days / 30),
            default => $this->tarif_harian * $days,
        };
    }

    /**
     * Get rate by duration type
     */
    public function getRateByType(string $durationType): int
    {
        return match($durationType) {
            'harian' => $this->tarif_harian,
            'mingguan' => $this->tarif_mingguan,
            'bulanan' => $this->tarif_bulanan,
            default => $this->tarif_harian,
        };
    }
}