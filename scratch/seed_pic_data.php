<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$data = [
    // MANUFACTURING
    ['dept' => 'MANUFACTURING', 'name' => 'Ruli Agus Setiawan', 'nik' => '18978', 'email' => 'Rulli.Setiawan@danone.com'],
    ['dept' => 'MANUFACTURING', 'name' => 'Muhammad Zaenal', 'nik' => '21170', 'email' => 'Muhammad.ZAENAL1@danone.com'],
    ['dept' => 'MANUFACTURING', 'name' => 'Imron Rizaldi', 'nik' => '23354', 'email' => 'Imron.RIZALDI@danone.com'],
    ['dept' => 'MANUFACTURING', 'name' => 'Maulana Ahmad R', 'nik' => '19564', 'email' => 'Maulana.Ramdani@danone.com'],
    ['dept' => 'MANUFACTURING', 'name' => 'Lufti', 'nik' => '20364', 'email' => 'Lufti.ARHAM@danone.com'],
    ['dept' => 'MANUFACTURING', 'name' => 'Tekun', 'nik' => '21761', 'email' => 'Tekun.SUPRIATNA@danone.com'],
    ['dept' => 'MANUFACTURING', 'name' => 'Feri Setyo Priyadi', 'nik' => '18633', 'email' => 'Feri.Priyadi@danone.com'],
    ['dept' => 'MANUFACTURING', 'name' => 'Wisnu Ilham Setia Budi', 'nik' => '18631', 'email' => 'Wisnu.Setiabudi@danone.com'],
    ['dept' => 'MANUFACTURING', 'name' => 'Samsudin', 'nik' => '19567', 'email' => 'Samsudin.SAMSUDIN@danone.com'],
    ['dept' => 'MANUFACTURING', 'name' => 'Triatmojo', 'nik' => '18977', 'email' => 'Triatmojo.TRIATMOJO@danone.com'],
    ['dept' => 'MANUFACTURING', 'name' => 'Agus Ismail', 'nik' => '19597', 'email' => 'Agus.ISMAIL@danone.com'],
    ['dept' => 'MANUFACTURING', 'name' => 'Simanaldo Pulungan', 'nik' => '20665', 'email' => 'Simanaldo.PULUNGAN@danone.com'],
    ['dept' => 'MANUFACTURING', 'name' => 'Fery Suryadi', 'nik' => '23634', 'email' => 'Fery.SURYADI@danone.com'],
    ['dept' => 'MANUFACTURING', 'name' => 'Ukat Abdul Aziz', 'nik' => '20015', 'email' => 'Ukat.ABDUL-AZIZ@danone.com'],

    // LOGISTIC
    ['dept' => 'LOGISTIC', 'name' => 'Ade Gojali Muchsin', 'nik' => '20573', 'email' => 'Ade.MUCHSIN@danone.com'],
    ['dept' => 'LOGISTIC', 'name' => 'Juliansyah', 'nik' => '22912', 'email' => 'Juliansyah.SAPUTRA@danone.com'],
    ['dept' => 'LOGISTIC', 'name' => 'Ali Sunandar', 'nik' => '19973', 'email' => 'Ali.SUNANDAR@danone.com'],
    ['dept' => 'LOGISTIC', 'name' => 'Juned P.', 'nik' => '18869', 'email' => 'Juned.PRIHATINOKO@danone.com'],
    ['dept' => 'LOGISTIC', 'name' => 'Ridwan Mundiri', 'nik' => '20169', 'email' => 'Ridwan.MUNDIRI@danone.com'],
    ['dept' => 'LOGISTIC', 'name' => 'Opan', 'nik' => '18986', 'email' => 'Opan.SOPIANDI@danone.com'],
    ['dept' => 'LOGISTIC', 'name' => 'Hendri S', 'nik' => '19562', 'email' => 'Hendri.SUPRIATNA@danone.com'],
    ['dept' => 'LOGISTIC', 'name' => 'Dama', 'nik' => '22882', 'email' => 'Dama.SAPOETRA@danone.com'],
    ['dept' => 'LOGISTIC', 'name' => 'Yuswita', 'nik' => '22222', 'email' => 'Yuswita.SETIANA@danone.com'],

    // ENGINEERING
    ['dept' => 'ENGINEERING', 'name' => 'Taufik Jaelani', 'nik' => '20286', 'email' => 'Taufik.JAELANI@danone.com'],
    ['dept' => 'ENGINEERING', 'name' => 'Dadang Hermawan', 'nik' => '18715', 'email' => 'Dadang.Hermawan@danone.com'],
    ['dept' => 'ENGINEERING', 'name' => 'Riyanto Sofyan', 'nik' => '20203', 'email' => 'Riyanto.Sofyan@danone.com'],
    ['dept' => 'ENGINEERING', 'name' => 'M. Rahmat', 'nik' => '20427', 'email' => 'Muhammad.RACHMAT@danone.com'],
    ['dept' => 'ENGINEERING', 'name' => 'Dede Trisna Y', 'nik' => '18920', 'email' => 'Dede.TRISNA-Y@danone.com'],
    ['dept' => 'ENGINEERING', 'name' => 'Arif Tabari', 'nik' => '18712', 'email' => 'Arif.Tabari@danone.com'],

    // QUALITY
    ['dept' => 'QUALITY', 'name' => "Sandi Bidayatul I'tibar", 'nik' => '19792', 'email' => 'Sandi.ITIBAR@danone.com'],
    ['dept' => 'QUALITY', 'name' => 'Lia Atikah', 'nik' => '20663', 'email' => 'Lia.ATIKAH@danone.com'],
    ['dept' => 'QUALITY', 'name' => 'Rika Lindayani', 'nik' => '18629', 'email' => 'Rika.LINDAYANI@danone.com'],
    ['dept' => 'QUALITY', 'name' => 'Dicky Aditya Rahman', 'nik' => '19730', 'email' => 'dicky.aditya-rahman@danone.com'],
    ['dept' => 'QUALITY', 'name' => 'Khoerul', 'nik' => '18816', 'email' => 'khoerul.18816@danone.com'], // Adjusted duplicate email
    ['dept' => 'QUALITY', 'name' => 'Ruslan Abdul Gani', 'nik' => '14427', 'email' => 'Ruslan.GANI@danone.com'],

    // HUMAN RESOURCES
    ['dept' => 'HUMAN RESOURCES', 'name' => 'Monica', 'nik' => '23056', 'email' => 'Monica.Monica@danone.com'],
    ['dept' => 'HUMAN RESOURCES', 'name' => 'Iwan Ridwan', 'nik' => '03758', 'email' => 'Iwan.RIDWAN@danone.com'],
    ['dept' => 'HUMAN RESOURCES', 'name' => 'Shinta Sri Rahayu', 'nik' => '19165', 'email' => 'Shinta.RAHAYU@danone.com'],
    ['dept' => 'HUMAN RESOURCES', 'name' => 'Imron Rosadih', 'nik' => '21503', 'email' => 'Imron.Rosadih@danone.com'],

    // CSR
    ['dept' => 'CSR', 'name' => 'Warsono', 'nik' => '02211', 'email' => 'Warsono.USEP@danone.com'],
    ['dept' => 'CSR', 'name' => 'Uden Winajat', 'nik' => '21749', 'email' => 'Uden.WINAJAT@danone.com'],

    // FINANCE
    ['dept' => 'FINANCE', 'name' => 'Putri', 'nik' => '23491', 'email' => 'Putri.BR-PARANGIN-ANGIN@danone.com'],

    // PERFORMANCE
    ['dept' => 'PERFORMANCE', 'name' => 'Ricky Purba', 'nik' => '24696', 'email' => 'Ricky.PURBA@danone.com'],
    ['dept' => 'PERFORMANCE', 'name' => 'Encep', 'nik' => '20664', 'email' => 'Encep.SUNANDAR@danone.com'],
    ['dept' => 'PERFORMANCE', 'name' => 'Robi Darwis', 'nik' => '19034', 'email' => 'Robi.Darwis@danone.com'],
    ['dept' => 'PERFORMANCE', 'name' => 'Saepul Pajar', 'nik' => '19972', 'email' => 'Admin.SparepartCjr@danone.com'],

    // SHE
    ['dept' => 'SHE', 'name' => 'Ricky Ryantono', 'nik' => '17168', 'email' => 'Ricky.Ryantono@danone.com'],
    ['dept' => 'SHE', 'name' => 'Agung P', 'nik' => '23048', 'email' => 'Agung.PRAYOGA@danone.com'],
    ['dept' => 'SHE', 'name' => 'Avief Syurahman', 'nik' => '20202', 'email' => 'Avief.SURAHMAN@danone.com'],
];

