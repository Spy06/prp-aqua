<?php

namespace App\Http\Controllers;

use App\Models\Temuan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class ExportController extends Controller
{
    /**
     * Guard: hanya role QA yang boleh mengakses semua method di controller ini.
     */
    protected function requireQa(): void
    {
        if (auth()->user()?->role !== 'qa') {
            abort(403, 'Hanya QA yang dapat mengakses fitur export.');
        }
    }

    /**
     * Parse parameter filter (dipakai bersama di semua method export).
     *
     * @return array{awal: string, akhir: string, label: string}
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
            default => (function () use ($request) { // 'bulan'
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
     * Ambil data temuan sesuai filter.
     */
    protected function getTemuans(string $awal, string $akhir, Request $request)
    {
        $query = Temuan::with(['departemen', 'pelapor', 'pic', 'klausul', 'tindakLanjut'])
            ->whereBetween('tanggal_temuan', [$awal, $akhir]);

        if ($request->filled('departemen_id')) {
            $query->where('departemen_id', $request->input('departemen_id'));
        }

        if ($request->filled('sub_area_names')) {
            $subAreaNames = (array) $request->input('sub_area_names');
            $subAreaNames = array_filter($subAreaNames);
            if (!empty($subAreaNames)) {
                $query->whereIn('sub_area', $subAreaNames);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return $query->orderBy('tanggal_temuan', 'asc')->get();
    }

    // =====================================================================
    // Export Excel (CSV yang kompatibel Excel, tanpa ext-zip)
    // =====================================================================

    /**
     * GET /export/excel
     * Query params: tipe=bulan|tahun|custom, bulan, tahun, awal, akhir, departemen_id, status, sub_area_names
     */
    public function excel(Request $request)
    {
        $this->requireQa();

        ['awal' => $awal, 'akhir' => $akhir, 'label' => $label] = $this->parseFilter($request);
        $temuans = $this->getTemuans($awal, $akhir, $request);

        $total     = $temuans->count();
        $perStatus = [
            'open'              => $temuans->where('status', 'open')->count(),
            'in_progress'       => $temuans->where('status', 'in_progress')->count(),
            'closed_pending_qa' => $temuans->where('status', 'closed_pending_qa')->count(),
            'closed_acc'        => $temuans->where('status', 'closed_acc')->count(),
        ];
        $perDepartemen = $temuans->groupBy('departemen_id')->map(function ($group) {
            $dept = $group->first()->departemen;
            return [
                'nama'              => $dept->nama_departemen ?? 'Tidak Diketahui',
                'total'             => $group->count(),
                'open'              => $group->where('status', 'open')->count(),
                'in_progress'       => $group->where('status', 'in_progress')->count(),
                'closed_pending_qa' => $group->where('status', 'closed_pending_qa')->count(),
                'closed_acc'        => $group->where('status', 'closed_acc')->count(),
            ];
        })->values();

        $filename = 'SIVERA_Daftar_Temuan_' . str_replace([' ', '/', '\\'], '_', $label) . '.xls';

        return response()->view('excel.rekap', [
            'temuans'       => $temuans,
            'total'         => $total,
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
     * GET /export/pdf/daftar
     * Export PDF Daftar Semua Temuan SIVERA (Text Details Only, Tanpa Gambar)
     */
    public function pdfDaftar(Request $request)
    {
        $this->requireQa();

        ['awal' => $awal, 'akhir' => $akhir, 'label' => $label] = $this->parseFilter($request);
        $temuans = $this->getTemuans($awal, $akhir, $request);

        $pdf = Pdf::loadView('pdf.daftar-temuan', [
            'filterLabel' => $label,
            'temuans'     => $temuans,
            'total'       => $temuans->count(),
            'open'        => $temuans->where('status', 'open')->count(),
            'inProgress'  => $temuans->where('status', 'in_progress')->count(),
            'pendingQa'   => $temuans->where('status', 'closed_pending_qa')->count(),
            'closedAcc'   => $temuans->where('status', 'closed_acc')->count(),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('SIVERA_Daftar_Temuan_' . str_replace([' ', '/', '\\'], '_', $label) . '.pdf');
    }

    // =====================================================================
    // Export PDF — Satu Temuan
    // =====================================================================

    /**
     * GET /export/pdf/temuan/{id}
     */
    public function pdfTemuan(Temuan $temuan)
    {

        $temuan->loadMissing(['departemen', 'pelapor', 'pic', 'klausul', 'tindakLanjut']);

        // Resolve URL foto untuk DomPDF (Gunakan Base64 agar lintas platform & menghindari isu path Windows)
        $fotoTemuanUrl = null;
        if ($temuan->foto_temuan_path) {
            $path = Storage::disk('public')->path($temuan->foto_temuan_path);
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $fotoTemuanUrl = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }

        $fotoBuktiUrls = [];
        $docBuktiFiles = [];
        $fotoBuktiUrl  = null;

        if ($tl = $temuan->tindakLanjut) {
            foreach ($tl->bukti_paths as $bPath) {
                $path = Storage::disk('public')->path($bPath);
                if (file_exists($path)) {
                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                        $mimeType = $ext === 'jpg' ? 'jpeg' : $ext;
                        $data     = file_get_contents($path);
                        $url      = 'data:image/' . $mimeType . ';base64,' . base64_encode($data);
                        $fotoBuktiUrls[] = $url;
                        if (!$fotoBuktiUrl) {
                            $fotoBuktiUrl = $url;
                        }
                    } else {
                        $docBuktiFiles[] = [
                            'name' => basename($bPath),
                            'ext'  => strtoupper($ext),
                        ];
                    }
                }
            }
        }

        $pdf = Pdf::loadView('pdf.temuan-detail', [
            'temuan'        => $temuan,
            'fotoTemuanUrl' => $fotoTemuanUrl,
            'fotoBuktiUrl'  => $fotoBuktiUrl,
            'fotoBuktiUrls' => $fotoBuktiUrls,
            'docBuktiFiles' => $docBuktiFiles,
        ])->setPaper('a4', 'portrait');

        $filename = "laporan-temuan-{$temuan->id}-" . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    // =====================================================================
    // Export PDF — Rekap Periode
    // =====================================================================

    /**
     * GET /export/pdf/rekap
     * Query params: tipe=bulan|tahun|custom, bulan, tahun, awal, akhir
     */
    public function pdfRekap(Request $request)
    {
        $this->requireQa();

        ['awal' => $awal, 'akhir' => $akhir, 'label' => $label] = $this->parseFilter($request);
        $temuans = $this->getTemuans($awal, $akhir, $request);

        $total     = $temuans->count();
        $perStatus = [
            'open'              => $temuans->where('status', 'open')->count(),
            'in_progress'       => $temuans->where('status', 'in_progress')->count(),
            'closed_pending_qa' => $temuans->where('status', 'closed_pending_qa')->count(),
            'closed_acc'        => $temuans->where('status', 'closed_acc')->count(),
        ];
        $perDepartemen = $temuans->groupBy('departemen_id')->map(function ($group) {
            $dept = $group->first()->departemen;
            return [
                'nama'              => $dept->nama_departemen ?? 'Tidak Diketahui',
                'total'             => $group->count(),
                'open'              => $group->where('status', 'open')->count(),
                'in_progress'       => $group->where('status', 'in_progress')->count(),
                'closed_pending_qa' => $group->where('status', 'closed_pending_qa')->count(),
                'closed_acc'        => $group->where('status', 'closed_acc')->count(),
            ];
        })->values();

        $pdf = Pdf::loadView('pdf.rekap', [
            'temuans'       => $temuans,
            'total'         => $total,
            'perStatus'     => $perStatus,
            'perDepartemen' => $perDepartemen,
            'periodeLabel'  => $label,
            'awal'          => $awal,
            'akhir'         => $akhir,
        ])->setPaper('a4', 'landscape');

        $filename = 'rekap-temuan-prp-' . str_replace([' ', '/', '\\'], '-', strtolower($label)) . '-' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
