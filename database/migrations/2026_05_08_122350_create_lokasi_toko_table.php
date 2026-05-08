<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lokasi_toko', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->unique();        // kode unik untuk scan QR/barcode
            $table->string('nama_toko');
            $table->decimal('latitude', 10, 7);         // presisi 7 angka di belakang koma
            $table->decimal('longitude', 10, 7);
            $table->float('accuracy');                  // akurasi dalam meter saat titik diambil
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lokasi_toko');
    }
};