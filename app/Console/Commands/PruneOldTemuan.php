<?php

namespace App\Console\Commands;

use App\Models\Temuan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PruneOldTemuan extends Command
{
    /**
     * Nama dan deskripsi perintah Artisan.
     *
     * @var string
     */
    protected $signature = 'prp:prune-temuan {--years= : Jumlah tahun retensi data temuan (default dari config/prp.php)}';

    /**
     * Deskripsi perintah.
     *
     * @var string
     */
    protected $description = 'Menghapus data temuan beserta foto pendukung yang sudah berusia lebih dari X tahun.';

    /**
     * Eksekusi perintah Artisan.
     */
    public function handle(): int
    {
        $yearsOption = $this->option('years');
        $years = $yearsOption !== null ? (int) $yearsOption : (int) config('prp.retention_years', 2);

        if ($years <= 0) {
            $this->error('Jumlah tahun retensi harus berupa angka positif lebih dari 0.');
            return Command::FAILURE;
        }

        $cutoffDate = now()->subYears($years);

        $temuans = Temuan::with('tindakLanjut')
            ->where('created_at', '<', $cutoffDate)
            ->get();

        $count = $temuans->count();

        if ($count === 0) {
            $this->info("Tidak ada data temuan yang berusia lebih dari {$years} tahun untuk dihapus.");
            return Command::SUCCESS;
        }

        foreach ($temuans as $temuan) {
            // Hapus file foto temuan jika ada di storage public
            if ($temuan->foto_temuan_path && Storage::disk('public')->exists($temuan->foto_temuan_path)) {
                Storage::disk('public')->delete($temuan->foto_temuan_path);
            }

            // Hapus foto bukti tindak lanjut jika ada
            if ($temuan->tindakLanjut) {
                if ($temuan->tindakLanjut->foto_bukti_path && Storage::disk('public')->exists($temuan->tindakLanjut->foto_bukti_path)) {
                    Storage::disk('public')->delete($temuan->tindakLanjut->foto_bukti_path);
                }
                $temuan->tindakLanjut->delete();
            }

            // Hapus record temuan
            $temuan->delete();
        }

        $this->info("Berhasil menghapus {$count} data temuan (beserta foto pendukung) yang berusia lebih dari {$years} tahun.");
        return Command::SUCCESS;
    }
}
