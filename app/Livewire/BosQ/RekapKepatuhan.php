<?php

namespace App\Livewire\BosQ;

use App\Models\BosqTemuan;
use App\Models\Departemen;
use App\Models\Karyawan;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\Attributes\Url;

class RekapKepatuhan extends Component
{
    #[Url]
    public string $bulan_tahun = '';
    
    // Simpan memori tanggal kustom per bulan
    public array $history_weeks = [];
    
    // Tanggal kustom untuk Week 1 s/d Week 4
    public string $week1_start = '';
    public string $week1_end   = '';
    
    public string $week2_start = '';
    public string $week2_end   = '';
    
    public string $week3_start = '';
    public string $week3_end   = '';
    
    public string $week4_start = '';
    public string $week4_end   = '';

    public function mount(): void
    {
        $this->history_weeks = session()->get('bosq_rekap_history_weeks', []);

        if (empty($this->bulan_tahun)) {
            $this->bulan_tahun = Carbon::now()->format('Y-m');
        }
        
        if (empty($this->week1_start)) {
            if (isset($this->history_weeks[$this->bulan_tahun])) {
                $this->restoreFromHistory($this->bulan_tahun);
            } else {
                $this->generateDefaultWeeks();
            }
        }
    }

    public function updated($property): void
    {
        // Simpan setiap perubahan tanggal langsung ke session agar tahan terhadap F5 Refresh
        if (str_starts_with($property, 'week')) {
            $this->saveToHistory();
        }
    }

    public function updatingBulanTahun(): void
    {
        // Simpan state tanggal kustom bulan yang lama sebelum diganti ke dalam Session
        $this->saveToHistory();
    }

    private function saveToHistory(): void
    {
        $this->history_weeks[$this->bulan_tahun] = [
            'w1s' => $this->week1_start,
            'w1e' => $this->week1_end,
            'w2s' => $this->week2_start,
            'w2e' => $this->week2_end,
            'w3s' => $this->week3_start,
            'w3e' => $this->week3_end,
            'w4s' => $this->week4_start,
            'w4e' => $this->week4_end,
        ];
        session()->put('bosq_rekap_history_weeks', $this->history_weeks);
    }

    public function updatedBulanTahun(): void
    {
        // Kembalikan state tanggal kustom jika bulan tersebut pernah dibuka sebelumnya
        if (isset($this->history_weeks[$this->bulan_tahun])) {
            $this->restoreFromHistory($this->bulan_tahun);
        } else {
            $this->generateDefaultWeeks();
        }
    }

    private function restoreFromHistory(string $bulan): void
    {
        $h = $this->history_weeks[$bulan];
        $this->week1_start = $h['w1s'];
        $this->week1_end   = $h['w1e'];
        $this->week2_start = $h['w2s'];
        $this->week2_end   = $h['w2e'];
        $this->week3_start = $h['w3s'];
        $this->week3_end   = $h['w3e'];
        $this->week4_start = $h['w4s'];
        $this->week4_end   = $h['w4e'];
    }

    /**
     * Set patokan tanggal default Week 1-4 berdasarkan bulan yang dipilih.
     */
    public function generateDefaultWeeks(): void
    {
        try {
            $date = Carbon::parse($this->bulan_tahun . '-01');
        } catch (\Exception $e) {
            $date = Carbon::now()->startOfMonth();
            $this->bulan_tahun = $date->format('Y-m');
        }

        $year = $date->year;
        $month = sprintf('%02d', $date->month);
        $lastDay = $date->daysInMonth;

        // Week 1: tgl 1 - 07
        $this->week1_start = "{$year}-{$month}-01";
        $this->week1_end   = "{$year}-{$month}-07";

        // Week 2: tgl 08 - 14
        $this->week2_start = "{$year}-{$month}-08";
        $this->week2_end   = "{$year}-{$month}-14";

        // Week 3: tgl 15 - 21
        $this->week3_start = "{$year}-{$month}-15";
        $this->week3_end   = "{$year}-{$month}-21";

        // Week 4: tgl 22 - akhir bulan
        $this->week4_start = "{$year}-{$month}-22";
        $this->week4_end   = "{$year}-{$month}-" . sprintf('%02d', $lastDay);
    }

