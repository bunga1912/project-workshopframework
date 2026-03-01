<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';

    protected $primaryKey = 'id_barang'; // PK kamu

    public $incrementing = false; // karena string (BRG001)

    protected $keyType = 'string'; // tipe PK

    public $timestamps = false; // MATIKAN timestamp

    protected $fillable = [
        'id_barang',
        'nama',
        'harga'
    ];
}