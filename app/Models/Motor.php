<?php

namespace App\Models;

use App\Enums\MotorStatus;
use App\Enums\MotorType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Motor extends Model
{
    use HasFactory;

    protected $fillable = [
        'pemilik_id',
        'merk',
        'tipe_cc',
        'no_plat',
        'status',
        'photo',
        'dokumen_kepemilikan',
    ];

    protected function casts(): array
    {
        return [
            'status' => MotorStatus::class,
            'tipe_cc' => MotorType::class,
        ];
    }

    /**
     * Get the owner of this motor
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pemilik_id');
    }

    /**
     * Get the rental rates for this motor
     */
    public function tarifRental(): HasOne
    {
        return $this->hasOne(TarifRental::class);
    }

    /**
     * Get all bookings for this motor
     */
    public function penyewaans(): HasMany
    {
        return $this->hasMany(Penyewaan::class);
    }

    /**
     * Get active booking for this motor
     */
    public function activeBooking(): HasOne
    {
        return $this->hasOne(Penyewaan::class)->whereIn('status', ['confirmed', 'active']);
    }

    /**
     * Check if motor is available for booking
     */
    public function isAvailable(): bool
    {
        return $this->status === MotorStatus::AVAILABLE;
    }

    /**
     * Check if motor has rental rates set
     */
    public function hasRates(): bool
    {
        return $this->tarifRental()->exists();
    }

    /**
     * Get the photo URL
     */
    public function getPhotoUrlAttribute(): string
    {
        return $this->photo ? asset('storage/' . $this->photo) : asset('images/default-motor.jpg');
    }

    /**
     * Get the document URL
     */
    public function getDocumentUrlAttribute(): string
    {
        return $this->dokumen_kepemilikan ? asset('storage/' . $this->dokumen_kepemilikan) : '';
    }
}