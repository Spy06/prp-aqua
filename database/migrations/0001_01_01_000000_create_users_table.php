<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel users dikustomisasi untuk sistem verifikasi PRP:
     * - Login memakai NIK (bukan email) — sesuai implementation plan §4 & §5
     * - Email dijadikan nullable (tetap ada sebagai kolom opsional)
     * - Tambah kolom: nik (FK ke karyawan), role ('karyawan'/'qa'), no_whatsapp
     *
     * FK ke karyawan tidak bisa dibuat di sini karena tabel karyawan belum ada.
     * FK akan ditambahkan di migration berikutnya setelah tabel karyawan dibuat.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            // NIK sebagai username login — FK ke karyawan ditambah di migration terpisah
            $table->string('nik')->nullable()->unique();
            // Role akun: 'karyawan' atau 'qa'
            $table->string('role')->default('karyawan');
            // Nomor WhatsApp untuk notifikasi Twilio (format: 628xxx)
            $table->string('no_whatsapp')->nullable();
            // Email dijadikan nullable — tidak dipakai untuk login
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