$successCount = 0;
$updatedCount = 0;

DB::transaction(function () use ($data, &$successCount, &$updatedCount) {
    foreach ($data as $item) {
        $dept = Departemen::firstOrCreate(['nama_departemen' => $item['dept']]);

        $karyawan = Karyawan::where('nik', $item['nik'])->first();
        if ($karyawan) {
            $karyawan->update([
                'nama'          => $item['name'],
                'departemen_id' => $dept->id,
                'status_aktif'  => true,
            ]);
            $updatedCount++;
        } else {
            Karyawan::create([
                'nik'           => $item['nik'],
                'nama'          => $item['name'],
                'departemen_id' => $dept->id,
                'status_aktif'  => true,
            ]);
            $successCount++;
        }

        // Handle unique email check
        $email = $item['email'];
        $existingUserWithEmail = User::where('email', $email)->where('nik', '!=', $item['nik'])->first();
        if ($existingUserWithEmail) {
            $email = strtolower(str_replace(' ', '', $item['name'])) . '.' . $item['nik'] . '@danone.com';
        }

        User::updateOrCreate(
            ['nik' => $item['nik']],
            [
                'name'     => $item['name'],
                'email'    => $email,
                'role'     => 'karyawan',
                'password' => Hash::make($item['nik']),
            ]
        );
    }
});

echo "Import Result: Created {$successCount} new PICs, Updated {$updatedCount} existing PICs. Total processed: " . count($data) . PHP_EOL;
