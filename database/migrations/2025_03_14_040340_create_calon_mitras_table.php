<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_calonmitra', function (Blueprint $table) {
            $table->id('id_calon');
            $table->string('nama_lengkap');
            $table->string('no_ktp')->unique();
            $table->string('provinsi');
            $table->string('kota');
            $table->string('kelurahan');
            $table->string('ktp'); // Path file KTP
            $table->string('no_hp');
            $table->text('alamat_lengkap');
            $table->string('pas_photo'); // Path foto calon mitra

            // Data Lokasi Usaha
            $table->string('provinsi_usaha');
            $table->string('kota_usaha');
            $table->string('kelurahan_usaha');
            $table->string('kecamatan_usaha');
            $table->text('alamat_usaha');
            $table->string('kode_pos');
            $table->string('titik_koordinat'); // Titik koordinat lokasi usaha
            $table->string('latitude')->nullable(); // Latitude dipisah
            $table->string('longitude')->nullable(); // Longitude dipisah
            $table->string('lokasi_usaha')->nullable(); // Path file lokasi usaha (optional)
        });

        Schema::table('tb_calonmitra', function (Blueprint $table) {
        $table->string('latitude')->nullable()->after('titik_koordinat');
        $table->string('longitude')->nullable()->after('latitude');
    });
    }

    /**
     * Rollback migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_calonmitra', function (Blueprint $table) {
        $table->dropColumn(['latitude', 'longitude']);
        });
        Schema::dropIfExists('tb_calonmitra');
    }
};
