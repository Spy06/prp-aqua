<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendWhatsAppDummy implements ShouldQueue
{
    use Queueable;

    public $to;
    public $messageBody;

    /**
     * Create a new job instance.
     */
    public function __construct($to = null, $messageBody = null)
    {
        $this->to = $to;
        $this->messageBody = $messageBody;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $sid    = env('TWILIO_SID');
        $token  = env('TWILIO_AUTH_TOKEN');
        $from   = env('TWILIO_WHATSAPP_FROM');
        $to     = $this->to ?? env('TWILIO_TEST_NUMBER');
        $body   = $this->messageBody ?? "Hello dari PRP Aqua! Ini adalah pesan test dari queue Laravel.";

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
                        "body" => $body
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
