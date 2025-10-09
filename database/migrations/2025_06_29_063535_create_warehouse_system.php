<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Suppliers Table
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id('id_supplier');
            $table->string('nama_supplier');
            $table->string('telepon');
            $table->text('alamat');
            $table->timestamps();
        });

        // Jenis Bahan Table
        Schema::create('jenis_bahan', function (Blueprint $table) {
            $table->id('id_jenis');
            $table->string('nama_jenis');
            $table->timestamps();
        });

        // Satuan Table
        Schema::create('satuans', function (Blueprint $table) {
            $table->id('id_satuan');
            $table->string('nama_satuan');
            $table->timestamps();
        });

        // Bahan Baku Table (with custom primary key)
        Schema::create('bahan_baku', function (Blueprint $table) {
            $table->id('id_bahan'); // Custom primary key name
            $table->string('nama_bahan');
            $table->foreignId('id_jenis')->constrained('jenis_bahan', 'id_jenis');
            $table->foreignId('id_satuan')->constrained('satuans', 'id_satuan');
            $table->decimal('harga', 12, 2);
            $table->integer('stok')->default(0);
            $table->timestamps();
        });

        // Franchises Table
        Schema::create('franchises', function (Blueprint $table) {
            $table->id('id_franchise');
            $table->string('nama_franchise');
            $table->string('kode_franchise')->unique();
            $table->string('lokasi');
            $table->timestamps();
        });

        // Barang Masuk Table
        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->id('id_masuk');
            $table->foreignId('id_bahan')->constrained('bahan_baku', 'id_bahan');
            $table->foreignId('id_supplier')->constrained('suppliers', 'id_supplier');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 12, 2);
            $table->text('keterangan')->nullable();
            $table->date('tanggal_masuk');
            $table->timestamps();
        });

        // Barang Keluar Table
        Schema::create('barang_keluar', function (Blueprint $table) {
            $table->id('id_keluar');
            $table->foreignId('id_bahan')->constrained('bahan_baku', 'id_bahan');
            $table->foreignId('id_franchise')->constrained('franchises', 'id_franchise');
            $table->integer('jumlah');
            $table->text('keterangan')->nullable();
            $table->date('tanggal_keluar');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('barang_keluar');
        Schema::dropIfExists('barang_masuk');
        Schema::dropIfExists('bahan_baku');
        Schema::dropIfExists('franchises');
        Schema::dropIfExists('satuans');
        Schema::dropIfExists('jenis_bahan');
        Schema::dropIfExists('suppliers');
    }
};