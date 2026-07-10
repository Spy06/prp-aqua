<?php

use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\KlausulPrp;
use App\Models\Temuan;
use App\Models\TindakLanjut;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

/**
 * Helper: buat user karyawan dummy beserta karyawan-nya.
 */
function buatKaryawan(string $nik, string $nama, ?string $wa = null): User
{
    $dept = Departemen::first() ?? Departemen::create(['nama_departemen' => 'Test Dept']);

    Karyawan::firstOrCreate(
        ['nik' => $nik],
        ['nama' => $nama, 'departemen_id' => $dept->id, 'status_aktif' => true]
    );

    return User::firstOrCreate(
        ['nik' => $nik],
        [
            'name'        => $nama,
            'nik'         => $nik,
            'role'        => 'karyawan',
            'no_whatsapp' => $wa ?? '6281200000099',
            'password'    => Hash::make('password'),
        ]
    );
}

/**
 * Helper: buat user QA dummy.
 */
function buatQa(string $nik = 'QA9999', string $wa = '6281200000001'): User
{
    $dept = Departemen::first() ?? Departemen::create(['nama_departemen' => 'QA Dept']);

    Karyawan::firstOrCreate(
        ['nik' => $nik],
        ['nama' => 'QA Test', 'departemen_id' => $dept->id, 'status_aktif' => true]
    );

    return User::firstOrCreate(
        ['nik' => $nik],
        [
            'name'        => 'QA Test',
            'nik'         => $nik,
            'role'        => 'qa',
            'no_whatsapp' => $wa,
            'password'    => Hash::make('qapassword'),
        ]
    );
}

/**
 * Helper: buat temuan + tindak_lanjut awal.
 */
function buatTemuan(User $pelapor, User $pic, string $status = 'open'): Temuan
{
    $dept = Departemen::first() ?? Departemen::create(['nama_departemen' => 'Test Dept']);

    $temuan = Temuan::create([
        'tanggal_temuan'   => now()->toDateString(),
        'pelapor_id'       => $pelapor->id,
        'pic_id'           => $pic->id,
        'departemen_id'    => $dept->id,
        'sub_area'         => 'Area Test',
        'foto_temuan_path' => 'temuan/test.jpg',
        'deskripsi'        => 'Deskripsi temuan test',
        'status'           => $status,
    ]);

    TindakLanjut::create([
        'temuan_id' => $temuan->id,
        'status'    => $status,
        'acc_qa'    => false,
    ]);

    return $temuan;
}

// ===========================================================================
// TEST GROUP 1: Otorisasi Level-Objek (Policy) — FR-15
// ===========================================================================

describe('Policy TemuanPolicy: akses /temuan/{id}', function () {

    /**
     * Pelapor pemilik temuan BISA mengakses detail temuan miliknya.
     */
    it('mengizinkan pelapor pemilik temuan membuka detail', function () {
        $pelapor = buatKaryawan('T001', 'Pelapor Test');
        $pic     = buatKaryawan('T002', 'PIC Test');
        $temuan  = buatTemuan($pelapor, $pic);

        $this->actingAs($pelapor)
             ->get(route('temuan.detail', $temuan->id))
             ->assertOk();
    });

    /**
     * PIC dari temuan BISA mengakses detail temuan terkait.
     */
    it('mengizinkan PIC dari temuan membuka detail', function () {
        $pelapor = buatKaryawan('T003', 'Pelapor2 Test');
        $pic     = buatKaryawan('T004', 'PIC2 Test');
        $temuan  = buatTemuan($pelapor, $pic);

        $this->actingAs($pic)
             ->get(route('temuan.detail', $temuan->id))
             ->assertOk();
    });

    /**
     * QA BISA mengakses detail temuan mana pun.
     */
    it('mengizinkan QA membuka detail temuan siapapun', function () {
        $pelapor = buatKaryawan('T005', 'Pelapor3 Test');
        $pic     = buatKaryawan('T006', 'PIC3 Test');
        $temuan  = buatTemuan($pelapor, $pic);
        $qa      = buatQa();

        $this->actingAs($qa)
             ->get(route('temuan.detail', $temuan->id))
             ->assertOk();
    });

    /**
     * Karyawan LAIN (bukan pelapor/pic dari temuan ini) HARUS mendapat 403.
     * Ini adalah tes wajib sesuai requirement Hari 4.
     */
    it('menolak (403) karyawan lain yang bukan pelapor maupun PIC dari temuan ini', function () {
        $pelapor         = buatKaryawan('T007', 'Pelapor4 Test');
        $pic             = buatKaryawan('T008', 'PIC4 Test');
        $karyawanLain    = buatKaryawan('T009', 'Karyawan Lain');
        $temuan          = buatTemuan($pelapor, $pic);

        $this->actingAs($karyawanLain)
             ->get(route('temuan.detail', $temuan->id))
             ->assertForbidden(); // HTTP 403
    });

    /**
     * User yang belum login harus diredirect ke login (bukan 403 atau 200).
     */
    it('meredirect user belum login ke halaman login (intended URL disimpan)', function () {
        $pelapor = buatKaryawan('T010', 'Pelapor5 Test');
        $pic     = buatKaryawan('T011', 'PIC5 Test');
        $temuan  = buatTemuan($pelapor, $pic);

        $this->get(route('temuan.detail', $temuan->id))
             ->assertRedirect(route('login'));
    });

})->group('policy');

