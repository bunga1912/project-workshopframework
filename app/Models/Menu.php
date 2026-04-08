<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Vendor;
use App\Models\DetailPesanan;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'idmenu';
    public $timestamps = false;

    protected $fillable = [
        'nama_menu',
        'harga',
        'path_gambar',
        'idvendor'
    ];

    // ============================
    // CASTING (BIAR HARGA PASTI INTEGER)
    // ============================
    protected $casts = [
        'harga' => 'integer',
    ];

    // ============================
    // MUTATOR (AUTO BERSIHIN FORMAT 10.000)
    // ============================
    public function setHargaAttribute($value)
    {
        $this->attributes['harga'] = str_replace('.', '', $value);
    }

    // ============================
    // RELASI KE VENDOR
    // ============================
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'idvendor');
    }

    // ============================
    // RELASI KE DETAIL PESANAN
    // ============================
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'idmenu');
    }

    // ============================
    // ACCESSOR FORMAT RUPIAH
    // ============================
    public function getHargaRupiahAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}