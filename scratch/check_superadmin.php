<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Karyawan;
use App\Models\User;

echo "=== CHECKING USERS ===\n";
$superUsers = User::where('role', 'superadmin')
    ->orWhere('name', 'like', '%super administrator%')
    ->orWhere('nik', '99999')
    ->get();

foreach ($superUsers as $u) {
    echo "USER ID: {$u->id} | NIK: {$u->nik} | Name: {$u->name} | Role: {$u->role}\n";
}

echo "\n=== CHECKING KARYAWAN ===\n";
$superKaryawans = Karyawan::where('nama', 'like', '%super administrator%')
    ->orWhere('nik', '99999')
    ->get();

foreach ($superKaryawans as $k) {
    echo "KARYAWAN NIK: {$k->nik} | Nama: {$k->nama} | DeptID: {$k->departemen_id}\n";
}
