<?php

namespace App\Livewire\BosQ;

use App\Models\BosqTemuan;
use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Component;

class RekapKepatuhan extends Component
{
    public string $selected_date = '';
    public ?int $expanded_departemen_id = null;

    public function mount(): void
    {
        $this->selected_date = Carbon::now()->toDateString();
    }

    public function prevWeek(): void
    {
        $this->selected_date = Carbon::parse($this->selected_date)->subWeek()->toDateString();
    }

    public function nextWeek(): void
    {
        $this->selected_date = Carbon::parse($this->selected_date)->addWeek()->toDateString();
    }

    public function currentWeek(): void
    {
        $this->selected_date = Carbon::now()->toDateString();
    }

    public function toggleExpand(int $deptId): void
    {
        if ($this->expanded_departemen_id === $deptId) {
            $this->expanded_departemen_id = null;
        } else {
            $this->expanded_departemen_id = $deptId;
        }
    }

    public function render()
    {
        $dateObj = Carbon::parse($this->selected_date);
        $startOfWeek = $dateObj->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
        $endOfWeek   = $dateObj->copy()->endOfWeek(Carbon::SUNDAY)->toDateString();
        $isoWeek     = $dateObj->isoWeek();
        $isoYear     = $dateObj->isoWeekYear();

        $weekLabel = "Minggu ke-{$isoWeek} ({$isoYear}): " . Carbon::parse($startOfWeek)->translatedFormat('d M Y') . " s/d " . Carbon::parse($endOfWeek)->translatedFormat('d M Y');

        $departemens = Departemen::orderBy('nama_departemen')->get();

        $rekapData = [];
        $totalTargetSemua = 0;
        $totalRealisasiSemua = 0;

        foreach ($departemens as $dept) {
            // Ambil semua karyawan anggota divisi manajemen aktif di departemen ini
            $anggotaList = Karyawan::with('user')
                ->where('departemen_id', $dept->id)
                ->where('is_anggota_divisi_manajemen', true)
                ->where('status_aktif', true)
                ->orderBy('nama')
                ->get();

            $anggotaCount = $anggotaList->count();
            $targetDept = $anggotaCount * 2; // Target 2 laporan/minggu per anggota

            $realisasiDept = 0;
            $individuList = [];

            foreach ($anggotaList as $k) {
                $realisasiIndividu = 0;
                if ($k->user) {
                    $realisasiIndividu = BosqTemuan::where('pelapor_id', $k->user->id)
                        ->whereBetween('tanggal_temuan', [$startOfWeek, $endOfWeek])
                        ->count();
                }

                $realisasiDept += $realisasiIndividu;

                $targetIndividu = 2;
                $statusIndividu = $realisasiIndividu >= $targetIndividu ? 'tercapai' : 'belum';
                $persenIndividu = min(100, round(($realisasiIndividu / $targetIndividu) * 100));

                $individuList[] = [
                    'nik'            => $k->nik,
                    'nama'           => $k->nama,
                    'has_user'       => (bool) $k->user,
                    'target'         => $targetIndividu,
                    'realisasi'      => $realisasiIndividu,
                    'status'         => $statusIndividu,
                    'persentase'     => $persenIndividu,
                ];
            }

            if ($anggotaCount == 0) {
                $statusDept = 'no_members';
                $persenDept = 'N/A';
            } else {
                $statusDept = $realisasiDept >= $targetDept ? 'tercapai' : 'belum';
                $persenDept = min(100, round(($realisasiDept / $targetDept) * 100, 1)) . '%';
                $totalTargetSemua += $targetDept;
                $totalRealisasiSemua += $realisasiDept;
            }

            $rekapData[] = [
                'departemen_id' => $dept->id,
                'nama'          => $dept->nama_departemen,
                'anggota_count' => $anggotaCount,
                'target'        => $targetDept,
                'realisasi'     => $realisasiDept,
                'status'        => $statusDept,
                'persentase'    => $persenDept,
                'individu_list' => $individuList,
            ];
        }

        return view('livewire.bosq.rekap-kepatuhan', [
            'weekLabel'           => $weekLabel,
            'startOfWeek'         => $startOfWeek,
            'endOfWeek'           => $endOfWeek,
            'rekapData'           => $rekapData,
            'totalTargetSemua'    => $totalTargetSemua,
            'totalRealisasiSemua' => $totalRealisasiSemua,
        ])->layout('layouts.bosq', ['title' => 'Rekap Kepatuhan BOS\'Q']);
    }
}
