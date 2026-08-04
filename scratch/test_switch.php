<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('role', 'qa')->first();
if ($user) {
    Auth::login($user);
}

session(['login_system' => 'sivera']);
echo "Initial session login_system: " . session('login_system') . "\n";

// Test access to BOSQ route with system_guard:bosq
$middleware = new App\Http\Middleware\SystemGuardMiddleware();
$request = Illuminate\Http\Request::create('/bosq/qa/dashboard', 'GET');
$request->setUserResolver(fn() => $user);

$response = $middleware->handle($request, fn($req) => response('OK'), 'bosq');
echo "Middleware response status: " . $response->getStatusCode() . "\n";
echo "After accessing BOS'Q, session login_system: " . session('login_system') . "\n";

// Test access to SIVERA route with system_guard:sivera
$request2 = Illuminate\Http\Request::create('/qa/dashboard', 'GET');
$request2->setUserResolver(fn() => $user);

$response2 = $middleware->handle($request2, fn($req) => response('OK'), 'sivera');
echo "Middleware response status: " . $response2->getStatusCode() . "\n";
echo "After accessing SIVERA, session login_system: " . session('login_system') . "\n";
