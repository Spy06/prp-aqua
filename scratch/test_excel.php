<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('role', 'qa')->first();
if ($user) {
    Auth::login($user);
}

$request = new \Illuminate\Http\Request(['date' => '2026-08-01']);
$controller = new App\Http\Controllers\BosqExportController();

try {
    $response = $controller->rekapExcel($request);
    echo "SUCCESS: Status Code " . $response->getStatusCode() . "\n";
    echo "Content Length: " . strlen($response->getContent()) . " bytes\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
