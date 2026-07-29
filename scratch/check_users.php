<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Karyawan;
use App\Models\Departemen;

echo "=== USER LIST ===" . PHP_EOL;
foreach (User::all() as $u) {
    echo "ID: {$u->id} | NIK: {$u->nik} | Nama: {$u->name} | Role: {$u->role}" . PHP_EOL;
}

echo PHP_EOL . "=== DEPARTEMEN LIST ===" . PHP_EOL;
foreach (Departemen::all() as $d) {
    echo "ID: {$d->id} | Nama: {$d->nama_departemen}" . PHP_EOL;
}
