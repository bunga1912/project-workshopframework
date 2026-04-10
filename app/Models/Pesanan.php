<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DetailPesanan;
use App\Models\Payment;
use App\Models\User;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'idpesanan';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'timestamp',
        'total',
        'metode_bayar',
        'status_bayar'
    ];

    // ============================
    // RELASI KE USER (OPTIONAL)
    // ============================
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ============================
    // RELASI KE DETAIL PESANAN
    // ============================
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'idpesanan');
    }

    // ============================
    // RELASI KE PAYMENT
    // ============================
    public function payments()
    {
        return $this->hasMany(Payment::class, 'idpesanan');
    }

    // ============================
    // ACCESSOR FORMAT RUPIAH
    // ============================
    public function getTotalRupiahAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }
}