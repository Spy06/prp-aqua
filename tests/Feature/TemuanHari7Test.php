<?php

use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\KlausulPrp;
use App\Models\Temuan;
use App\Models\TindakLanjut;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendWhatsAppDummy;
use Livewire\Livewire;

uses(RefreshDatabase::class);

/**
 * Helper untuk karyawan & QA.
 */
function testBuatKaryawan(string $nik, string $nama, bool $aktif = true): User
{
    $dept = Departemen::first() ?? Departemen::create(['nama_departemen' => 'Test Dept']);
    Karyawan::create([
        'nik' => $nik,
        'nama' => $nama,
        'departemen_id' => $dept->id,
        'status_aktif' => $aktif,
    ]);

    return User::create([
        'name' => $nama,
        'nik' => $nik,
        'role' => 'karyawan',
        'no_whatsapp' => '6281200000000',
        'password' => Hash::make('password'),
    ]);
}

function testBuatQa(string $nik = 'QA8888'): User
{
    $dept = Departemen::first() ?? Departemen::create(['nama_departemen' => 'QA Dept']);
    Karyawan::create([
        'nik' => $nik,
        'nama' => 'QA Admin',
        'departemen_id' => $dept->id,
        'status_aktif' => true,
    ]);

    return User::create([
        'name' => 'QA Admin',
        'nik' => $nik,
        'role' => 'qa',
        'no_whatsapp' => '6281200000001',
        'password' => Hash::make('qapassword'),
    ]);
}

function testBuatTemuan(User $pelapor, User $pic, string $status = 'open'): Temuan
{
    $dept = Departemen::first() ?? Departemen::create(['nama_departemen' => 'Test Dept']);
    $temuan = Temuan::create([
        'tanggal_temuan' => now()->toDateString(),
        'pelapor_id' => $pelapor->id,
        'pic_id' => $pic->id,
        'departemen_id' => $dept->id,
        'sub_area' => 'Line 1',
        'foto_temuan_path' => 'temuan/test.jpg',
        'deskripsi' => 'Ada kebocoran air',
        'status' => $status,
    ]);

    TindakLanjut::create([
        'temuan_id' => $temuan->id,
        'status' => $status,
        'acc_qa' => false,
    ]);

    return $temuan;
}

// ===========================================================================
// 1. Validasi field wajib di FormTemuan
// ===========================================================================
test('FormTemuan: validasi field wajib', function () {
    $pelapor = testBuatKaryawan('K101', 'Pelapor');

    Livewire::actingAs($pelapor)
        ->test(\App\Livewire\FormTemuan::class)
        ->call('submit')
        ->assertHasErrors([
            'departemen_id' => 'required',
            'sub_area' => 'required',
            'klausul_id' => 'required',
            'foto_temuan' => 'required',
            'deskripsi' => 'required',
            'pic_id' => 'required',
        ]);
});

// ===========================================================================
// 2. Validasi field wajib di TindakLanjutPIC
// ===========================================================================
test('TindakLanjutPIC: validasi field wajib simpanDetail dan uploadFoto', function () {
    $pelapor = testBuatKaryawan('K102', 'Pelapor');
    $pic = testBuatKaryawan('K103', 'PIC');
    $temuan = testBuatTemuan($pelapor, $pic);

    // Test simpanDetail
    Livewire::actingAs($pic)
        ->test(\App\Livewire\TindakLanjutPIC::class, ['temuanId' => $temuan->id])
        ->call('simpanDetail')
        ->assertHasErrors([
            'action' => 'required',
            'due_date' => 'required',
        ]);

    // Test uploadFoto
    Livewire::actingAs($pic)
        ->test(\App\Livewire\TindakLanjutPIC::class, ['temuanId' => $temuan->id])
        ->call('uploadFoto')
        ->assertHasErrors([
            'foto_bukti' => 'required',
        ]);
});

// ===========================================================================
// 3. Transisi status tidak boleh loncat & PIC tidak boleh closed_acc
// ===========================================================================
test('TindakLanjutPIC: transisi status dilarang loncat dan PIC tidak boleh set closed_acc', function () {
    $pelapor = testBuatKaryawan('K104', 'Pelapor');
    $pic = testBuatKaryawan('K105', 'PIC');
    $temuan = testBuatTemuan($pelapor, $pic, 'open');

    // Coba set closed_acc langsung sebagai PIC -> ditolak
    Livewire::actingAs($pic)
        ->test(\App\Livewire\TindakLanjutPIC::class, ['temuanId' => $temuan->id])
        ->call('ubahStatus', 'closed_acc');
    
    expect($temuan->fresh()->status)->toBe('open');

    // Coba set closed_pending_qa langsung dari open (loncat in_progress) -> ditolak
    Livewire::actingAs($pic)
        ->test(\App\Livewire\TindakLanjutPIC::class, ['temuanId' => $temuan->id])
        ->call('ubahStatus', 'closed_pending_qa');

    expect($temuan->fresh()->status)->toBe('open');
});

