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
        Schema::create('bosq_line', function (Blueprint $table) {
            $table->id();
            $table->string('nama_line');
            $table->foreignId('default_auditee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('bosq_sub_area', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departemen_id')->nullable()->constrained('departemen')->nullOnDelete();
            $table->string('nama_sub_area');
            $table->timestamps();
        });

        Schema::create('bosq_elemen_qfs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_elemen');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('bosq_temuan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_temuan');
            $table->foreignId('pelapor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('auditee_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('departemen_id')->constrained('departemen')->cascadeOnDelete();
            $table->foreignId('line_id')->nullable()->constrained('bosq_line')->cascadeOnDelete();
            $table->foreignId('sub_area_id')->constrained('bosq_sub_area')->cascadeOnDelete();
            $table->string('detail_sub_area')->nullable();
            $table->foreignId('elemen_qfs_id')->constrained('bosq_elemen_qfs')->cascadeOnDelete();
            $table->text('temuan_bqa');
            $table->string('tingkat_resiko'); // food_safety_risk, major_quality_risk, minor_quality_risk
            $table->string('dampak_temuan'); // positif, negatif
            $table->string('status'); // open, closed_pending_qa, closed_acc
            $table->timestamps();
        });

        Schema::create('bosq_tindak_lanjut', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bosq_temuan_id')->constrained('bosq_temuan')->cascadeOnDelete();
            $table->text('action')->nullable();
            $table->date('due_date')->nullable();
            $table->text('foto_bukti_path')->nullable();
            $table->string('status')->default('open');
            $table->boolean('acc_qa')->default(false);
            $table->date('tanggal_acc')->nullable();
            $table->text('catatan_qa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bosq_tindak_lanjut');
        Schema::dropIfExists('bosq_temuan');
        Schema::dropIfExists('bosq_elemen_qfs');
        Schema::dropIfExists('bosq_sub_area');
        Schema::dropIfExists('bosq_line');
    }
};
