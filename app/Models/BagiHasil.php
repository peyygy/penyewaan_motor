<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BagiHasil extends Model
{
    use HasFactory;

    protected $fillable = [
        'penyewaan_id',
        'bagi_hasil_pemilik',
        'bagi_hasil_admin',
        'tanggal',
        'settled_at',
    ];

    protected function casts(): array
    {
        return [
            'bagi_hasil_pemilik' => 'integer',
            'bagi_hasil_admin' => 'integer',
            'tanggal' => 'date',
            'settled_at' => 'datetime',
        ];
    }

    /**
     * Get the booking (penyewaan) for this revenue share
     */
    public function penyewaan(): BelongsTo
    {
        return $this->belongsTo(Penyewaan::class);
    }

    /**
     * Calculate revenue share (70% owner, 30% admin)
     */
    public static function calculateShare(int $totalAmount): array
    {
        $ownerShare = round($totalAmount * 0.7);
        $adminShare = $totalAmount - $ownerShare; // Ensures total equals exactly
        
        return [
            'owner' => $ownerShare,
            'admin' => $adminShare
        ];
    }

    /**
     * Get total revenue
     */
    public function getTotalRevenue(): int
    {
        return $this->bagi_hasil_pemilik + $this->bagi_hasil_admin;
    }

    /**
     * Get formatted owner share
     */
    public function getFormattedOwnerShare(): string
    {
        return 'Rp ' . number_format($this->bagi_hasil_pemilik, 0, ',', '.');
    }

    /**
     * Get formatted admin share
     */
    public function getFormattedAdminShare(): string
    {
        return 'Rp ' . number_format($this->bagi_hasil_admin, 0, ',', '.');
    }

    /**
     * Check if revenue is settled
     */
    public function isSettled(): bool
    {
        return !is_null($this->settled_at);
    }
}