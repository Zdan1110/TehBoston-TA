<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifikasi_admin', function (Blueprint $table) {
            $table->id();
            $table->string('pesan');
            $table->timestamp('dibuat_pada')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi_admin');
    }
};
