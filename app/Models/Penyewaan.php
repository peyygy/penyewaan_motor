<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Penyewaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'penyewa_id',
        'motor_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'tipe_durasi',
        'harga',
        'status',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'harga' => 'integer',
            'status' => BookingStatus::class,
        ];
    }

    /**
     * Get the renter (penyewa) of this booking
     */
    public function penyewa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penyewa_id');
    }

    /**
     * Get the motor being rented
     */
    public function motor(): BelongsTo
    {
        return $this->belongsTo(Motor::class);
    }

    /**
     * Get the transactions for this booking
     */
    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    /**
     * Get the revenue sharing record for this booking
     */
    public function bagiHasil(): HasOne
    {
        return $this->hasOne(BagiHasil::class);
    }

    /**
     * Calculate duration in days
     */
    public function getDurationInDays(): int
    {
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai);
    }

    /**
     * Check if booking is active
     */
    public function isActive(): bool
    {
        return $this->status === BookingStatus::ACTIVE;
    }

    /**
     * Check if booking is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === BookingStatus::COMPLETED;
    }

    /**
     * Check if booking can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [BookingStatus::PENDING, BookingStatus::CONFIRMED]);
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDuration(): string
    {
        $days = $this->getDurationInDays();
        
        return match($this->tipe_durasi) {
            'harian' => $days . ' hari',
            'mingguan' => ceil($days / 7) . ' minggu',
            'bulanan' => ceil($days / 30) . ' bulan',
            default => $days . ' hari',
        };
    }

    /**
     * Get total paid amount
     */
    public function getTotalPaid(): int
    {
        return $this->transaksis()->where('status', 'success')->sum('jumlah');
    }

    /**
     * Check if booking is fully paid
     */
    public function isFullyPaid(): bool
    {
        return $this->getTotalPaid() >= $this->harga;
    }
}