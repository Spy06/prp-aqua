<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\Departemen;
use App\Models\KlausulPrp;
use Carbon\Carbon;

class MassiveTemuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Memulai seeding 10.000 data Temuan...');

        $karyawans = User::where('role', 'karyawan')->pluck('id')->toArray();
        $departemens = Departemen::pluck('id')->toArray();
        $klausuls = KlausulPrp::pluck('id')->toArray();
        $statuses = ['open', 'in_progress', 'closed_pending_qa', 'closed_acc'];

        if (empty($karyawans) || empty($departemens)) {
            $this->command->error('Data User Karyawan atau Departemen masih kosong! Harap jalankan DatabaseSeeder terlebih dahulu.');
            return;
        }

        $totalData = 10000;
        $chunkSize = 1000; // Insert per 1000 baris agar RAM tidak penuh
        $dataToInsert = [];

        // Pre-encrypt string untuk deskripsi agar tidak memberatkan Crypt di setiap loop
        // Secara teknis setiap deskripsi harus berbeda, namun untuk dummy ini kita bisa gunakan beberapa variasi
        $dummyDescriptions = [
            Crypt::encryptString('Ditemukan genangan air di area produksi yang berpotensi menjadi sarang hama.'),
            Crypt::encryptString('Mesin pengemas mengalami kebocoran oli, perlu segera diperbaiki.'),
            Crypt::encryptString('Atap di gudang bahan baku bocor, air menetes ke dekat tumpukan kardus.'),
            Crypt::encryptString('Suhu ruangan pendingin tidak stabil, berada di atas standar 5 derajat celcius.'),
            Crypt::encryptString('Ditemukan serangga di dekat pintu masuk area pemrosesan.')
        ];

        for ($i = 1; $i <= $totalData; $i++) {
            // Generate random date between 2018 and now
            $start = Carbon::create(2018, 1, 1)->timestamp;
            $end = Carbon::now()->timestamp;
            $randomTimestamp = mt_rand($start, $end);
            $randomDate = Carbon::createFromTimestamp($randomTimestamp);

            // Karena data harus dibagi ke 4 akun, kita pastikan pelapor disebar rata
            $pelaporId = $karyawans[$i % count($karyawans)];
            $picId = $karyawans[array_rand($karyawans)];

            $dataToInsert[] = [
                'tanggal_temuan'   => $randomDate->format('Y-m-d'),
                'pelapor_id'       => $pelaporId,
                'pic_id'           => $picId,
                'departemen_id'    => $departemens[array_rand($departemens)],
                'sub_area'         => 'Area Dummy ' . rand(1, 100),
                'klausul_id'       => !empty($klausuls) ? $klausuls[array_rand($klausuls)] : null,
                'foto_temuan_path' => null,
                'deskripsi'        => $dummyDescriptions[array_rand($dummyDescriptions)],
                'saran'            => 'Tolong segera dicek dan diperbaiki.',
                'status'           => $statuses[array_rand($statuses)],
                'created_at'       => $randomDate->toDateTimeString(),
                'updated_at'       => $randomDate->toDateTimeString(),
            ];

            // Insert per chunk
            if ($i % $chunkSize === 0) {
                DB::table('temuan')->insert($dataToInsert);
                $this->command->info("Berhasil insert $i data...");
                $dataToInsert = []; // Reset array
            }
        }

        // Jika ada sisa
        if (count($dataToInsert) > 0) {
            DB::table('temuan')->insert($dataToInsert);
            $this->command->info("Berhasil insert sisa data...");
        }

        $this->command->info('Selesai membuat 10.000 data Temuan!');
    }
}
