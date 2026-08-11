<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class TemuanNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $temuan;
    public string $system;
    public string $type;
    public ?string $recipientName;

    /**
     * @param mixed       $temuan        Instance Temuan (SIVERA) atau BosqTemuan (BOS'Q)
     * @param string      $system        'sivera' atau 'bosq'
     * @param string      $type          'baru', 'tindaklanjut', 'bukti', 'closed'
     * @param string|null $recipientName Nama penerima untuk personalisasi subject
     */
    public function __construct($temuan, string $system = 'sivera', string $type = 'baru', ?string $recipientName = null)
    {
        $this->temuan        = $temuan;
        $this->system        = $system;
        $this->type          = $type;
        $this->recipientName = $recipientName;
    }

    public function envelope(): Envelope
    {
        $systemLabel = $this->system === 'bosq' ? "BOS'Q" : 'SIVERA';

        // Subject dipersonalisasi & menyertakan ID temuan agar tiap email benar-benar unik
        $typeLabel = match($this->type) {
            'baru'         => 'Penunjukan PIC – Tindak Lanjut Diperlukan',
            'tindaklanjut' => 'Rencana Aksi Telah Diperbarui',
            'bukti'        => 'Bukti Perbaikan Dikirim – Mohon Verifikasi',
            'closed'       => 'Temuan #' . $this->temuan->id . ' Telah Ditutup',
            'subarea_pic'  => 'Peringatan Observasi Baru di Sub Area Anda',
            default        => 'Pembaruan Status Temuan',
        };

        // Sertakan nama penerima & ID temuan agar tidak dianggap email massal
        $namePrefix = $this->recipientName ? "Yth. {$this->recipientName} – " : '';

        return new Envelope(
            subject: "[{$systemLabel}] {$namePrefix}{$typeLabel} (ID #{$this->temuan->id})",
        );
    }

    public function headers(): Headers
    {
        // Symfony Mime menambahkan < > secara otomatis — jangan sertakan di sini
        $domain    = parse_url(config('app.url'), PHP_URL_HOST) ?: 'siverabosq.local';
        $messageId = sprintf(
            '%s.%s.%s@%s',
            $this->temuan->id,
            $this->type,
            substr(md5(uniqid('', true)), 0, 8),
            $domain
        );

        return new Headers(
            messageId: $messageId,
            text: [
                'X-Mailer'                  => 'SIVERA-System/2.0',
                'X-Entity-Ref-ID'           => (string) $this->temuan->id,
                'X-Notification-Type'       => $this->system . '.' . $this->type,
                'X-Auto-Response-Suppress'  => 'OOF, AutoReply',
                'X-Priority'                => '1',
                'Importance'                => 'High',
            ],
        );
    }

    public function content(): Content
    {
        $view = $this->system === 'bosq'
            ? 'emails.bosq-temuan'
            : 'emails.sivera-temuan';

        return new Content(
            view: $view,
            with: [
                'temuan'        => $this->temuan,
                'type'          => $this->type,
                'system'        => $this->system,
                'recipientName' => $this->recipientName,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}