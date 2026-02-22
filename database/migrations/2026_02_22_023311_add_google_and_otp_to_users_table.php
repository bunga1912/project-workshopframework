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
        Schema::table('users', function (Blueprint $table) {

            // Kolom untuk menyimpan ID dari Google
            $table->string('id_google', 256)->nullable()->after('id');

            // Kolom untuk menyimpan OTP 6 karakter
            $table->string('otp', 6)->nullable()->after('password');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn('id_google');
            $table->dropColumn('otp');

        });
    }
};