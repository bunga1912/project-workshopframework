<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Antrian extends Model
{
    protected $fillable = ['idpesanan', 'idvendor', 'nomor', 'status'];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'idpesanan', 'idpesanan');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'idvendor', 'idvendor');
    }
}