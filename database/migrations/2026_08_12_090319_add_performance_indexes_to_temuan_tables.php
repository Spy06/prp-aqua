<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan index performa di kolom yang sering dipakai untuk filter & sort.
     * Berdampak pada kecepatan query di: Dashboard, Daftar Temuan, Rekap Periode.
     */
    public function up(): void
    {
        // ── Tabel SIVERA: temuan ──────────────────────────────────────────────
        Schema::table('temuan', function (Blueprint $table) {
            // Filter tanggal (WHERE tanggal_temuan BETWEEN ... AND ...)
            $table->index('tanggal_temuan', 'idx_temuan_tanggal');
            // Filter status (WHERE status = 'open' / 'in_progress' / etc.)
            $table->index('status', 'idx_temuan_status');
            // Filter departemen (WHERE departemen_id = ...)
            $table->index('departemen_id', 'idx_temuan_departemen');
        });

        // ── Tabel BOS'Q: bosq_temuan ──────────────────────────────────────────
        Schema::table('bosq_temuan', function (Blueprint $table) {
            // Filter tanggal observasi BOS'Q
            $table->index('tanggal_temuan', 'idx_bosq_tanggal');
            // Filter status observasi
            $table->index('status', 'idx_bosq_status');
            // Filter departemen observasi
            $table->index('departemen_id', 'idx_bosq_departemen');
        });
    }

    /**
     * Hapus index jika migration di-rollback.
     */
    public function down(): void
    {
        Schema::table('temuan', function (Blueprint $table) {
            $table->dropIndex('idx_temuan_tanggal');
            $table->dropIndex('idx_temuan_status');
            $table->dropIndex('idx_temuan_departemen');
        });

        Schema::table('bosq_temuan', function (Blueprint $table) {
            $table->dropIndex('idx_bosq_tanggal');
            $table->dropIndex('idx_bosq_status');
            $table->dropIndex('idx_bosq_departemen');
        });
    }
};
