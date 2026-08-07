<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('role', 'superadmin')->first();

if (!$user) {
    echo "No superadmin found!\n";
    exit;
}

// Set password to 'admin2026'
$user->password = Illuminate\Support\Facades\Hash::make('admin2026');
$user->save();

echo "SUCCESSFULLY SET SUPER ADMIN CREDENTIALS:\n";
echo "========================================\n";
echo "NIK / Username : Super Administrator\n";
echo "Password       : admin2026\n";
echo "PIN IT Admin   : 2026\n";
echo "========================================\n";
