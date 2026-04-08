<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;
use App\Models\Pesanan;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';
    protected $primaryKey = 'iddetail_pesanan';
    public $timestamps = false;

    protected $fillable = [
        'idmenu',
        'idpesanan',
        'jumlah',
        'harga',
        'subtotal',
        'timestamp',
        'catatan'
    ];

    // ============================
    // RELASI KE MENU
    // ============================
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'idmenu');
    }

    // ============================
    // RELASI KE PESANAN
    // ============================
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'idpesanan');
    }

    // ============================
    // ACCESSOR SUBTOTAL RUPIAH
    // ============================
    public function getSubtotalRupiahAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}