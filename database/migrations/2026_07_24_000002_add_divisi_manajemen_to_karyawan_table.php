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
        Schema::table('karyawan', function (Blueprint $table) {
            if (!Schema::hasColumn('karyawan', 'is_anggota_divisi_manajemen')) {
                $table->boolean('is_anggota_divisi_manajemen')->default(false)->after('status_aktif');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            if (Schema::hasColumn('karyawan', 'is_anggota_divisi_manajemen')) {
                $table->dropColumn('is_anggota_divisi_manajemen');
            }
        });
    }
};
