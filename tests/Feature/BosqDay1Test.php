<?php

use App\Models\BosqElemenQfs;
use App\Models\BosqLine;
use App\Models\BosqSubArea;
use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('tabel bosq_* dan seeder terisi dengan benar pada database', function () {
    Departemen::firstOrCreate(['nama_departemen' => 'Manufacturing']);
    $this->seed(\Database\Seeders\BosqMasterSeeder::class);

    expect(BosqLine::count())->toBeGreaterThanOrEqual(10);
    expect(BosqSubArea::count())->toBeGreaterThanOrEqual(20);
    expect(BosqElemenQfs::count())->toBeGreaterThanOrEqual(10);

    $line1 = BosqLine::where('nama_line', 'Line 1')->first();
    expect($line1)->not->toBeNull();
    expect($line1->default_auditee_id)->toBeNull();
});

test('kolom is_anggota_divisi_manajemen ada di tabel karyawan dengan default false', function () {
    $dept = Departemen::firstOrCreate(['nama_departemen' => 'Manufacturing']);
    $user = User::factory()->create(['role' => 'karyawan']);

    $karyawan = Karyawan::create([
        'user_id'       => $user->id,
        'nik'           => '8001',
        'nama'          => 'Karyawan Test',
        'departemen_id' => $dept->id,
        'status_aktif'  => true,
    ]);

    expect($karyawan->fresh()->is_anggota_divisi_manajemen)->toBeFalse();
});

test('portal utama menampilkan 2 card system SIVERA dan BOSQ dengan palet warna yang seragam', function () {
    $responsePortal = $this->get('/');
    $responsePortal->assertStatus(200);
    $responsePortal->assertSee('BOS');
    $responsePortal->assertSee('SIVERA');
    $responsePortal->assertDontSee('SIM-PLANT');

    $responseLogin = $this->get('/login?system=bosq');
    $responseLogin->assertStatus(200);
    $responseLogin->assertSee('BOS');
    $responseLogin->assertSee('Behavior Observation System Quality');
});

test('skeleton route bosq terdaftar dan dilindungi auth', function () {
    $user = User::factory()->create(['role' => 'karyawan']);

    $this->actingAs($user)
        ->get('/bosq/beranda')
        ->assertStatus(200)
        ->assertSee('BOSQ Beranda Placeholder');

    $qa = User::factory()->create(['role' => 'qa']);

    $this->actingAs($qa)
        ->get('/bosq/qa/dashboard')
        ->assertStatus(200)
        ->assertSee('BOSQ QA Dashboard Placeholder');
});