    public function render()
    {
        $dateObj = Carbon::parse($this->bulan_tahun . '-01');
        $monthShort = strtoupper($dateObj->translatedFormat('M')); // e.g. JUL
        $monthName  = strtoupper($dateObj->translatedFormat('M Y')); // e.g. JULI 2026

        // Label rentang tanggal per week untuk header tabel
        $w1_label = Carbon::parse($this->week1_start)->format('d') . '-' . Carbon::parse($this->week1_end)->format('d');
        $w2_label = Carbon::parse($this->week2_start)->format('d') . '-' . Carbon::parse($this->week2_end)->format('d');
        $w3_label = Carbon::parse($this->week3_start)->format('d') . '-' . Carbon::parse($this->week3_end)->format('d');
        $w4_label = Carbon::parse($this->week4_start)->format('d') . '-' . Carbon::parse($this->week4_end)->format('d');

        $weeks = [
            'w1' => ['start' => $this->week1_start, 'end' => $this->week1_end, 'label' => $w1_label, 'title' => 'WEEK 1'],
            'w2' => ['start' => $this->week2_start, 'end' => $this->week2_end, 'label' => $w2_label, 'title' => 'WEEK 2'],
            'w3' => ['start' => $this->week3_start, 'end' => $this->week3_end, 'label' => $w3_label, 'title' => 'WEEK 3'],
            'w4' => ['start' => $this->week4_start, 'end' => $this->week4_end, 'label' => $w4_label, 'title' => 'WEEK 4'],
        ];

        // Ambil semua departemen
        $departemens = Departemen::orderBy('nama_departemen')->get();

        $matrixData  = [];
        $deptSummary = [];

        foreach ($departemens as $dept) {
            // Ambil anggota karyawan di departemen ini
            $karyawans = Karyawan::with('user')
                ->where('departemen_id', $dept->id)
                ->where('status_aktif', true)
                ->where('is_anggota_divisi_manajemen', true)
                ->where('nama', 'not like', '%super administrator%')
                ->orderBy('nama')
                ->get();

            $memberCount = $karyawans->count();
            $membersData = [];

            // Hitungan total per-week per-departemen
            $deptWeekTotals = [
                'w1' => ['realisasi' => 0, 'target' => $memberCount * 2],
                'w2' => ['realisasi' => 0, 'target' => $memberCount * 2],
                'w3' => ['realisasi' => 0, 'target' => $memberCount * 2],
                'w4' => ['realisasi' => 0, 'target' => $memberCount * 2],
            ];

            foreach ($karyawans as $k) {
                $scores = [];

                foreach ($weeks as $wKey => $wVal) {
                    $count = 0;
                    if ($k->user) {
                        $count = BosqTemuan::where('pelapor_id', $k->user->id)
                            ->whereBetween('tanggal_temuan', [$wVal['start'], $wVal['end']])
                            ->count();
                    }

                    $targetIndividu = 2;
                    $persenIndividu = min(100, round(($count / $targetIndividu) * 100));

                    $scores[$wKey] = [
                        'count'  => $count,
                        'target' => $targetIndividu,
                        'persen' => $persenIndividu,
                    ];

                    $deptWeekTotals[$wKey]['realisasi'] += $count;
                }

                $membersData[] = [
                    'nik'    => $k->nik,
                    'nama'   => $k->nama,
                    'scores' => $scores,
                ];
            }

            // Hitung persentase departemen per week
            $deptScores = [];
            foreach ($weeks as $wKey => $wVal) {
                $targetDept = $deptWeekTotals[$wKey]['target'];
                $realisasiDept = $deptWeekTotals[$wKey]['realisasi'];

                if ($targetDept > 0) {
                    $persenDept = min(100, round(($realisasiDept / $targetDept) * 100));
                } else {
                    $persenDept = 0;
                }

                $deptScores[$wKey] = [
                    'realisasi' => $realisasiDept,
                    'target'    => $targetDept,
                    'persen'    => $persenDept,
                ];
            }

            $deptSummary[] = [
                'nama'   => strtoupper($dept->nama_departemen),
                'scores' => $deptScores,
            ];

            $matrixData[] = [
                'departemen_id'   => $dept->id,
                'nama_departemen' => strtoupper($dept->nama_departemen),
                'members'         => $membersData,
            ];
        }

        return view('livewire.bosq.rekap-kepatuhan', [
            'monthShort'  => $monthShort,
            'monthName'   => $monthName,
            'weeks'       => $weeks,
            'deptSummary' => $deptSummary,
            'matrixData'  => $matrixData,
        ])->layout('layouts.bosq', ['title' => 'Rekap Kepatuhan BQA — BOS\'Q']);
    }
}
