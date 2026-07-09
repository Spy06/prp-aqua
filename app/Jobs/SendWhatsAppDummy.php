<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendWhatsAppDummy implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $sid    = env('TWILIO_SID');
        $token  = env('TWILIO_AUTH_TOKEN');
        $from   = env('TWILIO_WHATSAPP_FROM');
        // Dummy target number
        $to     = env('TWILIO_TEST_NUMBER');

        if (!$sid || !$token || !$from || !$to) {
            \Illuminate\Support\Facades\Log::warning('Kredensial Twilio belum lengkap di .env, job SendWhatsAppDummy dilewati.');
            return;
        }

        try {
            $twilio = new \Twilio\Rest\Client($sid, $token);

            $message = $twilio->messages
                ->create("whatsapp:" . $to, // to
                    [
                        "from" => "whatsapp:" . $from,
                        "body" => "Hello dari PRP Aqua! Ini adalah pesan test dari queue Laravel."
                    ]
                );

            \Illuminate\Support\Facades\Log::info("Pesan dummy berhasil dikirim! SID: " . $message->sid);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Gagal mengirim pesan dummy: " . $e->getMessage());
            // Optional: jika butuh retry, bisa di-throw kembali
            // throw $e;
        }
    }
}
