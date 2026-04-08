<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'idpesanan',
        'id_transaksi',
        'jenis_pembayaran',
        'status_pembayaran',
        'token_snap',
        'waktu_buat'
    ];

    // Relasi ke pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'idpesanan');
    }
}