// ===========================================================================
// TEST GROUP 2: Transisi Status PIC (TindakLanjutPIC)
// ===========================================================================

describe('TindakLanjutPIC: transisi status', function () {

    /**
     * PIC tidak bisa set status ke closed_acc langsung.
     */
    it('menolak PIC mengubah status ke closed_acc', function () {
        $pelapor = buatKaryawan('S001', 'Pelapor Status Test');
        $pic     = buatKaryawan('S002', 'PIC Status Test');
        $temuan  = buatTemuan($pelapor, $pic, 'in_progress');

        $tl = TindakLanjut::where('temuan_id', $temuan->id)->first();

        // Langsung panggil metode Livewire component
        $component = Livewire\Livewire::actingAs($pic)
            ->test(App\Livewire\TindakLanjutPIC::class, ['temuanId' => $temuan->id])
            ->call('ubahStatus', 'closed_acc');

        // Status di DB tidak berubah ke closed_acc
        expect(TindakLanjut::find($tl->id)->status)->toBe('in_progress');
    });

    /**
     * PIC tidak bisa loncat dari open ke closed_pending_qa.
     */
    it('menolak PIC loncat status dari open langsung ke closed_pending_qa', function () {
        $pelapor = buatKaryawan('S003', 'Pelapor Loncat');
        $pic     = buatKaryawan('S004', 'PIC Loncat');
        $temuan  = buatTemuan($pelapor, $pic, 'open');

        Livewire\Livewire::actingAs($pic)
            ->test(App\Livewire\TindakLanjutPIC::class, ['temuanId' => $temuan->id])
            ->call('ubahStatus', 'closed_pending_qa');

        // Status tidak boleh berubah dari open
        expect(TindakLanjut::where('temuan_id', $temuan->id)->first()->status)->toBe('open');
    });

    /**
     * PIC bisa mengubah status dari open ke in_progress.
     */
    it('mengizinkan PIC mengubah status dari open ke in_progress', function () {
        $pelapor = buatKaryawan('S005', 'Pelapor OK');
        $pic     = buatKaryawan('S006', 'PIC OK');
        $temuan  = buatTemuan($pelapor, $pic, 'open');

        Livewire\Livewire::actingAs($pic)
            ->test(App\Livewire\TindakLanjutPIC::class, ['temuanId' => $temuan->id])
            ->call('ubahStatus', 'in_progress');

        expect(TindakLanjut::where('temuan_id', $temuan->id)->first()->status)->toBe('in_progress');
    });

    /**
     * PIC tidak bisa set closed_pending_qa jika foto bukti belum ada.
     */
    it('menolak closed_pending_qa jika foto bukti belum diupload', function () {
        $pelapor = buatKaryawan('S007', 'Pelapor FotoBelum');
        $pic     = buatKaryawan('S008', 'PIC FotoBelum');
        $temuan  = buatTemuan($pelapor, $pic, 'in_progress');

        // Pastikan klausul ada
        $klausul = KlausulPrp::first() ?? KlausulPrp::create(['kode_klausul' => 'K1', 'nama_klausul' => 'Test']);
        TindakLanjut::where('temuan_id', $temuan->id)->update([
            'klausul_id'     => $klausul->id,
            'action'         => 'Tindakan test',
            'due_date'       => now()->addDays(7)->toDateString(),
            'foto_bukti_path' => null, // Belum ada foto
        ]);

        Livewire\Livewire::actingAs($pic)
            ->test(App\Livewire\TindakLanjutPIC::class, ['temuanId' => $temuan->id])
            ->call('ubahStatus', 'closed_pending_qa');

        // Status tidak boleh berubah ke closed_pending_qa
        expect(TindakLanjut::where('temuan_id', $temuan->id)->first()->status)->toBe('in_progress');
    });

    /**
     * PIC BISA set closed_pending_qa jika semua syarat terpenuhi (termasuk foto bukti).
     */
    it('mengizinkan closed_pending_qa jika detail dan foto bukti sudah ada', function () {
        Queue::fake(); // Prevent actual WA job dispatch

        $pelapor = buatKaryawan('S009', 'Pelapor FotoAda');
        $pic     = buatKaryawan('S010', 'PIC FotoAda');
        $qa      = buatQa('QA001', '6281200000001');
        $temuan  = buatTemuan($pelapor, $pic, 'in_progress');

        $klausul = KlausulPrp::first() ?? KlausulPrp::create(['kode_klausul' => 'K2', 'nama_klausul' => 'Test2']);
        TindakLanjut::where('temuan_id', $temuan->id)->update([
            'klausul_id'      => $klausul->id,
            'action'          => 'Tindakan sudah dilakukan',
            'due_date'        => now()->addDays(7)->toDateString(),
            'foto_bukti_path' => 'bukti/test-foto.jpg', // Foto sudah ada
        ]);

        Livewire\Livewire::actingAs($pic)
            ->test(App\Livewire\TindakLanjutPIC::class, ['temuanId' => $temuan->id])
            ->call('ubahStatus', 'closed_pending_qa');

        expect(TindakLanjut::where('temuan_id', $temuan->id)->first()->status)->toBe('closed_pending_qa');

        // QA harus menerima job WA
        Queue::assertPushed(App\Jobs\SendWhatsAppDummy::class);
    });

})->group('status-transition');
