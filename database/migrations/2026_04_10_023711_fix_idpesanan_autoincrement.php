<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    DB::statement('CREATE SEQUENCE IF NOT EXISTS pesanan_idpesanan_seq START 1');
    DB::statement('ALTER TABLE pesanan ALTER COLUMN idpesanan SET DEFAULT nextval(\'pesanan_idpesanan_seq\')');
    DB::statement('SELECT setval(\'pesanan_idpesanan_seq\', COALESCE((SELECT MAX(idpesanan) FROM pesanan), 1))');
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
