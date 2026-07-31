<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Ensure Karyawan with NIK 0000000 exists
$karyawan = Karyawan::firstOrCreate(
    ['nik' => '0000000'],
    [
        'nama' => 'Super Administrator',
        'departemen_id' => 1,
        'status_aktif' => true,
        'is_anggota_divisi_manajemen' => true,
    ]
);

// Create or update Super Admin User
$user = User::updateOrCreate(
    ['nik' => '0000000'],
    [
        'name' => 'Super Administrator',
        'role' => 'superadmin',
        'no_whatsapp' => '6281234567899',
        'password' => Hash::make('0000000'), // default password adalah NIK: 0000000
    ]
);

echo "=== SUPER ADMIN USER CREATED / UPDATED SUCCESSFULLY ===" . PHP_EOL;
echo "NIK      : " . $user->nik . PHP_EOL;
echo "Nama     : " . $user->name . PHP_EOL;
echo "Role     : " . $user->role . PHP_EOL;
echo "Password : " . $user->nik . " (sama dengan NIK)" . PHP_EOL;
