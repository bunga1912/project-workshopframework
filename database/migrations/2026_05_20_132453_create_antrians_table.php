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
        Schema::create('antrians', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('idpesanan');
            $table->foreign('idpesanan')
                ->references('idpesanan')
                ->on('pesanan')
                ->onDelete('cascade');

            $table->unsignedBigInteger('idvendor');
            $table->foreign('idvendor')
                ->references('idvendor')
                ->on('vendor')
                ->onDelete('cascade');

            $table->integer('nomor');

            $table->enum('status', ['menunggu', 'dipanggil', 'selesai', 'terlambat'])
                ->default('menunggu');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrians');
    }
};
