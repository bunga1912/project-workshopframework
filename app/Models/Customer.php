<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'foto_blob',
        'foto_path',
    ];

    // Sembunyikan foto_blob saat serialisasi JSON supaya response tidak membengkak
    protected $hidden = ['foto_blob'];
}