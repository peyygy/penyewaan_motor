<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'penyewaan_id',
        'jumlah',
        'metode_pembayaran',
        'status',
        'external_id',
        'payment_url',
        'snap_token',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'paid_at' => 'datetime',
        ];
    }

    /**
     * Get the booking (penyewaan) for this transaction
     */
    public function penyewaan(): BelongsTo
    {
        return $this->belongsTo(Penyewaan::class);
    }

    /**
     * Check if transaction is successful
     */
    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    /**
     * Check if transaction is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if transaction is failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmount(): string
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    /**
     * Get payment method label
     */
    public function getPaymentMethodLabel(): string
    {
        return match($this->metode_pembayaran) {
            'midtrans_snap' => 'Midtrans (Credit Card/E-Wallet)',
            'transfer_bank' => 'Transfer Bank',
            'cash' => 'Tunai',
            default => ucfirst(str_replace('_', ' ', $this->metode_pembayaran)),
        };
    }
}