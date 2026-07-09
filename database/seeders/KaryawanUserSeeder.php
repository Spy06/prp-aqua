<?php

namespace Database\Seeders;

use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KaryawanUserSeeder extends Seeder
{
    /**
     * Seed karyawan dummy dan akun users-nya.
     *
     * Kredensial yang dibuat:
     * ┌──────────────┬─────────────────┬────────────┬──────────┬───────────┐
     * │ NIK          │ Nama            │ Departemen │ Role     │ Password  │
     * ├──────────────┼─────────────────┼────────────┼──────────┼───────────┤
     * │ 2024001      │ Budi Santoso    │ Produksi   │ karyawan │ password1 │
     * │ 2024002      │ Siti Rahayu     │ QC         │ karyawan │ password2 │
     * │ 2024003      │ Ahmad Fauzi     │ Gudang     │ karyawan │ password3 │
     * │ 2024004      │ Dewi Lestari    │ Maintenance│ karyawan │ password4 │
     * │ 9900001      │ Indah Permata   │ QC         │ qa       │ qapassword│
     * └──────────────┴─────────────────┴────────────┴──────────┴───────────┘
     */
    public function run(): void
    {
        $produksi    = Departemen::where('nama_departemen', 'Produksi')->first();
        $qc          = Departemen::where('nama_departemen', 'Quality Control (QC)')->first();
        $gudang      = Departemen::where('nama_departemen', 'Gudang')->first();
        $maintenance = Departemen::where('nama_departemen', 'Maintenance')->first();

        // ── 1. QA account ──────────────────────────────────────────────────
        $karyawanQa = Karyawan::firstOrCreate(
            ['nik' => '9900001'],
            [
                'nama'          => 'Indah Permata',
                'departemen_id' => $qc->id,
                'status_aktif'  => true,
            ]
        );

        User::firstOrCreate(
            ['nik' => '9900001'],
            [
                'name'         => $karyawanQa->nama,
                'nik'          => '9900001',
                'role'         => 'qa',
                'no_whatsapp'  => '6281200000001',
                'password'     => Hash::make('qapassword'),
            ]
        );

        // ── 2. Karyawan accounts ───────────────────────────────────────────
        $karyawans = [
            [
                'nik'           => '2024001',
                'nama'          => 'Budi Santoso',
                'departemen_id' => $produksi->id,
                'role'          => 'karyawan',
                'no_whatsapp'   => '6281200000002',
                'password'      => 'password1',
            ],
            [
                'nik'           => '2024002',
                'nama'          => 'Siti Rahayu',
                'departemen_id' => $qc->id,
                'role'          => 'karyawan',
                'no_whatsapp'   => '6281200000003',
                'password'      => 'password2',
            ],
            [
                'nik'           => '2024003',
                'nama'          => 'Ahmad Fauzi',
                'departemen_id' => $gudang->id,
                'role'          => 'karyawan',
                'no_whatsapp'   => '6281200000004',
                'password'      => 'password3',
            ],
            [
                'nik'           => '2024004',
                'nama'          => 'Dewi Lestari',
                'departemen_id' => $maintenance->id,
                'role'          => 'karyawan',
                'no_whatsapp'   => '6281200000005',
                'password'      => 'password4',
            ],
        ];

        foreach ($karyawans as $data) {
            Karyawan::firstOrCreate(
                ['nik' => $data['nik']],
                [
                    'nama'          => $data['nama'],
                    'departemen_id' => $data['departemen_id'],
                    'status_aktif'  => true,
                ]
            );

            User::firstOrCreate(
                ['nik' => $data['nik']],
                [
                    'name'        => $data['nama'],
                    'nik'         => $data['nik'],
                    'role'        => $data['role'],
                    'no_whatsapp' => $data['no_whatsapp'],
                    'password'    => Hash::make($data['password']),
                ]
            );
        }

        // ── 3. Karyawan tanpa akun (contoh — belum punya akun sistem) ──────
        $karyawanTanpaAkun = [
            [
                'nik'           => '2024005',
                'nama'          => 'Roni Wibowo',
                'departemen_id' => $produksi->id,
            ],
            [
                'nik'           => '2024006',
                'nama'          => 'Maya Anggraeni',
                'departemen_id' => $gudang->id,
            ],
        ];

        foreach ($karyawanTanpaAkun as $data) {
            Karyawan::firstOrCreate(
                ['nik' => $data['nik']],
                [
                    'nama'          => $data['nama'],
                    'departemen_id' => $data['departemen_id'],
                    'status_aktif'  => true,
                ]
            );
        }

        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('  AKUN DUMMY YANG TERSEDIA — SISTEM VERIFIKASI PRP    ');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('  NIK: 9900001   | Password: qapassword   | Role: QA  ');
        $this->command->info('  NIK: 2024001   | Password: password1    | Role: Karyawan');
        $this->command->info('  NIK: 2024002   | Password: password2    | Role: Karyawan');
        $this->command->info('  NIK: 2024003   | Password: password3    | Role: Karyawan');
        $this->command->info('  NIK: 2024004   | Password: password4    | Role: Karyawan');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('');
    }
}
