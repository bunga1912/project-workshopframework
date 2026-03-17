<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';

    protected $primaryKey = 'id_penjualan';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false; // karena tidak ada created_at & updated_at

    protected $fillable = [
        'timestamp',
        'total'
    ];

    // Relasi ke detail
    public function detail()
    {
        return $this->hasMany(PenjualanDetail::class, 'id_penjualan', 'id_penjualan');
    }
}