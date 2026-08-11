<?php

namespace App\Http\Controllers;

use App\Models\BosqTemuan;
use App\Models\Departemen;
use App\Models\Karyawan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BosqExportController extends Controller
{
    /**
     * Guard: Hanya role QA & PIC BOS'Q yang dapat mengunduh export Dashboard/Daftar.
     */
    protected function requireQa(): void
    {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        if ($user->role === 'superadmin') {
            abort(403, 'Super Admin tidak dapat mengakses data ini.');
        }

        if ($user->role !== 'qa' && !$user->isBosqPicUser()) {
            abort(403, 'Anda tidak memiliki hak akses untuk fitur export BOS\'Q.');
        }
    }

    /**
     * Parse parameter filter periode.
     */
    protected function parseFilter(Request $request): array
    {
        $tipe = $request->input('tipe', 'bulan');

        return match($tipe) {
            'custom' => [
                'awal'  => $request->input('awal', Carbon::now()->startOfMonth()->toDateString()),
                'akhir' => $request->input('akhir', Carbon::now()->toDateString()),
                'label' => $request->input('awal', '-') . ' s/d ' . $request->input('akhir', '-'),
            ],
            'tahun' => (function () use ($request) {
                $tahun = (int) $request->input('tahun', now()->year);
                $awal  = Carbon::createFromDate($tahun, 1, 1)->startOfYear()->toDateString();
                $akhir = Carbon::createFromDate($tahun, 1, 1)->endOfYear()->toDateString();
                return ['awal' => $awal, 'akhir' => $akhir, 'label' => "Tahun {$tahun}"];
            })(),
            default => (function () use ($request) {
                $bulan = (int) $request->input('bulan', now()->month);
                $tahun = (int) $request->input('tahun', now()->year);
                $awal  = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth()->toDateString();
                $akhir = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()->toDateString();
                $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y');
                return ['awal' => $awal, 'akhir' => $akhir, 'label' => $namaBulan];
            })(),
        };
    }

    /**
     * Ambil data bosq_temuan berdasarkan filter.
     */
    protected function getTemuans(string $awal, string $akhir, Request $request)
    {
        $query = BosqTemuan::with(['departemen', 'subArea', 'elemenQfs', 'pelapor', 'auditee', 'tindakLanjut'])
            ->whereBetween('tanggal_temuan', [$awal, $akhir]);

        if ($request->filled('departemen_id')) {
            $query->where('departemen_id', $request->input('departemen_id'));
        }
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'open') {
                $query->whereIn('status', ['open', 'in_progress', 'closed_pending_qa']);
            } else {
                $query->whereIn('status', ['closed', 'closed_acc']);
            }
        }
        if ($request->filled('tingkat_resiko')) {
            $query->where('tingkat_resiko', $request->input('tingkat_resiko'));
        }
        if ($request->filled('dampak_temuan')) {
            $query->where('dampak_temuan', $request->input('dampak_temuan'));
        }

        return $query->orderBy('tanggal_temuan', 'asc')->get();
    }

    /**
     * Export Excel Observasi BOS'Q (Formatted HTML Spreadsheet)
     */
    public function excel(Request $request)
    {
        $this->requireQa();

        ['awal' => $awal, 'akhir' => $akhir, 'label' => $label] = $this->parseFilter($request);
        $temuans = $this->getTemuans($awal, $akhir, $request);

        $total     = $temuans->count();
        $perDampak = [
            'positif' => $temuans->where('dampak_temuan', 'positif')->count(),
            'negatif' => $temuans->where('dampak_temuan', 'negatif')->count(),
        ];
        $perStatus = [
            'open'   => $temuans->whereIn('status', ['open', 'in_progress', 'closed_pending_qa'])->count(),
            'closed' => $temuans->whereIn('status', ['closed', 'closed_acc'])->count(),
        ];
        $perDepartemen = $temuans->groupBy('departemen_id')->map(function ($group) {
            $dept = $group->first()->departemen;
            return [
                'nama'    => $dept->nama_departemen ?? 'Tidak Diketahui',
                'total'   => $group->count(),
                'positif' => $group->where('dampak_temuan', 'positif')->count(),
                'negatif' => $group->where('dampak_temuan', 'negatif')->count(),
                'open'    => $group->whereIn('status', ['open', 'in_progress', 'closed_pending_qa'])->count(),
                'closed'  => $group->whereIn('status', ['closed', 'closed_acc'])->count(),
            ];
        })->values();

        $filename = 'BOSQ_Observasi_' . str_replace([' ', '/', '\\'], '_', $label) . '.xls';

        return response()->view('excel.bosq-daftar', [
            'temuans'       => $temuans,
            'total'         => $total,
            'perDampak'     => $perDampak,
            'perStatus'     => $perStatus,
            'perDepartemen' => $perDepartemen,
            'periodeLabel'  => $label,
            'awal'          => $awal,
            'akhir'         => $akhir,
        ])
        ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
        ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Export PDF Dashboard BOS'Q
     */
    public function pdfDashboard(Request $request)
    {
        $this->requireQa();

        ['awal' => $awal, 'akhir' => $akhir, 'label' => $label] = $this->parseFilter($request);
        $temuans = $this->getTemuans($awal, $akhir, $request);

        $pdf = Pdf::loadView('pdf.bosq-dashboard', [
            'filterLabel' => $label,
            'temuans'     => $temuans,
            'total'       => $temuans->count(),
            'open'        => $temuans->whereIn('status', ['open', 'in_progress', 'closed_pending_qa'])->count(),
            'closed'      => $temuans->whereIn('status', ['closed', 'closed_acc'])->count(),
            'negatif'     => $temuans->where('dampak_temuan', 'negatif')->count(),
            'positif'     => $temuans->where('dampak_temuan', 'positif')->count(),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('BOSQ_Dashboard_' . str_replace([' ', '/', '\\'], '_', $label) . '.pdf');
    }

    /**
     * Data helper untuk Rekap Kepatuhan (Summary Dept & Matrix Detail)
     */
    protected function getRekapKepatuhanData(string $selectedDate): array
    {
        $dateObj = Carbon::parse($selectedDate);
        $monthShort = strtoupper($dateObj->translatedFormat('M'));
        $monthName  = strtoupper($dateObj->translatedFormat('M Y'));

        $year = $dateObj->year;
        $month = sprintf('%02d', $dateObj->month);
        $lastDay = $dateObj->daysInMonth;

        $w1_start = "{$year}-{$month}-01";
        $w1_end   = "{$year}-{$month}-07";
        $w2_start = "{$year}-{$month}-08";
        $w2_end   = "{$year}-{$month}-14";
        $w3_start = "{$year}-{$month}-15";
        $w3_end   = "{$year}-{$month}-21";
        $w4_start = "{$year}-{$month}-22";
        $w4_end   = "{$year}-{$month}-" . sprintf('%02d', $lastDay);

        $w1_label = Carbon::parse($w1_start)->format('d') . '-' . Carbon::parse($w1_end)->format('d');
        $w2_label = Carbon::parse($w2_start)->format('d') . '-' . Carbon::parse($w2_end)->format('d');
        $w3_label = Carbon::parse($w3_start)->format('d') . '-' . Carbon::parse($w3_end)->format('d');
        $w4_label = Carbon::parse($w4_start)->format('d') . '-' . Carbon::parse($w4_end)->format('d');

        $weeks = [
            'w1' => ['start' => $w1_start, 'end' => $w1_end, 'label' => $w1_label, 'title' => 'WEEK 1'],
            'w2' => ['start' => $w2_start, 'end' => $w2_end, 'label' => $w2_label, 'title' => 'WEEK 2'],
            'w3' => ['start' => $w3_start, 'end' => $w3_end, 'label' => $w3_label, 'title' => 'WEEK 3'],
            'w4' => ['start' => $w4_start, 'end' => $w4_end, 'label' => $w4_label, 'title' => 'WEEK 4'],
        ];

        $departemens = Departemen::orderBy('nama_departemen')->get();

        $matrixData  = [];
        $deptSummary = [];

        foreach ($departemens as $dept) {
            $karyawans = Karyawan::with('user')
                ->where('departemen_id', $dept->id)
                ->where('status_aktif', true)
                ->where('is_anggota_divisi_manajemen', true)
                ->where('nama', 'not like', '%super administrator%')
                ->orderBy('nama')
                ->get();

            $memberCount = $karyawans->count();
            $membersData = [];

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

        return [
            'monthShort'  => $monthShort,
            'monthName'   => $monthName,
            'weeks'       => $weeks,
            'deptSummary' => $deptSummary,
            'matrixData'  => $matrixData,
        ];
    }

    /**
     * Export CSV Rekap Kepatuhan (Matriks Excel 2-Tabel)
     */
    public function rekapExcel(Request $request)
    {
        $this->requireQa();

        $selectedDate = $request->input('date', Carbon::now()->toDateString());
        $data = $this->getRekapKepatuhanData($selectedDate);

        $filename = "BOSQ_Rekap_Kepatuhan_BQA_" . str_replace(' ', '_', $data['monthName']) . ".xls";

        return response()->view('excel.bosq-rekap', $data)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Export PDF Rekap Kepatuhan
     */
    public function pdfRekap(Request $request)
    {
        $this->requireQa();

        $selectedDate = $request->input('date', Carbon::now()->toDateString());
        $data = $this->getRekapKepatuhanData($selectedDate);

        $pdf = Pdf::loadView('pdf.bosq-rekap', $data)->setPaper('a4', 'portrait');

        return $pdf->stream("BOSQ_Rekap_Kepatuhan_BQA_" . str_replace(' ', '_', $data['monthName']) . ".pdf");
    }
}
