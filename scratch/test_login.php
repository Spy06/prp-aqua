<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('role', 'superadmin')->first();

if ($user) {
    $user->password = Illuminate\Support\Facades\Hash::make('admin2026');
    $user->save();
}

$request = Illuminate\Http\Request::create('/admin-SiveraBosQ', 'POST', [
    'nik' => 'Super Administrator',
    'password' => 'admin2026',
    'secret_pin' => '2026',
]);

$controller = new App\Http\Controllers\ItPortalAuthController();

try {
    $response = $controller->login($request);
    echo "LOGIN SUCCESSFUL! Redirecting to: " . $response->getTargetUrl() . "\n";
} catch (\Exception $e) {
    echo "LOGIN FAILED: " . $e->getMessage() . "\n";
}
