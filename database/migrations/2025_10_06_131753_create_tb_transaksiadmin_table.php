<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_transaksiadmin', function (Blueprint $table) {
            $table->id();
            $table->string('id_transaksi')->unique();
            $table->string('transaksi');
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->decimal('jumlah', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_transaksiadmin');
    }
};
