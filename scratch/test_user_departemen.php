<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$users = User::with('karyawan.departemen')->take(5)->get();

foreach ($users as $u) {
    $dept = $u->karyawan->departemen->nama_departemen ?? 'TANPA DEPARTEMEN';
    echo "User: {$u->name} | NIK: {$u->nik} | Dept: {$dept}" . PHP_EOL;
}
