<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Karyawan;
use App\Models\User;

// 1. Hapus Karyawan Super Administrator
$deletedCount = Karyawan::where('nama', 'like', '%super administrator%')
    ->orWhere('nik', '0000000')
    ->orWhere('nik', '99999')
    ->delete();

echo "Deleted {$deletedCount} karyawan record(s) for Super Administrator.\n";

// 2. Putuskan relasi NIK pada user Super Admin agar murni menjadi akun IT rahasia
$superUsers = User::where('role', 'superadmin')->get();
foreach ($superUsers as $u) {
    $u->nik = null;
    $u->save();
    echo "Updated User ID {$u->id} (role: superadmin) - NIK cleared.\n";
}

echo "Cleanup completed successfully!\n";
