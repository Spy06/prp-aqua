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
     * │ 2024001      │ Farhan Hakim    │ Produksi   │ karyawan │ password1 │
     * │ 2024002      │ Fahri Irfandi   │ QC         │ karyawan │ password2 │
     * │ 2024003      │ Daffa Salman    │ Gudang     │ karyawan │ password3 │
     * │ 2024004      │ Dewi Lestari    │ Maintenance│ karyawan │ password4 │
     * │ 9900001      │ Lia Atikah      │ QC         │ qa       │ qapassword│
     * └──────────────┴─────────────────┴────────────┴──────────┴───────────┘
     */
    public function run(): void
    {
        $produksi = Departemen::where('nama_departemen', 'Manufacturing')->first() ?? Departemen::first();
        $qc = Departemen::where('nama_departemen', 'Quality Assurance')->first() ?? Departemen::first();
        $gudang = Departemen::where('nama_departemen', 'Logistics')->first() ?? Departemen::first();
        $maintenance = Departemen::where('nama_departemen', 'Engineering')->first() ?? Departemen::first();

        $karyawanQa = Karyawan::firstOrCreate(
            ['nik' => '9900001'],
            [
                'nama' => 'Lia Atikah',
                'departemen_id' => $qc->id,
                'status_aktif' => true,
            ]
        );

        User::firstOrCreate(
            ['nik' => '9900001'],
            [
                'name' => $karyawanQa->nama,
                'nik' => '9900001',
                'role' => 'qa',
                'no_whatsapp' => '628562001150',
                'password' => Hash::make('9900001'),
            ]
        );

        $karyawans = [
            [
                'nik' => '2024001',
                'nama' => 'Farhan Hakim',
                'departemen_id' => $produksi->id,
                'role' => 'karyawan',
                'no_whatsapp' => '6281326532314',
            ],
            [
                'nik' => '2024002',
                'nama' => 'Mhd Fahri Irfandi Dewantara',
                'departemen_id' => $qc->id,
                'role' => 'karyawan',
                'no_whatsapp' => '62895618964044',
            ],
            [
                'nik' => '2024003',
                'nama' => 'Daffa Salman Fauzan Santoso',
                'departemen_id' => $gudang->id,
                'role' => 'karyawan',
                'no_whatsapp' => '6281270783144',
            ],
            [
                'nik' => '2024004',
                'nama' => 'Dewi Lestari',
                'departemen_id' => $maintenance->id,
                'role' => 'karyawan',
                'no_whatsapp' => '6281200000005',
            ],
        ];

        foreach ($karyawans as $data) {
            Karyawan::firstOrCreate(
                ['nik' => $data['nik']],
                [
                    'nama' => $data['nama'],
                    'departemen_id' => $data['departemen_id'],
                    'status_aktif' => true,
                ]
            );

            User::firstOrCreate(
                ['nik' => $data['nik']],
                [
                    'name' => $data['nama'],
                    'nik' => $data['nik'],
                    'role' => $data['role'],
                    'no_whatsapp' => $data['no_whatsapp'],
                    'password' => Hash::make($data['nik']),
                ]
            );
        }

        $karyawanTanpaAkun = [
            [
                'nik' => '2024005',
                'nama' => 'Roni Wibowo',
                'departemen_id' => $produksi->id,
            ],
            [
                'nik' => '2024006',
                'nama' => 'Maya Anggraeni',
                'departemen_id' => $gudang->id,
            ],
        ];

        foreach ($karyawanTanpaAkun as $data) {
            Karyawan::firstOrCreate(
                ['nik' => $data['nik']],
                [
                    'nama' => $data['nama'],
                    'departemen_id' => $data['departemen_id'],
                    'status_aktif' => true,
                ]
            );
        }

        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('  AKUN DUMMY YANG TERSEDIA — SISTEM VERIFIKASI PRP    ');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('  NIK: 9900001   | Password: NIK nya sendiri (9900001) | Role: QA  ');
        $this->command->info('  NIK: 2024001   | Password: NIK nya sendiri (2024001) | Role: Karyawan');
        $this->command->info('  NIK: 2024002   | Password: NIK nya sendiri (2024002) | Role: Karyawan');
        $this->command->info('  NIK: 2024003   | Password: NIK nya sendiri (2024003) | Role: Karyawan');
        $this->command->info('  NIK: 2024004   | Password: NIK nya sendiri (2024004) | Role: Karyawan');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('');
    }
}
