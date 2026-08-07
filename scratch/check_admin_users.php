<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$superadmins = App\Models\User::where('role', 'superadmin')->get();

echo "Super Admin Users Count: " . $superadmins->count() . "\n";
foreach ($superadmins as $u) {
    echo "ID: {$u->id} | NIK: {$u->nik} | Name: {$u->name} | Email: {$u->email} | Role: {$u->role}\n";
}
