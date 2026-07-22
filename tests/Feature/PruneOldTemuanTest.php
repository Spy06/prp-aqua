<?php

use App\Models\Departemen;
use App\Models\KlausulPrp;
use App\Models\Temuan;
use App\Models\TindakLanjut;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

test('prune temuan command deletes records and files older than X years', function () {
    Storage::fake('public');

    $pelapor = User::factory()->create();
    $pic = User::factory()->create(['role' => 'karyawan']);
    $dept = Departemen::create(['nama_departemen' => 'QC']);
    $klausul = KlausulPrp::create(['kode_klausul' => 'PRP-01', 'nama_klausul' => 'Kebersihan']);

    // File dummy di storage
    Storage::disk('public')->put('temuan/old_photo.jpg', 'fake content');
    Storage::disk('public')->put('bukti/old_bukti.jpg', 'fake content');

    Storage::disk('public')->put('temuan/new_photo.jpg', 'fake content');

    // Temuan LAMA (3 tahun lalu)
    $oldTemuan = Temuan::create([
        'tanggal_temuan' => now()->subYears(3)->format('Y-m-d'),
        'pelapor_id' => $pelapor->id,
        'pic_id' => $pic->id,
        'klausul_id' => $klausul->id,
        'departemen_id' => $dept->id,
        'sub_area' => 'Line 1',
        'foto_temuan_path' => 'temuan/old_photo.jpg',
        'deskripsi' => 'Temuan lama 3 tahun lalu',
        'status' => 'open',
        'created_at' => now()->subYears(3),
    ]);

    TindakLanjut::create([
        'temuan_id' => $oldTemuan->id,
        'action' => 'Tindak lanjut lama',
        'foto_bukti_path' => 'bukti/old_bukti.jpg',
        'status' => 'open',
    ]);

    // Temuan BARU (6 bulan lalu)
    $newTemuan = Temuan::create([
        'tanggal_temuan' => now()->subMonths(6)->format('Y-m-d'),
        'pelapor_id' => $pelapor->id,
        'pic_id' => $pic->id,
        'klausul_id' => $klausul->id,
        'departemen_id' => $dept->id,
        'sub_area' => 'Line 2',
        'foto_temuan_path' => 'temuan/new_photo.jpg',
        'deskripsi' => 'Temuan baru 6 bulan lalu',
        'status' => 'open',
        'created_at' => now()->subMonths(6),
    ]);

    // Jalankan command prune (default 2 tahun)
    Artisan::call('prp:prune-temuan', ['--years' => 2]);

    // Assert: temuan lama & file fisiknya terhapus
    $this->assertDatabaseMissing('temuan', ['id' => $oldTemuan->id]);
    $this->assertDatabaseMissing('tindak_lanjut', ['temuan_id' => $oldTemuan->id]);
    Storage::disk('public')->assertMissing('temuan/old_photo.jpg');
    Storage::disk('public')->assertMissing('bukti/old_bukti.jpg');

    // Assert: temuan baru & file fisiknya tetap ada
    $this->assertDatabaseHas('temuan', ['id' => $newTemuan->id]);
    Storage::disk('public')->assertExists('temuan/new_photo.jpg');
});
