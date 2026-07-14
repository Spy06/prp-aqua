<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppDummy implements ShouldQueue
{
    use Queueable;

    public $to;
    public $messageBody;

    public function __construct($to = null, $messageBody = null)
    {
        // Pastikan format nomor diawali dengan country code (misal: 62812...)
        // Hapus tanda + atau angka 0 di awal jika ada
        $this->to = ltrim($to, '+0');
        $this->messageBody = $messageBody;
    }

    public function handle(): void
    {
        $instanceId = env('GREENAPI_INSTANCE_ID');
        $token      = env('GREENAPI_TOKEN');
        $to         = $this->to ?? '6281200000000'; // Nomor default untuk testing
        $body       = $this->messageBody ?? "Hello dari PRP Aqua! Ini adalah pesan test Green API.";

        if (!$instanceId || !$token || !$to) {
            Log::warning('Kredensial Green API belum lengkap di .env, job SendWhatsApp dilewati.');
            return;
        }

        try {
            // URL endpoint dari Green API (SendMessage)
            $url = "https://api.green-api.com/waInstance{$instanceId}/sendMessage/{$token}";

            // Kirim HTTP POST Request
            $response = Http::post($url, [
                // Nomor tujuan WA harus diakhiri dengan @c.us untuk chat personal
                'chatId'  => $to . '@c.us',
                'message' => $body
            ]);

            if ($response->successful()) {
                Log::info("Pesan berhasil dikirim via Green API! Response: " . $response->body());
            } else {
                Log::error("Gagal mengirim via Green API. HTTP Status: " . $response->status() . " Body: " . $response->body());
            }

        } catch (\Exception $e) {
            Log::error("Error koneksi saat mengirim via Green API: " . $e->getMessage());
        }
    }
}
