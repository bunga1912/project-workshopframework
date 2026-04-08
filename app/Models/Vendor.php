<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Menu;

class Vendor extends Model
{
    protected $table = 'vendor';
    protected $primaryKey = 'idvendor';
    public $timestamps = false;

    protected $fillable = [
        'nama_vendor',
        'user_id' // 🔥 WAJIB (relasi ke users)
    ];

    // ============================
    // RELASI KE USER (LOGIN)
    // ============================
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ============================
    // RELASI KE MENU
    // ============================
    public function menus()
    {
        return $this->hasMany(Menu::class, 'idvendor');
    }
}