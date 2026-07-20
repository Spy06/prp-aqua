<div class="fu" id="rekap-periode-container">

    {{-- Header --}}
    <div class="bph">
        <div>
            <h2 class="bph-title">Rekap Periode</h2>
            <p class="bph-sub">Rangkuman temuan dalam rentang waktu tertentu</p>
        </div>
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('export.excel', $queryParams) }}" target="_blank" class="bbtn bbtn-success">
                <span class="material-symbols-outlined" style="font-size:16px;">table_chart</span> Excel
            </a>
            <a href="{{ route('export.pdf.rekap', $queryParams) }}" target="_blank" class="bbtn bbtn-danger">
                <span class="material-symbols-outlined" style="font-size:16px;">picture_as_pdf</span> PDF
            </a>
        </div>
    </div>

    {{-- Filter Panel --}}
    <div class="bcard fu1" style="padding:20px;">
        <p style="font-size:13px;font-weight:700;color:var(--btxt);margin:0 0 16px;">Filter Periode</p>

        {{-- Tipe Filter --}}
        <div style="display:flex;flex-wrap:wrap;gap:16px;margin-bottom:16px;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                <input type="radio" wire:model.live="filterTipe" value="bulan" id="filter-bulan"
                       style="accent-color:var(--bp);" />
                <span style="font-size:13.5px;font-weight:500;color:var(--btxt);">Per Bulan</span>
            </label>
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                <input type="radio" wire:model.live="filterTipe" value="tahun" id="filter-tahun"
                       style="accent-color:var(--bp);" />
                <span style="font-size:13.5px;font-weight:500;color:var(--btxt);">Per Tahun</span>
            </label>
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                <input type="radio" wire:model.live="filterTipe" value="custom" id="filter-custom"
                       style="accent-color:var(--bp);" />
                <span style="font-size:13.5px;font-weight:500;color:var(--btxt);">Custom Range</span>
            </label>
        </div>

        @if($filterTipe === 'bulan')
        <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;">
            <div style="flex:1;min-width:130px;">
                <label class="blabel">Bulan</label>
                <select wire:model.live="filterBulan" id="select-bulan" class="binput">
                    @foreach($bulanList as $kode => $nama)
                        <option value="{{ $kode }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex:1;min-width:100px;">
                <label class="blabel">Tahun</label>
                <select wire:model.live="filterBulanTahun" id="select-bulan-tahun" class="binput">
                    @foreach($tahunList as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif

        @if($filterTipe === 'tahun')
        <div style="max-width:160px;">
            <label class="blabel">Tahun</label>
            <select wire:model.live="filterTahun" id="select-tahun" class="binput">
                @foreach($tahunList as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
        </div>
        @endif

        @if($filterTipe === 'custom')
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;">
            <div>
                <label for="tanggal-awal" class="blabel">Tanggal Awal</label>
                <input type="date" id="tanggal-awal" wire:model.live="tanggalAwal" class="binput" />
            </div>
            <div>
                <label for="tanggal-akhir" class="blabel">Tanggal Akhir</label>
                <input type="date" id="tanggal-akhir" wire:model.live="tanggalAkhir" class="binput" />
            </div>
        </div>
        @endif

        {{-- Filter Departemen & Status --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;margin-top:16px;padding-top:16px;border-top:1px solid var(--bbor);">
            <div>
                <label for="filter-dept" class="blabel">Filter Departemen</label>
                <select id="filter-dept" wire:model.live="filterDepartemen" class="binput">
                    <option value="">Semua Departemen</option>
                    @foreach($allDepartemens as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="filter-status" class="blabel">Filter Status</label>
                <select id="filter-status" wire:model.live="filterStatus" class="binput">
                    <option value="">Semua Status</option>
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="closed_pending_qa">Pending QA</option>
                    <option value="closed_acc">Closed ACC</option>
                </select>
            </div>
        </div>

        <p style="font-size:12px;color:var(--btxt2);margin-top:12px;">
            Menampilkan data: <strong style="color:var(--btxt);">
                {{ \Carbon\Carbon::parse($awal)->translatedFormat('d M Y') }} — {{ \Carbon\Carbon::parse($akhir)->translatedFormat('d M Y') }}
            </strong>
        </p>
    </div>

    {{-- Summary Stat Cards --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:12px;" class="fu2">
        <div class="bstat">
            <div class="bstat-icon" style="background:var(--bs-light);">
                <span class="material-symbols-outlined fil" style="color:var(--bs);font-size:24px;">assignment</span>
            </div>
            <div><div class="bstat-val">{{ $total }}</div><div class="bstat-lbl">Total</div></div>
        </div>
        <div class="bstat">
            <div class="bstat-icon" style="background:rgba(245, 158, 11, 0.15);">
                <span class="material-symbols-outlined fil" style="color:#f59e0b;font-size:24px;">error</span>
            </div>
            <div><div class="bstat-val" style="color:#f59e0b;">{{ $perStatus['open'] }}</div><div class="bstat-lbl">Open</div></div>
        </div>
        <div class="bstat">
            <div class="bstat-icon" style="background:var(--bp-light);">
                <span class="material-symbols-outlined fil" style="color:var(--bp);font-size:24px;">pending</span>
            </div>
            <div><div class="bstat-val" style="color:var(--bp);">{{ $perStatus['in_progress'] }}</div><div class="bstat-lbl">In Progress</div></div>
        </div>
        <div class="bstat">
            <div class="bstat-icon" style="background:var(--bs-light);">
                <span class="material-symbols-outlined fil" style="color:var(--bs);font-size:24px;">hourglass_top</span>
            </div>
            <div><div class="bstat-val" style="color:var(--bs);">{{ $perStatus['closed_pending_qa'] }}</div><div class="bstat-lbl">Pending QA</div></div>
        </div>
        <div class="bstat">
            <div class="bstat-icon" style="background:rgba(16, 185, 129, 0.15);">
                <span class="material-symbols-outlined fil" style="color:var(--success);font-size:24px;">task_alt</span>
            </div>
            <div><div class="bstat-val" style="color:var(--success);">{{ $perStatus['closed_acc'] }}</div><div class="bstat-lbl">Closed ACC</div></div>
        </div>
    </div>

    {{-- Breakdown Per Departemen --}}
    @if($perDepartemen->isNotEmpty())
    <div class="bcard fu3" style="overflow:hidden;">
        <div style="padding:16px 20px;border-bottom:1px solid var(--bbor);">
            <h3 style="font-size:14px;font-weight:700;color:var(--btxt);margin:0;">Breakdown per Departemen</h3>
        </div>
        <div style="overflow-x:auto;">
            <table class="btbl" style="min-width:500px;">
                <thead>
                    <tr>
                        <th>Departemen</th>
                        <th style="text-align:center;">Total</th>
                        <th style="text-align:center;color:#f59e0b;">Open</th>
                        <th style="text-align:center;color:var(--bp);">In Progress</th>
                        <th style="text-align:center;color:var(--bs);">Pending QA</th>
                        <th style="text-align:center;color:var(--success);">Closed ACC</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($perDepartemen as $row)
                    <tr>
                        <td style="font-weight:600;">{{ $row['nama'] }}</td>
                        <td style="text-align:center;font-weight:700;">{{ $row['total'] }}</td>
                        <td style="text-align:center;color:#f59e0b;font-weight:600;">{{ $row['open'] }}</td>
                        <td style="text-align:center;color:var(--bp);font-weight:600;">{{ $row['in_progress'] }}</td>
                        <td style="text-align:center;color:var(--bs);font-weight:600;">{{ $row['closed_pending_qa'] }}</td>
                        <td style="text-align:center;color:var(--success);font-weight:600;">{{ $row['closed_acc'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bcard fu3" style="padding:40px;text-align:center;">
        <span class="material-symbols-outlined" style="font-size:40px;opacity:.3;display:block;margin-bottom:8px;">inbox</span>
        <p style="color:var(--btxt2);font-size:13.5px;margin:0;">Tidak ada temuan pada periode yang dipilih.</p>
    </div>
    @endif

</div>
