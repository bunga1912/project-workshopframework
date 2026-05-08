<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kunjungan extends Model
{
    protected $fillable = [
        'toko_id',
        'sales_lat',
        'sales_lng',
        'sales_accuracy',
        'jarak_meter',
        'threshold_efektif',
        'status',
        'visited_at',
    ];

    protected $casts = [
        'sales_lat'         => 'float',
        'sales_lng'         => 'float',
        'sales_accuracy'    => 'float',
        'jarak_meter'       => 'float',
        'threshold_efektif' => 'float',
        'visited_at'        => 'datetime',
    ];

    /**
     * Setiap kunjungan dimiliki oleh satu toko.
     */
    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class);
    }
}