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
                $table->string('email')->unique()->after('nama');
            }
            if (!Schema::hasColumn('tb_akun', 'created_at') && !Schema::hasColumn('tb_akun', 'updated_at')) {
                $table->timestamps();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tb_akun', function (Blueprint $table) {
            if (Schema::hasColumn('tb_akun', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('tb_akun', 'created_at')) {
                $table->dropTimestamps();
            }
        });
    }
};
