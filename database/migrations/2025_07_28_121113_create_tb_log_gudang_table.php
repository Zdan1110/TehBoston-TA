<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_log_gudang', function (Blueprint $table) {
            $table->id();
            $table->string('aksi');
            $table->text('keterangan');
            $table->timestamp('waktu')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_log_gudang');
    }
};
