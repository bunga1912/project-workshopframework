<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswas';

    protected $fillable = [
        'nama',
        'nim',
        'nfc_serial',
    ];

    /**
     * Relasi ke tabel absensis.
     * Satu mahasiswa bisa punya banyak data absensi.
     */
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    /**
     * Cek apakah mahasiswa sudah absen hari ini.
     */
    public function sudahAbsenHariIni(): bool
    {
        return $this->absensis()
            ->whereDate('waktu_absen', today())
            ->exists();
    }
}