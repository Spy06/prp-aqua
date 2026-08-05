<?php

namespace App\Services;

use App\Models\BosqTemuan;
use App\Models\Temuan;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailNotificationService
{
    /**
     * Kirim email notifikasi untuk temuan SIVERA.
     */
    public function sendSiveraNotification(Temuan $temuan, string $type, ?string $recipientEmail = null): bool
    {
        try {
            // Pastikan relasi pelapor, pic, departemen, klausul ter-load penuh
            $temuan->loadMissing(['pic', 'pelapor', 'departemen', 'klausul']);

            $email = $recipientEmail;

            // Default penerima jika tidak dispesifikasikan secara eksplisit
            if (!$email) {
                $email = match($type) {
                    'baru'        => $temuan->pic?->email,
                    'tindaklanjut' => $temuan->pelapor?->email,
                    'closed'      => $temuan->pic?->email ?? $temuan->pelapor?->email,
                    default       => $temuan->pic?->email,
                };
            }

            if (empty($email)) {
                Log::warning("SIVERA Email Notification Skipped: Tidak ada alamat email untuk temuan #{$temuan->id} [Tipe: {$type}]");
                return false;
            }

            Mail::send('emails.sivera-temuan', [
                'temuan' => $temuan,
                'type'   => $type,
            ], function ($message) use ($email, $temuan, $type) {
                $subject = match($type) {
                    'baru'        => "[SIVERA] Temuan Audit Baru Ditugaskan kepada Anda (#{$temuan->id})",
                    'tindaklanjut' => "[SIVERA] Rencana Aksi Perbaikan Diperbarui (#{$temuan->id})",
                    'bukti'       => "[SIVERA] Bukti Perbaikan Diunggah (#{$temuan->id})",
                    'closed'      => "[SIVERA] Temuan Audit Dinyatakan CLOSED/ACC (#{$temuan->id})",
                    default       => "[SIVERA] Update Temuan Audit (#{$temuan->id})",
                };

                $message->to($email)
                        ->subject($subject);
            });

            Log::info("SIVERA Email SUKSES terkirim ke {$email} untuk temuan #{$temuan->id} [Tipe: {$type}]");
            return true;
        } catch (\Throwable $e) {
            Log::error("Gagal mengirim SIVERA Email ke {$recipientEmail} untuk temuan #{$temuan->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim email notifikasi untuk observasi BOS'Q.
     */
    public function sendBosqNotification(BosqTemuan $bosqTemuan, string $type, ?string $recipientEmail = null): bool
    {
        try {
            $bosqTemuan->loadMissing(['auditee', 'pelapor', 'departemen', 'subArea']);

            $email = $recipientEmail ?? $bosqTemuan->auditee?->email ?? $bosqTemuan->pelapor?->email;

            if (empty($email)) {
                Log::warning("BOS'Q Email Notification Skipped: Tidak ada alamat email untuk observasi #{$bosqTemuan->id}");
                return false;
            }

            Mail::send('emails.bosq-temuan', [
                'temuan' => $bosqTemuan,
                'type'   => $type,
            ], function ($message) use ($email, $bosqTemuan, $type) {
                $subject = match($type) {
                    'baru'        => "[BOS'Q] Observasi Perilaku Baru (#{$bosqTemuan->id})",
                    'subarea_pic' => "[BOS'Q] Laporan Perilaku Baru di Sub Area Anda (#{$bosqTemuan->id})",
                    'closed'      => "[BOS'Q] Observasi Perilaku Status CLOSED (#{$bosqTemuan->id})",
                    default       => "[BOS'Q] Update Observasi Perilaku (#{$bosqTemuan->id})",
                };

                $message->to($email)
                        ->subject($subject);
            });

            Log::info("BOS'Q Email SUKSES terkirim ke {$email} untuk observasi #{$bosqTemuan->id} [Tipe: {$type}]");
            return true;
        } catch (\Throwable $e) {
            Log::error("Gagal mengirim BOS'Q Email ke {$recipientEmail} untuk observasi #{$bosqTemuan->id}: " . $e->getMessage());
            return false;
        }
    }
}
