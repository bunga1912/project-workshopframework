<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Vendor;
use App\Models\Pesanan;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_google',
        'otp',
        'role' // 🔥 WAJIB (admin / vendor / customer)
    ];

    /**
     * Hidden attributes
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ============================
    // RELASI KE VENDOR
    // ============================
    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    // ============================
    // RELASI KE PESANAN
    // ============================
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }

    // ============================
    // HELPER ROLE (OPSIONAL 🔥)
    // ============================
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isVendor()
    {
        return $this->role === 'vendor';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }
}