<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$temuan = App\Models\Temuan::latest()->first();
$testEmail = "fahriirfandi564@gmail.com";

try {
    echo "Sending email to {$testEmail}..." . PHP_EOL;
    Illuminate\Support\Facades\Mail::send('emails.sivera-temuan', [
        'temuan' => $temuan,
        'type'   => 'baru',
    ], function ($message) use ($testEmail, $temuan) {
        $message->to($testEmail)->subject("[SIVERA] Test Direct Send");
    });
    echo "SUCCESS!" . PHP_EOL;
} catch (\Throwable $e) {
    echo "ERROR EXCEPTION: " . $e->getMessage() . PHP_EOL;
    echo "TRACE: " . $e->getTraceAsString() . PHP_EOL;
}
