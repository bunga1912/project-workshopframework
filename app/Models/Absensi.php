<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensis';

    protected $fillable = [
        'mahasiswa_id',
        'waktu_absen',
        'status',
    ];

    protected $casts = [
        'waktu_absen' => 'datetime',
    ];

    /**
     * Relasi ke tabel mahasiswas.
     * Satu absensi dimiliki oleh satu mahasiswa.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}