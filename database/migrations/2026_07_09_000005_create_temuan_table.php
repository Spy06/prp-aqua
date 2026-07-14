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
        Schema::create('temuan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_temuan');
            // Pelapor: user yang membuat laporan
            $table->foreignId('pelapor_id')->constrained('users')->onDelete('restrict');
            // PIC: user yang ditunjuk Pelapor untuk menindaklanjuti
            $table->foreignId('pic_id')->constrained('users')->onDelete('restrict');
            // Departemen lokasi temuan
            $table->foreignId('departemen_id')->constrained('departemen')->onDelete('restrict');
            $table->string('sub_area');
            $table->foreignId('klausul_id')->nullable()->constrained('klausul_prp')->onDelete('restrict');
            $table->string('foto_temuan_path')->nullable();
            // deskripsi akan di-encrypt di level aplikasi (Eloquent cast encrypted)
            $table->text('deskripsi');
            // Status: open | in_progress | closed_pending_qa | closed_acc
            $table->string('status')->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temuan');
    }
};
