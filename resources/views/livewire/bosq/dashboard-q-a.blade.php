<div style="display:flex;flex-direction:column;gap:24px;" class="fu">
    
    {{-- Header & Title --}}
    <div class="bph fu1">
        <div>
            <h2 class="bph-title">Dashboard Analisis QA — BOS'Q</h2>
            <p class="bph-sub">Hasil analisis kepatuhan dan observasi perilaku mutu (Periode: <strong>{{ $filterLabel }}</strong>)</p>
        </div>
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('bosq.qa.export.csv', [
                'tipe' => $filter_type,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'awal' => $tgl_mulai,
                'akhir' => $tgl_selesai,
                'departemen_id' => $filter_departemen_id,
                'status' => $filter_status,
                'tingkat_resiko' => $filter_tingkat_resiko,
                'dampak_temuan' => $filter_dampak_temuan
            ]) }}" class="bbtn bbtn-secondary bbtn-sm">
                <span class="material-symbols-outlined" style="font-size:18px;color:#2e7d32;">csv</span>
                Export Excel / CSV
            </a>
            <a href="{{ route('bosq.qa.export.pdf.dashboard', [
                'tipe' => $filter_type,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'awal' => $tgl_mulai,
                'akhir' => $tgl_selesai,
                'departemen_id' => $filter_departemen_id,
                'status' => $filter_status,
                'tingkat_resiko' => $filter_tingkat_resiko,
                'dampak_temuan' => $filter_dampak_temuan
            ]) }}" target="_blank" class="bbtn bbtn-primary bbtn-sm">
                <span class="material-symbols-outlined" style="font-size:18px;">picture_as_pdf</span>
                Export PDF Dashboard
            </a>
        </div>
    </div>

    {{-- Filter Periode Card --}}
    <div class="bcard fu1" style="padding:16px 20px;">
        <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:8px;">
                <span class="material-symbols-outlined" style="color:var(--bp-dark);font-size:20px;">filter_alt</span>
                <span style="font-size:13px;font-weight:700;color:var(--btxt);">Filter Periode:</span>
            </div>

            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;flex:1;">
                <select wire:model.live="filter_type" class="binput" style="width:auto;padding:7px 12px;font-size:13px;">
                    <option value="bulan">Per Bulan</option>
                    <option value="tahun">Per Tahun</option>
                    <option value="custom">Rentang Tanggal Custom</option>
                </select>

                @if($filter_type === 'bulan')
                    <select wire:model.live="bulan" class="binput" style="width:auto;padding:7px 12px;font-size:13px;">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}">{{ \Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}</option>
                        @endfor
                    </select>

                    <select wire:model.live="tahun" class="binput" style="width:auto;padding:7px 12px;font-size:13px;">
                        @for($y = now()->year; $y >= 2024; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                @elseif($filter_type === 'tahun')
                    <select wire:model.live="tahun" class="binput" style="width:auto;padding:7px 12px;font-size:13px;">
                        @for($y = now()->year; $y >= 2024; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                @elseif($filter_type === 'custom')
                    <div style="display:flex;align-items:center;gap:6px;">
                        <input type="date" wire:model.live="tgl_mulai" class="binput" style="width:auto;padding:6px 10px;font-size:12.5px;">
                        <span style="font-size:12px;color:var(--btxt2);">s/d</span>
                        <input type="date" wire:model.live="tgl_selesai" class="binput" style="width:auto;padding:6px 10px;font-size:12.5px;">
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Stat Summary Cards --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:14px;" class="fu2">
        <div class="bstat">
            <div class="bstat-icon" style="background:#e3f2fd;">
                <span class="material-symbols-outlined fil" style="color:#1565c0;font-size:22px;">visibility</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#1565c0;">{{ $totalTemuan }}</div>
                <div class="bstat-lbl">Total Observasi</div>
            </div>
        </div>

        <div class="bstat">
            <div class="bstat-icon" style="background:#fff3e0;">
                <span class="material-symbols-outlined fil" style="color:#e65100;font-size:22px;">error</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#e65100;">{{ $totalOpen }}</div>
                <div class="bstat-lbl">Status Open</div>
            </div>
        </div>

        <div class="bstat">
            <div class="bstat-icon" style="background:#e8f5e9;">
                <span class="material-symbols-outlined fil" style="color:#2e7d32;font-size:22px;">task_alt</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#2e7d32;">{{ $totalClosed }}</div>
                <div class="bstat-lbl">Status Closed</div>
            </div>
        </div>

        <div class="bstat">
            <div class="bstat-icon" style="background:#ffebee;">
                <span class="material-symbols-outlined fil" style="color:#c62828;font-size:22px;">thumb_down</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#c62828;">{{ $totalNegatif }}</div>
                <div class="bstat-lbl">Dampak Negatif</div>
            </div>
        </div>

        <div class="bstat">
            <div class="bstat-icon" style="background:#eff6ff;">
                <span class="material-symbols-outlined fil" style="color:#2563eb;font-size:22px;">thumb_up</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#2563eb;">{{ $totalPositif }}</div>
                <div class="bstat-lbl">Dampak Positif</div>
            </div>
        </div>
    </div>

    {{-- 3 Mandatory Charts Grid --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));gap:16px;" class="fu2">
        
        {{-- Chart 1: Status BQA (Open vs Closed) --}}
        <div class="bcard" style="padding:20px;display:flex;flex-direction:column;justify-content:space-between;">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                    <span class="material-symbols-outlined" style="color:#e65100;font-size:20px;">donut_small</span>
                    <h3 style="font-size:14px;font-weight:700;color:var(--btxt);margin:0;">a. Status BQA (Open vs Closed)</h3>
                </div>
            </div>
            <div style="position:relative;width:100%;height:250px;display:flex;align-items:center;justify-content:center;"
                 data-labels="{{ json_encode(array_keys($chartStatusData)) }}"
                 data-values="{{ json_encode(array_values($chartStatusData)) }}"
                 x-data="{
                     init() {
                         const labels = JSON.parse(this.$el.dataset.labels);
                         const data = JSON.parse(this.$el.dataset.values);
                         new Chart(this.$refs.canvasStatus, {
                             type: 'doughnut',
                             data: {
                                 labels: labels,
                                 datasets: [{
                                     data: data,
                                     backgroundColor: ['#e65100', '#2e7d32'],
                                     borderWidth: 2,
                                     borderColor: '#ffffff'
                                 }]
                             },
                             options: {
                                 responsive: true, maintainAspectRatio: false,
                                 plugins: {
                                     legend: { position: 'bottom', labels: { font: { family: 'Inter', size: 12 } } },
                                     datalabels: { color: '#ffffff', font: { weight: 'bold', family: 'Inter', size: 13 } }
                                 }
                             }
                         });
                     }
                 }">
                <canvas x-ref="canvasStatus"></canvas>
            </div>
        </div>

        {{-- Chart 2: Temuan Per Departemen (Bar Chart) --}}
        <div class="bcard" style="padding:20px;display:flex;flex-direction:column;justify-content:space-between;">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                    <span class="material-symbols-outlined" style="color:var(--bp);font-size:20px;">bar_chart</span>
                    <h3 style="font-size:14px;font-weight:700;color:var(--btxt);margin:0;">b. Temuan per Departemen</h3>
                </div>
            </div>
            <div style="position:relative;width:100%;height:250px;"
                 data-labels="{{ json_encode(array_keys($chartDeptData)) }}"
                 data-values="{{ json_encode(array_values($chartDeptData)) }}"
                 x-data="{
                     init() {
                         const labels = JSON.parse(this.$el.dataset.labels);
                         const data = JSON.parse(this.$el.dataset.values);
                         new Chart(this.$refs.canvasDept, {
                             type: 'bar',
                             data: {
                                 labels: labels,
                                 datasets: [{
                                     label: 'Jumlah Observasi',
                                     data: data,
                                     backgroundColor: 'rgba(25, 118, 210, 0.75)',
                                     borderColor: '#1976d2',
                                     borderRadius: 6
                                 }]
                             },
                             options: {
                                 responsive: true, maintainAspectRatio: false,
                                 plugins: {
                                     legend: { display: false },
                                     datalabels: { anchor: 'end', align: 'top', color: 'var(--btxt)', font: { weight: 'bold', family: 'Inter', size: 11 } }
                                 },
                                 scales: {
                                     y: { beginAtZero: true, ticks: { stepSize: 1, font: { family: 'Inter' } } },
                                     x: { ticks: { font: { family: 'Inter', size: 10 } } }
                                 }
                             }
                         });
                     }
                 }">
                <canvas x-ref="canvasDept"></canvas>
            </div>
        </div>

        {{-- Chart 3: Temuan Negatif vs Positif --}}
        <div class="bcard" style="padding:20px;display:flex;flex-direction:column;justify-content:space-between;">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                    <span class="material-symbols-outlined" style="color:#2563eb;font-size:20px;">pie_chart</span>
                    <h3 style="font-size:14px;font-weight:700;color:var(--btxt);margin:0;">c. Temuan Negatif vs Positif</h3>
                </div>
            </div>
            <div style="position:relative;width:100%;height:250px;display:flex;align-items:center;justify-content:center;"
                 data-labels="{{ json_encode(array_keys($chartDampakData)) }}"
                 data-values="{{ json_encode(array_values($chartDampakData)) }}"
                 x-data="{
                     init() {
                         const labels = JSON.parse(this.$el.dataset.labels);
                         const data = JSON.parse(this.$el.dataset.values);
                         new Chart(this.$refs.canvasDampak, {
                             type: 'pie',
                             data: {
                                 labels: labels,
                                 datasets: [{
                                     data: data,
                                     backgroundColor: ['#ef4444', '#2563eb'],
                                     borderWidth: 2,
                                     borderColor: '#ffffff'
                                 }]
                             },
                             options: {
                                 responsive: true, maintainAspectRatio: false,
                                 plugins: {
                                     legend: { position: 'bottom', labels: { font: { family: 'Inter', size: 12 } } },
                                     datalabels: { color: '#ffffff', font: { weight: 'bold', family: 'Inter', size: 13 } }
                                 }
                             }
                         });
                     }
                 }">
                <canvas x-ref="canvasDampak"></canvas>
            </div>
        </div>

    </div>

    {{-- Tabel Semua Temuan dengan Filter --}}
    <div class="bcard fu3">
        <div class="bcard-header" style="justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div class="bcard-hicon" style="background:var(--bp-light);">
                    <span class="material-symbols-outlined fil" style="color:var(--bp-dark);font-size:20px;">list_alt</span>
                </div>
                <div>
                    <h3 style="font-size:15px;font-weight:700;color:var(--btxt);margin:0;">Daftar Observasi BOS'Q</h3>
                    <p style="font-size:12px;color:var(--btxt2);margin:0;">Kelola dan tinjau seluruh data observasi yang tercatat</p>
                </div>
            </div>
        </div>

        {{-- Filter Table Header --}}
        <div style="padding:16px 20px;border-bottom:1px solid var(--bbor);background:var(--bsur);display:grid;grid-template-columns:repeat(auto-fit, minmax(160px, 1fr));gap:10px;">
            <div>
                <label class="blabel" style="font-size:10.5px;margin-bottom:4px;">Departemen</label>
                <select wire:model.live="filter_departemen_id" class="binput" style="padding:6px 10px;font-size:12px;">
                    <option value="">Semua Departemen</option>
                    @foreach($departemens as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="blabel" style="font-size:10.5px;margin-bottom:4px;">Sub Area</label>
                <select wire:model.live="filter_sub_area_id" class="binput" style="padding:6px 10px;font-size:12px;">
                    <option value="">Semua Sub Area</option>
                    @foreach($subAreas as $sa)
                        <option value="{{ $sa->id }}">{{ $sa->nama_sub_area }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="blabel" style="font-size:10.5px;margin-bottom:4px;">Status</label>
                <select wire:model.live="filter_status" class="binput" style="padding:6px 10px;font-size:12px;">
                    <option value="">Semua Status</option>
                    <option value="open">Open</option>
                    <option value="closed">Closed</option>
                </select>
            </div>

            <div>
                <label class="blabel" style="font-size:10.5px;margin-bottom:4px;">Tingkat Risiko</label>
                <select wire:model.live="filter_tingkat_resiko" class="binput" style="padding:6px 10px;font-size:12px;">
                    <option value="">Semua Risiko</option>
                    <option value="food_safety_risk">Food Safety Risk</option>
                    <option value="major_quality_risk">Major Quality Risk</option>
                    <option value="minor_quality_risk">Minor Quality Risk</option>
                </select>
            </div>

            <div>
                <label class="blabel" style="font-size:10.5px;margin-bottom:4px;">Dampak Observasi</label>
                <select wire:model.live="filter_dampak_temuan" class="binput" style="padding:6px 10px;font-size:12px;">
                    <option value="">Semua Dampak</option>
                    <option value="negatif">Negatif</option>
                    <option value="positif">Positif</option>
                </select>
            </div>
        </div>

        {{-- Table Content --}}
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;text-align:left;">
                <thead>
                    <tr style="background:var(--bsur);border-bottom:1px solid var(--bbor);color:var(--btxt2);font-size:11px;text-transform:uppercase;letter-spacing:0.5px;">
                        <th style="padding:12px 16px;">ID / Tanggal</th>
                        <th style="padding:12px 16px;">Departemen & Sub Area</th>
                        <th style="padding:12px 16px;">Observer & Auditee</th>
                        <th style="padding:12px 16px;">Risiko & Dampak</th>
                        <th style="padding:12px 16px;">Status</th>
                        <th style="padding:12px 16px;text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($temuans as $t)
                        @php
                            $isClosed = in_array($t->status, ['closed', 'closed_acc']);
                            $statusBadgeClass = $isClosed ? 'sbadge-closed' : 'sbadge-open';
                            $statusText = $isClosed ? 'CLOSED' : 'OPEN';
                        @endphp
                        <tr style="border-bottom:1px solid var(--bbor);transition:background 0.15s;" onmouseover="this.style.background='var(--bsur)'" onmouseout="this.style.background='none'">
                            <td style="padding:12px 16px;">
                                <div style="font-weight:700;color:var(--bp);">#{{ $t->id }}</div>
                                <div style="font-size:11.5px;color:var(--btxt2);">{{ $t->tanggal_temuan->format('d/m/Y') }}</div>
                            </td>
                            <td style="padding:12px 16px;">
                                <div style="font-weight:600;color:var(--btxt);">{{ $t->departemen->nama_departemen ?? '-' }}</div>
                                <div style="font-size:12px;color:var(--btxt2);">
                                    {{ $t->subArea->nama_sub_area ?? '-' }}
                                    @if($t->detail_sub_area) <span>({{ $t->detail_sub_area }})</span> @endif
                                </div>
                            </td>
                            <td style="padding:12px 16px;">
                                <div style="font-size:12.5px;"><strong>Observer:</strong> {{ $t->pelapor->name ?? '-' }}</div>
                                <div style="font-size:12px;color:var(--btxt2);"><strong>Auditee:</strong> {{ $t->auditee->name ?? '-' }}</div>
                            </td>
                            <td style="padding:12px 16px;">
                                <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
                                    <span style="font-size:10px;font-weight:700;padding:2px 6px;border-radius:4px;background:var(--bsur);border:1px solid var(--bbor);text-transform:uppercase;">
                                        {{ str_replace('_', ' ', $t->tingkat_resiko) }}
                                    </span>
                                    <span style="font-size:10px;font-weight:700;padding:2px 6px;border-radius:4px;{{ $t->dampak_temuan === 'negatif' ? 'background:#ffebee;color:#c62828;' : 'background:#eff6ff;color:#2563eb;' }}">
                                        {{ strtoupper($t->dampak_temuan) }}
                                    </span>
                                </div>
                            </td>
                            <td style="padding:12px 16px;">
                                <span class="sbadge {{ $statusBadgeClass }}">{{ $statusText }}</span>
                            </td>
                            <td style="padding:12px 16px;text-align:center;">
                                <a href="{{ route('bosq.temuan.detail', $t->id) }}" class="bbtn bbtn-secondary bbtn-sm" style="text-decoration:none;">
                                    <span class="material-symbols-outlined" style="font-size:16px;">visibility</span>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:36px;text-align:center;color:var(--btxt2);">
                                Belum ada data observasi yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($temuans->hasPages())
            <div style="padding:16px 20px;border-top:1px solid var(--bbor);">
                {{ $temuans->links() }}
            </div>
        @endif
    </div>

</div>
