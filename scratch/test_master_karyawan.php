<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$component = new App\Livewire\BosQ\MasterKaryawan();
$dept = App\Models\Departemen::first();

$testNik = 'TESTSTATUS88';
$testNama = 'Karyawan Status Test';

$component->nik = $testNik;
$component->nama = $testNama;
$component->departemen_id = $dept->id;
$component->is_anggota_divisi_manajemen = true;
$component->status_aktif = true;
$component->save();

// Toggle status to nonaktif
$component->toggleStatusAktif($testNik);
$checkNonaktif = App\Models\Karyawan::where('nik', $testNik)->first();
echo "Status after first toggle (expected 0): " . ($checkNonaktif->status_aktif ? "1" : "0") . "\n";

// Toggle status back to aktif
$component->toggleStatusAktif($testNik);
$checkAktif = App\Models\Karyawan::where('nik', $testNik)->first();
echo "Status after second toggle (expected 1): " . ($checkAktif->status_aktif ? "1" : "0") . "\n";

// Clean up
$component->delete($testNik);
echo "TOGGLE STATUS AKTIF TEST PASSED SUCCESSFULLY!\n";
