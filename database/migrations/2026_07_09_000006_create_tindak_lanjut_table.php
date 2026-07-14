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
        Schema::create('tindak_lanjut', function (Blueprint $table) {
            $table->id();
            $table->foreignId('temuan_id')->constrained('temuan')->onDelete('cascade');
            // action akan di-encrypt di level aplikasi jika dianggap sensitif
            $table->text('action')->nullable();
            $table->date('due_date')->nullable();
            $table->string('foto_bukti_path')->nullable();
            // Status: open | in_progress | closed_pending_qa | closed_acc
            $table->string('status')->default('open');
            $table->boolean('acc_qa')->default(false);
            $table->date('tanggal_acc')->nullable();
            // catatan_qa akan di-encrypt di level aplikasi
            $table->text('catatan_qa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindak_lanjut');
    }
};
