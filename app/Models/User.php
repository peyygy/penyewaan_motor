<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'email',
        'no_tlpn',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    /**
     * Motors owned by this user (for pemilik role)
     */
    public function motors(): HasMany
    {
        return $this->hasMany(Motor::class, 'pemilik_id');
    }

    /**
     * Bookings made by this user (for penyewa role)  
     */
    public function penyewaans(): HasMany
    {
        return $this->hasMany(Penyewaan::class, 'penyewa_id');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    /**
     * Check if user is owner (pemilik)
     */
    public function isOwner(): bool
    {
        return $this->role === UserRole::PEMILIK;
    }

    /**
     * Check if user is renter (penyewa)
     */
    public function isRenter(): bool
    {
        return $this->role === UserRole::PENYEWA;
    }
}
