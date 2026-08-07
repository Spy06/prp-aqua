<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->boolean('is_pic')->default(false)->after('status_aktif');
        });

        // Set existing karyawan who have user records to is_pic = true so existing data works seamlessly
        DB::statement("UPDATE karyawan SET is_pic = 1 WHERE nik IN (SELECT DISTINCT nik FROM users WHERE nik IS NOT NULL)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropColumn('is_pic');
        });
    }
};
