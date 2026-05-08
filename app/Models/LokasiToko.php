<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LokasiToko extends Model
{
    protected $fillable = [
        'barcode',
        'nama_toko',
        'latitude',
        'longitude',
        'accuracy',
    ];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
        'accuracy'  => 'float',
    ];

    /**
     * Satu toko bisa punya banyak riwayat kunjungan.
     */
    public function kunjungans(): HasMany
    {
        return $this->hasMany(Kunjungan::class);
    }
}