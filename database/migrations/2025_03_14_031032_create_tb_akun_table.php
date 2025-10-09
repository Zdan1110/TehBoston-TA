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
        Schema::create('tb_akun', function (Blueprint $table) {
            $table->id(); // Kolom ID utama, auto-incrementing integer (id default Laravel)
            $table->string('nama');
            $table->string('email')->unique(); // Kolom email, harus unik
            $table->string('no_hp');
            $table->string('username')->unique(); // Kolom username, harus unik
            $table->string('password');
            $table->string('type_akun')->default('user'); // Tipe akun, default 'user'
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_akun');
    }
};