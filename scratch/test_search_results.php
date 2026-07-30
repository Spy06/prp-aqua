<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== TEST BOSQ AUDITEE SEARCH RESULTS ===" . PHP_EOL;
$auditees = User::with('karyawan.departemen')
    ->where(function ($q) {
        $q->where('name', 'like', '%f%')
          ->orWhere('nik', 'like', '%f%');
    })
    ->orderBy('name')
    ->take(15)
    ->get()
    ->toArray();

foreach ($auditees as $res) {
    $deptName = $res['karyawan']['departemen']['nama_departemen'] ?? '-';
    echo "Name: {$res['name']} | Departemen: {$deptName}" . PHP_EOL;
}

echo PHP_EOL . "=== TEST SIVERA PIC SEARCH RESULTS ===" . PHP_EOL;
$pics = User::with('karyawan.departemen')
    ->where('role', 'karyawan')
    ->where(function($q) {
        $q->where('name', 'like', '%f%')
          ->orWhere('nik', 'like', '%f%');
    })
    ->take(15)
    ->get();

foreach ($pics as $result) {
    $deptName = $result->karyawan->departemen->nama_departemen ?? '-';
    echo "Name: {$result->name} | Departemen: {$deptName}" . PHP_EOL;
}