// ===========================================================================
// 4. Policy object-level: Karyawan lain tidak bisa akses temuan orang lain (403)
// ===========================================================================
test('Policy: karyawan lain tidak boleh melihat detail temuan orang lain', function () {
    $pelapor = testBuatKaryawan('K106', 'Pelapor');
    $pic = testBuatKaryawan('K107', 'PIC');
    $karyawanLain = testBuatKaryawan('K108', 'Lain');
    $temuan = testBuatTemuan($pelapor, $pic);

    $this->actingAs($karyawanLain)
        ->get(route('temuan.detail', $temuan->id))
        ->assertForbidden(); // 403
});

// ===========================================================================
// 5. Validasi pembuatan akun: NIK tidak ada / tidak aktif ditolak
// ===========================================================================
test('MasterAkunUser: NIK tidak terdaftar atau tidak aktif harus ditolak', function () {
    $qa = testBuatQa();
    testBuatKaryawan('K201', 'Karyawan Pasif', false); // Tidak aktif

    // NIK tidak ada
    Livewire::actingAs($qa)
        ->test(\App\Livewire\MasterAkunUser::class)
        ->set('nik_baru', 'NIK999')
        ->set('role_baru', 'karyawan')
        ->set('no_whatsapp_baru', '6281234567890')
        ->set('password_baru', 'password123')
        ->call('buatAkun')
        ->assertHasErrors(['nik_baru']);

    // NIK tidak aktif
    Livewire::actingAs($qa)
        ->test(\App\Livewire\MasterAkunUser::class)
        ->set('nik_baru', 'K201')
        ->set('role_baru', 'karyawan')
        ->set('no_whatsapp_baru', '6281234567890')
        ->set('password_baru', 'password123')
        ->call('buatAkun')
        ->assertHasErrors(['nik_baru']);
});

// ===========================================================================
// 6. Validasi login & registrasi publik
// ===========================================================================
test('Auth: login menggunakan NIK + password, registrasi publik tidak tersedia (404)', function () {
    $karyawan = testBuatKaryawan('K301', 'Karyawan Login');

    // Login sukses
    $response = $this->post(route('login.store'), [
        'nik' => 'K301',
        'password' => 'password',
    ]);
    $response->assertSessionHasNoErrors();
    $this->assertAuthenticatedAs($karyawan);

    // Registrasi publik mengembalikan 404
    $this->get('/register')->assertStatus(404);
    $this->post('/register', [
        'name' => 'John',
        'email' => 'john@abc.com',
        'password' => 'password',
    ])->assertStatus(404);
});

// ===========================================================================
// 7. Lapor temuan dengan saran langsung mengirimkan WA ke QA
// ===========================================================================
test('FormTemuan: lapor temuan dengan saran mengirimkan WA langsung ke QA', function () {
    Queue::fake();

    $pelapor = testBuatKaryawan('K501', 'Pelapor');
    $pic = testBuatKaryawan('K502', 'PIC');
    $qa = testBuatQa('QA9999'); // Buat user QA

    $dept = Departemen::first() ?? Departemen::create(['nama_departemen' => 'Test Dept']);
    $klausul = KlausulPrp::create([
        'kode_klausul' => 'PRP-10.1',
        'nama_klausul' => 'Audit Hygiene',
    ]);

    Storage::fake('public');
    $file = UploadedFile::fake()->image('temuan.jpg');

    Livewire::actingAs($pelapor)
        ->test(\App\Livewire\FormTemuan::class)
        ->set('tanggal_temuan', now()->toDateString())
        ->set('departemen_id', $dept->id)
        ->set('sub_area', 'Jalur A')
        ->set('klausul_id', $klausul->id)
        ->set('pic_id', $pic->id)
        ->set('foto_temuan', $file)
        ->set('deskripsi', 'Kabel terkelupas')
        ->set('saran', 'Harap diganti kabel berinsulasi ganda')
        ->call('submit')
        ->assertHasNoErrors();

    // Pastikan temuan tersimpan dengan saran
    $temuan = Temuan::latest()->first();
    expect($temuan->saran)->toBe('Harap diganti kabel berinsulasi ganda');

    // Pastikan SendWhatsAppDummy di-dispatch ke nomor PIC (6281200000000) dan QA (6281200000001)
    Queue::assertPushed(SendWhatsAppDummy::class, function ($job) {
        return $job->to === '6281200000000'; // Ke PIC
    });

    Queue::assertPushed(SendWhatsAppDummy::class, function ($job) use ($qa) {
        return $job->to === $qa->no_whatsapp; // Ke QA langsung
    });
});
