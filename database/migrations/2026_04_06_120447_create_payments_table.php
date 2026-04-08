<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->integer('id_payments')->primary();
            $table->integer('idpesanan');

            $table->string('id_transaksi');     // dari Midtrans
            $table->string('jenis_pembayaran'); // VA / QRIS / dll
            $table->string('status_pembayaran'); // pending, settlement, expire
            $table->text('token_snap')->nullable(); // token untuk popup pembayaran

            $table->timestamp('waktu_buat')->useCurrent();

            $table->foreign('idpesanan')->references('idpesanan')->on('pesanan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
