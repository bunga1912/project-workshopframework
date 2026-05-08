<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('toko_id')
                  ->constrained('lokasi_toko')
                  ->onDelete('cascade');               // hapus kunjungan jika toko dihapus

            // posisi sales saat melakukan kunjungan
            $table->decimal('sales_lat', 10, 7);
            $table->decimal('sales_lng', 10, 7);
            $table->float('sales_accuracy');            // akurasi GPS sales (meter)

            $table->float('jarak_meter');               // jarak haversine antara toko dan sales
            $table->float('threshold_efektif');         // threshold + acc_toko + acc_sales

            $table->enum('status', ['diterima', 'ditolak']);

            $table->timestamp('visited_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
    }
};