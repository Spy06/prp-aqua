<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Karyawan;
use App\Models\BosqTemuan;
use App\Models\BosqTindakLanjut;
use App\Models\BosqSubArea;
use App\Models\BosqElemenQfs;
use Illuminate\Support\Carbon;

// Set is_anggota_divisi_manajemen = true
Karyawan::whereIn('nik', ['2024001', '2024002', '2024004'])->update([
    'is_anggota_divisi_manajemen' => true
]);

echo "Divisi Manajemen members updated!" . PHP_EOL;

// Create dummy test observations for current week
$now = Carbon::now();
$subArea = BosqSubArea::first();
$elemen = BosqElemenQfs::first();

// Farhan Hakim (ID 2) submits 2 observations this week
BosqTemuan::create([
    'tanggal_temuan' => $now->format('Y-m-d'),
    'pelapor_id'     => 2,
    'auditee_id'     => 3,
    'departemen_id'  => 1, // Manufacturing
    'sub_area_id'    => $subArea->id,
    'elemen_qfs_id'  => $elemen->id,
    'temuan_bqa'     => 'Karyawan menggunakan APD lengkap di area produksi.',
    'tingkat_resiko' => 'minor_quality_risk',
    'dampak_temuan'  => 'positif',
    'status'         => 'closed',
]);

BosqTemuan::create([
    'tanggal_temuan' => $now->format('Y-m-d'),
    'pelapor_id'     => 2,
    'auditee_id'     => 4,
    'departemen_id'  => 1, // Manufacturing
    'sub_area_id'    => $subArea->id,
    'elemen_qfs_id'  => $elemen->id,
    'temuan_bqa'     => 'Karyawan merapikan peralatan kerja setelah jam shift.',
    'tingkat_resiko' => 'minor_quality_risk',
    'dampak_temuan'  => 'positif',
    'status'         => 'closed',
]);

// Fahri Dewantara (ID 3) submits 1 observation this week
BosqTemuan::create([
    'tanggal_temuan' => $now->format('Y-m-d'),
    'pelapor_id'     => 3,
    'auditee_id'     => 5,
    'departemen_id'  => 1, // Manufacturing
    'sub_area_id'    => $subArea->id,
    'elemen_qfs_id'  => $elemen->id,
    'temuan_bqa'     => 'Penumpukan kardus afval di jalur pejalan kaki.',
    'tingkat_resiko' => 'major_quality_risk',
    'dampak_temuan'  => 'negatif',
    'status'         => 'open',
]);

echo "Dummy observations for testing RekapKepatuhan created successfully!" . PHP_EOL;
