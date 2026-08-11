<?php

namespace App\Services;

use App\Mail\TemuanNotificationMail;
use App\Models\BosqTemuan;
use App\Models\Temuan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailNotificationService
{
    /**
     * Jeda minimum (dalam detik) sebelum notifikasi yang SAMA dapat dikirim ulang
     * ke alamat yang SAMA untuk temuan yang SAMA.
     * Default: 2 jam. Mencegah spam dan pemblokiran akun Gmail.
     */
    private const COOLDOWN_SECONDS = 7200; // 2 jam

    // ──────────────────────────────────────────────────────────────────────────
    // SIVERA
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Kirim email notifikasi untuk temuan SIVERA.
     * Dilengkapi rate-limiting per (email, type, temuan_id) dengan cooldown 2 jam.
     */
    public function sendSiveraNotification(Temuan $temuan, string $type = 'baru', ?string $recipientEmail = null): bool
    {
        try {
            $temuan->loadMissing(['pic', 'pelapor', 'departemen', 'klausul']);

            $email = $recipientEmail;
            if (!$email) {
                $email = match($type) {
                    'baru'         => $temuan->pic?->email,
                    'tindaklanjut' => $temuan->pic?->email,
                    'bukti'        => null, // Sent to QA explicitly via recipientEmail
                    'closed'       => $temuan->pic?->email,
                    default        => $temuan->pic?->email,
                };
            }

            if (empty($email)) {
                Log::info("SIVERA Email skip: email kosong — temuan #{$temuan->id} type={$type}");
                return false;
            }

            // ── Rate Limiting ──────────────────────────────────────────────
            if ($this->isRateLimited('sivera', $type, $temuan->id, $email)) {
                return false;
            }

            // Tentukan nama penerima untuk personalisasi subject & greeting
            $recipientName = null;
            if ($recipientEmail) {
                $recipientName = \App\Models\User::where('email', $recipientEmail)->value('name');
            }
            if (!$recipientName) {
                $recipientName = $temuan->pic?->name;
            }

            Mail::to($email)->send(new TemuanNotificationMail($temuan, 'sivera', $type, $recipientName));

            $this->markSent('sivera', $type, $temuan->id, $email);
            Log::info("SIVERA Email [{$type}] → {$email} (Yth. {$recipientName}, temuan #{$temuan->id}) ✓");
            return true;

        } catch (\Throwable $e) {
            Log::error("Gagal kirim SIVERA Email temuan #{$temuan->id} [{$type}]: " . $e->getMessage());
            return false;
        }
    }

    // ──────────────────────────────────────────────────────────────────────────
    // BOS'Q
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Kirim email notifikasi untuk observasi BOS'Q.
     * Dilengkapi rate-limiting per (email, type, temuan_id) dengan cooldown 2 jam.
     */
    public function sendBosqNotification(BosqTemuan $bosqTemuan, string $type = 'baru', ?string $recipientEmail = null): bool
    {
        try {
            $bosqTemuan->loadMissing(['auditee', 'pelapor', 'departemen', 'subArea']);

            $email = $recipientEmail;
            if (!$email) {
                $email = match($type) {
                    'baru'        => null, // Tidak dikirim ke auditee saat baru
                    'subarea_pic' => $recipientEmail,
                    'tindaklanjut'=> $bosqTemuan->auditee?->email,
                    'bukti'       => null,
                    'closed'      => $bosqTemuan->auditee?->email,
                    default       => $bosqTemuan->auditee?->email,
                };
            }

            if (empty($email)) {
                Log::info("BOS'Q Email skip: email kosong — observasi #{$bosqTemuan->id} type={$type}");
                return false;
            }

            // ── Rate Limiting ──────────────────────────────────────────────
            if ($this->isRateLimited('bosq', $type, $bosqTemuan->id, $email)) {
                return false;
            }

            // Cari nama penerima berdasarkan email yang dituju
            $recipientName = \App\Models\User::where('email', $email)->value('name')
                ?? $bosqTemuan->auditee?->name
                ?? 'Tim BOS\'Q';

            Mail::to($email)->send(new TemuanNotificationMail($bosqTemuan, 'bosq', $type, $recipientName));

            $this->markSent('bosq', $type, $bosqTemuan->id, $email);
            Log::info("BOS'Q Email [{$type}] → {$email} (observasi #{$bosqTemuan->id}) ✓");
            return true;

        } catch (\Throwable $e) {
            Log::error("Gagal kirim BOS'Q Email observasi #{$bosqTemuan->id} [{$type}]: " . $e->getMessage());
            return false;
        }
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Rate Limiting Helpers
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Cek apakah notifikasi ini masih dalam masa cooldown (belum boleh dikirim ulang).
     */
    private function isRateLimited(string $system, string $type, int $entityId, string $email): bool
    {
        $key    = $this->rateLimitKey($system, $type, $entityId, $email);
        $locked = Cache::has($key);

        if ($locked) {
            $remaining = Cache::get($key . '_ttl', '?');
            Log::info("Email rate-limited [{$system}.{$type}] → {$email} (#{$entityId}) — cooldown aktif.");
        }

        return $locked;
    }

    /**
     * Tandai bahwa notifikasi ini baru saja dikirim (mulai cooldown).
     */
    private function markSent(string $system, string $type, int $entityId, string $email): void
    {
        $key = $this->rateLimitKey($system, $type, $entityId, $email);
        Cache::put($key, true, self::COOLDOWN_SECONDS);
    }

    /**
     * Buat cache key yang unik per (system, type, entity, email).
     */
    private function rateLimitKey(string $system, string $type, int $entityId, string $email): string
    {
        return sprintf(
            'email_rl:%s:%s:%d:%s',
            $system,
            $type,
            $entityId,
            md5(strtolower(trim($email)))
        );
    }
}