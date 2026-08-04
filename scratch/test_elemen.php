<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('role', 'qa')->first();
if ($user) {
    Auth::login($user);
}

try {
    $comp = new App\Livewire\BosQ\MasterElemenQfs();
    $view = $comp->render();
    echo "SUCCESS: Component rendered cleanly!\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
