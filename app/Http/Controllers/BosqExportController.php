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
     * Guard: Hanya role QA yang dapat mengunduh export.
     */
    protected function requireQa(): void
    {
        if (auth()->user()?->role !== 'qa') {
            abort(403, 'Hanya QA yang dapat mengakses fitur export BOS\'Q.');
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
     * Export CSV Observasi BOS'Q (Excel Compatible)
     */
    public function excel(Request $request)
    {
        $this->requireQa();

        ['awal' => $awal, 'akhir' => $akhir, 'label' => $label] = $this->parseFilter($request);
        $temuans = $this->getTemuans($awal, $akhir, $request);

        $bom = "\xEF\xBB\xBF";
        $header = [
            'ID Observasi', 'Tanggal Observasi', 'Departemen', 'Sub Area', 'Detail Sub Area',
            'Observer / Pelapor (NIK)', 'Observer (Nama)', 'Auditee (NIK)', 'Auditee (Nama)',
            'Elemen QFS', 'Temuan BQA', 'Tingkat Risiko', 'Dampak Observasi',
            'Status', 'Action (Jika Negatif)', 'Due Date Action', 'Tanggal ACC QA', 'Catatan QA'
        ];

        $rows = [];
        foreach ($temuans as $t) {
            $tl = $t->tindakLanjut;
            $isClosed = in_array($t->status, ['closed', 'closed_acc']);
            $rows[] = [
                $t->id,
                $t->tanggal_temuan->format('Y-m-d'),
                $t->departemen->nama_departemen ?? '-',
                $t->subArea->nama_sub_area ?? '-',
                $t->detail_sub_area ?? '-',
                $t->pelapor->nik ?? '-',
                $t->pelapor->name ?? '-',
                $t->auditee->nik ?? '-',
                $t->auditee->name ?? '-',
                $t->elemenQfs->nama_elemen ?? '-',
                $t->temuan_bqa,
                str_replace('_', ' ', strtoupper($t->tingkat_resiko)),
                strtoupper($t->dampak_temuan),
                $isClosed ? 'CLOSED' : 'OPEN',
                $tl?->action ?? '-',
                $tl?->due_date ? Carbon::parse($tl->due_date)->format('Y-m-d') : '-',
                $tl?->tanggal_acc ? Carbon::parse($tl->tanggal_acc)->format('Y-m-d H:i') : '-',
                $tl?->catatan_qa ?? '-',
            ];
        }

        $callback = function () use ($bom, $header, $rows) {
            $file = fopen('php://output', 'w');
            fwrite($file, $bom);
            fputcsv($file, $header);
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        $filename = 'BOSQ_Observasi_' . str_replace(' ', '_', $label) . '.csv';

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
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

        return $pdf->stream('BOSQ_Dashboard_' . str_replace(' ', '_', $label) . '.pdf');
    }

    /**
     * Export CSV Rekap Kepatuhan
     */
    public function rekapExcel(Request $request)
    {
        $this->requireQa();

        $selectedDate = $request->input('date', Carbon::now()->toDateString());
        $dateObj = Carbon::parse($selectedDate);
        $startOfWeek = $dateObj->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
        $endOfWeek   = $dateObj->copy()->endOfWeek(Carbon::SUNDAY)->toDateString();
        $isoWeek     = $dateObj->isoWeek();
        $isoYear     = $dateObj->isoWeekYear();

        $bom = "\xEF\xBB\xBF";
        $header = [
            'Minggu Ke', 'Tahun', 'Periode Minggu', 'Departemen',
            'NIK Karyawan', 'Nama Karyawan', 'Target Laporan', 'Realisasi Laporan',
            'Status Kepatuhan Individu', 'Status Kepatuhan Departemen'
        ];

        $departemens = Departemen::orderBy('nama_departemen')->get();
        $rows = [];

        foreach ($departemens as $dept) {
            $anggotaList = Karyawan::with('user')
                ->where('departemen_id', $dept->id)
                ->where('is_anggota_divisi_manajemen', true)
                ->where('status_aktif', true)
                ->orderBy('nama')
                ->get();

            $anggotaCount = $anggotaList->count();
            $targetDept = $anggotaCount * 2;
            $realisasiDept = 0;

            $deptRows = [];
            foreach ($anggotaList as $k) {
                $realisasiIndividu = 0;
                if ($k->user) {
                    $realisasiIndividu = BosqTemuan::where('pelapor_id', $k->user->id)
                        ->whereBetween('tanggal_temuan', [$startOfWeek, $endOfWeek])
                        ->count();
                }
                $realisasiDept += $realisasiIndividu;

                $deptRows[] = [
                    $k->nik,
                    $k->nama,
                    2,
                    $realisasiIndividu,
                    $realisasiIndividu >= 2 ? 'Tercapai' : 'Belum Tercapai',
                ];
            }

            $statusDeptStr = ($anggotaCount === 0) ? 'Belum Ada Anggota Terdaftar' : ($realisasiDept >= $targetDept ? 'Target Tercapai' : 'Belum Tercapai');

            if ($anggotaCount === 0) {
                $rows[] = [
                    $isoWeek, $isoYear, "{$startOfWeek} s/d {$endOfWeek}", $dept->nama_departemen,
                    '-', '- (Tanpa Anggota Divisi Manajemen)', 0, 0,
                    '-', $statusDeptStr
                ];
            } else {
                foreach ($deptRows as $dr) {
                    $rows[] = [
                        $isoWeek, $isoYear, "{$startOfWeek} s/d {$endOfWeek}", $dept->nama_departemen,
                        $dr[0], $dr[1], $dr[2], $dr[3], $dr[4], $statusDeptStr
                    ];
                }
            }
        }

        $callback = function () use ($bom, $header, $rows) {
            $file = fopen('php://output', 'w');
            fwrite($file, $bom);
            fputcsv($file, $header);
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        $filename = "BOSQ_Rekap_Kepatuhan_Minggu_{$isoWeek}_{$isoYear}.csv";

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Export PDF Rekap Kepatuhan
     */
    public function pdfRekap(Request $request)
    {
        $this->requireQa();

        $selectedDate = $request->input('date', Carbon::now()->toDateString());
        $dateObj = Carbon::parse($selectedDate);
        $startOfWeek = $dateObj->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
        $endOfWeek   = $dateObj->copy()->endOfWeek(Carbon::SUNDAY)->toDateString();
        $isoWeek     = $dateObj->isoWeek();
        $isoYear     = $dateObj->isoWeekYear();

        $weekLabel = "Minggu ke-{$isoWeek} ({$isoYear}): " . Carbon::parse($startOfWeek)->translatedFormat('d M Y') . " s/d " . Carbon::parse($endOfWeek)->translatedFormat('d M Y');

        $departemens = Departemen::orderBy('nama_departemen')->get();
        $rekapData = [];
        $totalTarget = 0;
        $totalRealisasi = 0;

        foreach ($departemens as $dept) {
            $anggotaList = Karyawan::with('user')
                ->where('departemen_id', $dept->id)
                ->where('is_anggota_divisi_manajemen', true)
                ->where('status_aktif', true)
                ->orderBy('nama')
                ->get();

            $anggotaCount = $anggotaList->count();
            $targetDept = $anggotaCount * 2;
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

                $individuList[] = [
                    'nik'       => $k->nik,
                    'nama'      => $k->nama,
                    'target'    => 2,
                    'realisasi' => $realisasiIndividu,
                    'status'    => $realisasiIndividu >= 2 ? 'Tercapai' : 'Belum',
                ];
            }

            if ($anggotaCount > 0) {
                $totalTarget += $targetDept;
                $totalRealisasi += $realisasiDept;
            }

            $rekapData[] = [
                'departemen'    => $dept->nama_departemen,
                'anggota_count' => $anggotaCount,
                'target'        => $targetDept,
                'realisasi'     => $realisasiDept,
                'status'        => $anggotaCount === 0 ? 'no_members' : ($realisasiDept >= $targetDept ? 'tercapai' : 'belum'),
                'individu_list' => $individuList,
            ];
        }

        $pdf = Pdf::loadView('pdf.bosq-rekap', [
            'weekLabel'      => $weekLabel,
            'rekapData'      => $rekapData,
            'totalTarget'    => $totalTarget,
            'totalRealisasi' => $totalRealisasi,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("BOSQ_Rekap_Kepatuhan_Minggu_{$isoWeek}_{$isoYear}.pdf");
    }
}
