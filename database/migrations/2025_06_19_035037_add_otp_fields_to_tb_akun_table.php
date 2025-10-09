<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_akun', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_akun', 'email')) {
                $table->string('email')->nullable();
            }

            $table->timestamps(); // atau kolom lain yang ingin kamu tambahkan
        });
    }

    public function down(): void
    {
        Schema::table('tb_akun', function (Blueprint $table) {
            $table->dropColumn(['email']);
            $table->dropTimestamps();
        });
    }
};
