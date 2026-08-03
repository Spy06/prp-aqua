<div style="display:flex;flex-direction:column;gap:24px;" class="fu" wire:poll.3s>
    <style>
        /* Card Abstract Circle Decorations — Persis SIVERA */
        .berry-stat {
            color: #fff !important;
            position: relative;
            overflow: hidden;
            border: none !important;
            border-radius: 12px;
            padding: 22px 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            transition: transform 0.25s, box-shadow 0.25s;
        }
        .berry-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }
        
        .stat-purple { background: linear-gradient(135deg, #4527a0 0%, #673ab7 100%); }
        .stat-purple::after {
            content: ""; position: absolute; width: 210px; height: 210px;
            background: #311b92; border-radius: 50%; top: -85px; right: -95px; opacity: 0.4;
        }
        .stat-purple::before {
            content: ""; position: absolute; width: 210px; height: 210px;
            background: #311b92; border-radius: 50%; top: -125px; right: -15px; opacity: 0.2;
        }

        .stat-red { background: linear-gradient(135deg, #c62828 0%, #ef5350 100%); }
        .stat-red::after {
            content: ""; position: absolute; width: 210px; height: 210px;
            background: #b71c1c; border-radius: 50%; top: -85px; right: -95px; opacity: 0.35;
        }
        .stat-red::before {
            content: ""; position: absolute; width: 210px; height: 210px;
            background: #b71c1c; border-radius: 50%; top: -125px; right: -15px; opacity: 0.18;
        }

        .stat-green { background: linear-gradient(135deg, #2e7d32 0%, #4caf50 100%); }
        .stat-green::after {
            content: ""; position: absolute; width: 210px; height: 210px;
            background: #1b5e20; border-radius: 50%; top: -85px; right: -95px; opacity: 0.4;
        }
        .stat-green::before {
            content: ""; position: absolute; width: 210px; height: 210px;
            background: #1b5e20; border-radius: 50%; top: -125px; right: -15px; opacity: 0.2;
        }

        .stat-crimson { background: linear-gradient(135deg, #880e4f 0%, #e91e63 100%); }
        .stat-crimson::after {
            content: ""; position: absolute; width: 210px; height: 210px;
            background: #4a148c; border-radius: 50%; top: -85px; right: -95px; opacity: 0.35;
        }
        .stat-crimson::before {
            content: ""; position: absolute; width: 210px; height: 210px;
            background: #4a148c; border-radius: 50%; top: -125px; right: -15px; opacity: 0.18;
        }

        .stat-blue { background: linear-gradient(135deg, #1565c0 0%, #2196f3 100%); }
        .stat-blue::after {
            content: ""; position: absolute; width: 210px; height: 210px;
            background: #0d47a1; border-radius: 50%; top: -85px; right: -95px; opacity: 0.4;
        }
        .stat-blue::before {
            content: ""; position: absolute; width: 210px; height: 210px;
            background: #0d47a1; border-radius: 50%; top: -125px; right: -15px; opacity: 0.2;
        }

        .stat-avatar {
            width: 44px; height: 44px;
            border-radius: 8px;
            background: rgba(255,255,255,0.22);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 14px;
            position: relative; z-index: 2;
        }
        .stat-avatar span { color: #fff; font-size: 24px; }
        .stat-content { position: relative; z-index: 2; }
        .stat-title { font-size: 13px; font-weight: 600; opacity: 0.9; margin: 0 0 4px; text-transform: uppercase; letter-spacing: 0.5px; }

        .berry-stat-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
        }
        @media (max-width: 1200px) {
            .berry-stat-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 768px) {
            .berry-stat-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        }
        @media (max-width: 480px) {
            .berry-stat-grid { grid-template-columns: 1fr; gap: 8px; }
        }

        /* 2x2 Symmetric Grid Layout */
        .charts-2x2-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        @media (max-width: 1024px) {
            .charts-2x2-grid { grid-template-columns: 1fr; gap: 16px; }
        }
    </style>
    
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
                'akhir' => $tgl_selesai
            ]) }}" class="bbtn bbtn-secondary bbtn-sm">
                <span class="material-symbols-outlined" style="font-size:18px;color:#2e7d32;">csv</span>
                Export Excel / CSV
            </a>
            <a href="{{ route('bosq.qa.export.pdf.dashboard', [
                'tipe' => $filter_type,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'awal' => $tgl_mulai,
                'akhir' => $tgl_selesai
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

    {{-- Top 5 Berry Stat Cards --}}
    <div class="berry-stat-grid fu2">
        <div class="berry-stat stat-purple">
            <div class="stat-avatar">
                <span class="material-symbols-outlined fil">visibility</span>
            </div>
            <div class="stat-content">
                <p class="stat-title">Total Observasi</p>
                <h3 style="font-size:32px;font-weight:800;margin:0;line-height:1;">{{ $totalTemuan }}</h3>
            </div>
        </div>

        <div class="berry-stat stat-red">
            <div class="stat-avatar">
                <span class="material-symbols-outlined fil">error</span>
            </div>
            <div class="stat-content">
                <p class="stat-title">Open</p>
                <h3 style="font-size:32px;font-weight:800;margin:0;line-height:1;">{{ $totalOpen }}</h3>
            </div>
        </div>

        <div class="berry-stat stat-green">
            <div class="stat-avatar">
                <span class="material-symbols-outlined fil">task_alt</span>
            </div>
            <div class="stat-content">
                <p class="stat-title">Closed</p>
                <h3 style="font-size:32px;font-weight:800;margin:0;line-height:1;">{{ $totalClosed }}</h3>
            </div>
        </div>

        <div class="berry-stat stat-crimson">
            <div class="stat-avatar">
                <span class="material-symbols-outlined fil">thumb_down</span>
            </div>
            <div class="stat-content">
                <p class="stat-title">Negatif</p>
                <h3 style="font-size:32px;font-weight:800;margin:0;line-height:1;">{{ $totalNegatif }}</h3>
            </div>
        </div>

        <div class="berry-stat stat-blue">
            <div class="stat-avatar">
                <span class="material-symbols-outlined fil">thumb_up</span>
            </div>
            <div class="stat-content">
                <p class="stat-title">Positif</p>
                <h3 style="font-size:32px;font-weight:800;margin:0;line-height:1;">{{ $totalPositif }}</h3>
            </div>
        </div>
    </div>

    {{-- Charts 2x2 Symmetric Grid --}}
    <div class="charts-2x2-grid fu2">
        
        {{-- Chart 1: Temuan per Departemen --}}
        <div class="bcard fu" style="padding:24px; display:flex; flex-direction:column; justify-content:space-between;"
             data-labels="{{ json_encode(array_keys($chartDeptData)) }}"
             data-values="{{ json_encode(array_values($chartDeptData)) }}"
             x-data="{
                 init() {
                     if (typeof Chart === 'undefined') return;
                     if (typeof ChartDataLabels !== 'undefined') {
                         Chart.register(ChartDataLabels);
                     }
                     const labels = JSON.parse(this.$el.dataset.labels);
                     const data = JSON.parse(this.$el.dataset.values);
                     
                     let chart = new Chart(this.$refs.canvas, {
                         type: 'bar',
                         data: {
                             labels: labels,
                             datasets: [{
                                 label: 'Jumlah Observasi',
                                 data: data,
                                 backgroundColor: '#8b5cf6',
                                 borderColor: '#7c3aed',
                                 borderWidth: 0,
                                 borderRadius: 8,
                                 maxBarThickness: 18
                             }]
                         },
                         options: {
                             responsive: true, maintainAspectRatio: false,
                             layout: { padding: { top: 20 } },
                             plugins: {
                                 tooltip: {
                                     callbacks: {
                                         title: function(ctx) { return ctx[0].label || ''; }
                                     }
                                 },
                                 legend: { display: false },
                                 datalabels: { anchor: 'end', align: 'top', color: 'var(--btxt)', font: { weight: 'bold', family: 'Inter', size: 11 }, formatter: val => val > 0 ? val : '' }
                             },
                             scales: {
                                 y: { beginAtZero: true, ticks: { stepSize: 1, font: { family: 'Inter' } }, grid: { drawBorder: false, color: 'rgba(0,0,0,0.05)' } },
                                 x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 10, weight: '600' }, autoSkip: false, maxRotation: 45, minRotation: 0 } }
                             }
                         }
                     });
                     this.$wire.on('bosq-chart-updated', (event) => {
                         const payload = Array.isArray(event) ? event[0] : event;
                         if (chart && payload && payload.deptData) {
                             chart.data.labels = Object.keys(payload.deptData);
                             chart.data.datasets[0].data = Object.values(payload.deptData);
                             chart.update();
                         }
                     });
                 }
             }"
             wire:ignore>
            <p style="font-size:12px;font-weight:700;color:var(--btxt2);text-transform:uppercase;letter-spacing:.8px;margin:0 0 16px;">Temuan per Departemen</p>
            <div style="position:relative; width:100%; height:320px; flex:1; min-width:0;"><canvas x-ref="canvas"></canvas></div>
        </div>

        {{-- Chart 2: Status Doughnut (SHADCN Donut Chart Replica) --}}
        <div class="bcard fu1" style="padding:24px; display:flex; flex-direction:column; align-items:center;"
             data-labels="{{ json_encode(array_keys($chartStatusData)) }}"
             data-values="{{ json_encode(array_values($chartStatusData)) }}"
             x-data="{
                 statusLabels: [],
                 statusData: [],
                 colors: ['#c62828', '#2e7d32'],
                 hiddenSegments: [],
                 hoveredIndex: null,
                 activeLabel: 'Total Observasi',
                 activeValue: 0,
                 activePercentage: null,
                 totalValue: 0,
                 size: 200,
                 strokeWidth: 26,
                 
                 init() {
                     this.statusLabels = JSON.parse(this.$el.dataset.labels);
                     this.statusData = JSON.parse(this.$el.dataset.values);
                     this.recalc();

                     this.$wire.on('bosq-chart-updated', (event) => {
                         const payload = Array.isArray(event) ? event[0] : event;
                         if (payload && payload.statusData) {
                             this.statusLabels = Object.keys(payload.statusData);
                             this.statusData = Object.values(payload.statusData);
                             this.hiddenSegments = [];
                             this.recalc();
                         }
                     });
                 },
                 
                 recalc() {
                     let sum = 0;
                     this.statusData.forEach((val, i) => {
                         if (!this.hiddenSegments.includes(i)) {
                             sum += val;
                         }
                     });
                     this.totalValue = sum;
                     this.activeValue = sum;
                     this.activeLabel = 'Total Observasi';
                     this.activePercentage = null;
                 },
                 
                 get radius() {
                     return this.size / 2 - this.strokeWidth / 2;
                 },
                 
                 get circumference() {
                     return 2 * Math.PI * this.radius;
                 },
                 
                 getSegmentProps(idx) {
                     const val = this.statusData[idx];
                     const isHidden = this.hiddenSegments.includes(idx);
                     
                     if (val === 0 || isHidden || this.totalValue === 0) {
                         return {
                             visible: false,
                             color: 'transparent',
                             dasharray: '0 1000',
                             dashoffset: 0
                         };
                     }
                     
                     let cumulativePercentage = 0;
                     for (let i = 0; i < idx; i++) {
                         const precedingVal = this.statusData[i];
                         const precedingHidden = this.hiddenSegments.includes(i);
                         if (precedingVal > 0 && !precedingHidden) {
                             cumulativePercentage += (precedingVal / this.totalValue) * 100;
                         }
                     }
                     
                     const percentage = (val / this.totalValue) * 100;
                     const dasharray = `${(percentage / 100) * this.circumference} ${this.circumference}`;
                     const dashoffset = -((cumulativePercentage / 100) * this.circumference);
                     
                     return {
                         visible: true,
                         color: this.colors[idx] || '#1976d2',
                         dasharray: dasharray,
                         dashoffset: dashoffset
                     };
                 },
                 
                 toggleSegment(idx) {
                     if (this.hiddenSegments.includes(idx)) {
                         this.hiddenSegments = this.hiddenSegments.filter(i => i !== idx);
                     } else {
                         this.hiddenSegments.push(idx);
                     }
                     this.recalc();
                 },
                 
                 hoverSegment(idx) {
                     if (idx !== null && !this.hiddenSegments.includes(idx)) {
                         this.hoveredIndex = idx;
                         this.activeLabel = this.statusLabels[idx];
                         this.activeValue = this.statusData[idx];
                         this.activePercentage = this.totalValue > 0 ? ((this.activeValue / this.totalValue) * 100).toFixed(0) : 0;
                     } else {
                         this.hoveredIndex = null;
                         this.activeLabel = 'Total Observasi';
                         this.activeValue = this.totalValue;
                         this.activePercentage = null;
                     }
                 }
             }"
             wire:ignore>
            
            <p style="font-size:12px;font-weight:700;color:var(--btxt2);text-transform:uppercase;letter-spacing:.8px;margin:0 0 16px; width:100%;">Proporsi Status BQA</p>
            
            <div style="position:relative; width:200px; height:200px; margin-bottom:16px;">
                <svg width="200" height="200" viewBox="0 0 200 200" class="overflow-visible -rotate-90">
                    <circle cx="100" cy="100" :r="radius" fill="transparent" stroke="var(--bbor)" :stroke-width="strokeWidth" style="opacity: 0.35;"></circle>
                    
                    <circle cx="100" cy="100" :r="radius"
                            fill="none" pointer-events="stroke"
                            :stroke="getSegmentProps(0).color"
                            :stroke-width="strokeWidth"
                            :stroke-dasharray="getSegmentProps(0).dasharray"
                            :stroke-dashoffset="getSegmentProps(0).dashoffset"
                            stroke-linecap="round"
                            class="transition-all duration-300 origin-center cursor-pointer"
                            :style="{
                                filter: hoveredIndex === 0 ? 'drop-shadow(0px 0px 6px ' + colors[0] + ') brightness(1.1)' : 'none',
                                transform: hoveredIndex === 0 ? 'scale(1.04)' : 'scale(1)',
                                transition: 'filter 0.2s ease-out, transform 0.2s ease-out, stroke-dasharray 0.3s ease-out, stroke-dashoffset 0.3s ease-out',
                                display: getSegmentProps(0).visible ? 'inline' : 'none'
                            }"
                            @mouseenter="hoverSegment(0)"
                            @mouseleave="hoverSegment(null)">
                    </circle>

                    <circle cx="100" cy="100" :r="radius"
                            fill="none" pointer-events="stroke"
                            :stroke="getSegmentProps(1).color"
                            :stroke-width="strokeWidth"
                            :stroke-dasharray="getSegmentProps(1).dasharray"
                            :stroke-dashoffset="getSegmentProps(1).dashoffset"
                            stroke-linecap="round"
                            class="transition-all duration-300 origin-center cursor-pointer"
                            :style="{
                                filter: hoveredIndex === 1 ? 'drop-shadow(0px 0px 6px ' + colors[1] + ') brightness(1.1)' : 'none',
                                transform: hoveredIndex === 1 ? 'scale(1.04)' : 'scale(1)',
                                transition: 'filter 0.2s ease-out, transform 0.2s ease-out, stroke-dasharray 0.3s ease-out, stroke-dashoffset 0.3s ease-out',
                                display: getSegmentProps(1).visible ? 'inline' : 'none'
                            }"
                            @mouseenter="hoverSegment(1)"
                            @mouseleave="hoverSegment(null)">
                    </circle>
                </svg>
                
                <div style="position: absolute; top: 68px; left: 0; width: 200px; text-align: center; pointer-events: none; z-index: 10; display: block;">
                    <div style="transition: transform 0.2s ease-out;"
                         :style="hoveredIndex !== null ? 'transform: scale(1.04);' : 'transform: scale(1);'">
                        <p style="font-size: 11px; color: var(--btxt2); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 auto; max-width: 150px; text-align: center;" class="truncate" x-text="activeLabel"></p>
                        <p style="font-size: 32px; font-weight: 700; color: var(--btxt); margin: 2px 0 0; line-height: 1; text-align: center;" x-text="activeValue"></p>
                        <div x-show="activePercentage !== null" style="margin-top: 4px; text-align: center;">
                            <span style="font-size: 12px; font-weight: 600; color: var(--btxt2); text-align: center;" x-text="'[' + activePercentage + '%]'"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display:flex; flex-direction:column; gap:4px; width:100%; padding-top:16px; border-top:1px solid var(--bbor);">
                <template x-for="(label, index) in statusLabels">
                    <div class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-150"
                         :style="{
                             opacity: hiddenSegments.includes(index) ? '0.4' : '1',
                             background: hoveredIndex === index ? 'var(--bsur)' : 'transparent'
                         }"
                         @click="toggleSegment(index)"
                         @mouseenter="hoverSegment(index)"
                         @mouseleave="hoverSegment(null)">
                        <div class="flex items-center gap-2.5">
                            <span class="w-3 h-3 rounded-full transition-transform duration-150"
                                  :style="{
                                      backgroundColor: colors[index],
                                      transform: hoveredIndex === index ? 'scale(1.25)' : 'scale(1)'
                                  }"></span>
                            <span class="text-xs font-semibold" style="color: var(--btxt);" x-text="label"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold" style="color: var(--btxt);" x-text="statusData[index]"></span>
                            <span class="text-[10px] font-medium text-slate-400" x-text="totalValue > 0 ? '(' + ((statusData[index] / totalValue) * 100).toFixed(0) + '%)' : '(0%)'"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Chart 3: Temuan Negatif vs Positif per Departemen (Grouped Bar Chart) --}}
        <div class="bcard fu" style="padding:24px; display:flex; flex-direction:column; justify-content:space-between;"
             data-labels="{{ json_encode($chartDampakLabels) }}"
             data-negatif="{{ json_encode($chartDampakNegatif) }}"
             data-positif="{{ json_encode($chartDampakPositif) }}"
             x-data="{
                 init() {
                     if (typeof Chart === 'undefined') return;
                     if (typeof ChartDataLabels !== 'undefined') {
                         Chart.register(ChartDataLabels);
                     }
                     const labels = JSON.parse(this.$el.dataset.labels);
                     const negatifData = JSON.parse(this.$el.dataset.negatif);
                     const positifData = JSON.parse(this.$el.dataset.positif);
                     
                     let chart = new Chart(this.$refs.canvasDampak, {
                         type: 'bar',
                         data: {
                             labels: labels,
                             datasets: [
                                 {
                                     label: 'Negatif (Butuh Perbaikan)',
                                     data: negatifData,
                                     backgroundColor: '#ef4444',
                                     borderColor: '#dc2626',
                                     borderRadius: 8,
                                     borderSkipped: false,
                                     maxBarThickness: 14
                                 },
                                 {
                                     label: 'Positif (Perilaku Baik)',
                                     data: positifData,
                                     backgroundColor: '#3b82f6',
                                     borderColor: '#2563eb',
                                     borderRadius: 8,
                                     borderSkipped: false,
                                     maxBarThickness: 14
                                 }
                             ]
                         },
                         options: {
                             responsive: true,
                             maintainAspectRatio: false,
                             plugins: {
                                 legend: {
                                     position: 'bottom',
                                     labels: {
                                         font: { family: 'Inter', size: 11 },
                                         usePointStyle: true,
                                         pointStyle: 'rectRounded'
                                     }
                                 },
                                 datalabels: {
                                     anchor: 'end',
                                     align: 'top',
                                     color: 'var(--btxt)',
                                     font: { weight: 'bold', family: 'Inter', size: 11 },
                                     formatter: (val) => val > 0 ? val : ''
                                 }
                             },
                             scales: {
                                 y: { beginAtZero: true, ticks: { stepSize: 1, font: { family: 'Inter' } }, grid: { color: 'rgba(0,0,0,0.05)' } },
                                 x: { ticks: { font: { family: 'Inter', size: 10, weight: '600' } }, grid: { display: false } }
                             }
                         }
                     });

                     this.$wire.on('bosq-chart-updated', (evt) => {
                         const payload = Array.isArray(evt) ? evt[0] : evt;
                         if (chart && payload && payload.dampakLabels) {
                             chart.data.labels = payload.dampakLabels;
                             chart.data.datasets[0].data = payload.dampakNegatifData;
                             chart.data.datasets[1].data = payload.dampakPositifData;
                             chart.update();
                         }
                     });
                 }
             }"
             wire:ignore>
            <p style="font-size:12px;font-weight:700;color:var(--btxt2);text-transform:uppercase;letter-spacing:.8px;margin:0 0 16px;">Temuan Negatif vs Positif per Departemen</p>
            <div style="position:relative; width:100%; height:320px; flex:1; min-width:0;"><canvas x-ref="canvasDampak"></canvas></div>
        </div>

        {{-- Chart 4: Observasi tiap Sub Area --}}
        <div class="bcard fu3" style="padding:24px; display:flex; flex-direction:column; justify-content:space-between;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;gap:12px;flex-wrap:wrap;">
                <p style="font-size:12px;font-weight:700;color:var(--btxt2);text-transform:uppercase;letter-spacing:.8px;margin:0;">Observasi tiap Sub Area</p>
                <select wire:model.live="filterDepartemenSubArea" class="binput" style="width:auto;padding:6px 12px;font-size:12.5px;">
                    @foreach($departemens as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                    @endforeach
                </select>
            </div>
            <div style="position:relative; width:100%; height:320px; flex:1; min-width:0;"
                 data-labels="{{ $subAreaLabels }}"
                 data-values="{{ $subAreaData }}"
                 x-data="{
                     init() {
                         if (typeof Chart === 'undefined') return;
                         if (typeof ChartDataLabels !== 'undefined') {
                             Chart.register(ChartDataLabels);
                         }
                         const labels = JSON.parse(this.$el.dataset.labels);
                         const data = JSON.parse(this.$el.dataset.values);
                         
                         let chart = new Chart(this.$refs.canvas, {
                             type: 'bar',
                             data: {
                                 labels: labels,
                                 datasets: [{
                                     label: 'Jumlah Observasi',
                                     data: data,
                                     backgroundColor: '#f59e0b',
                                     borderColor: '#d97706',
                                     borderWidth: 0,
                                     borderRadius: 8,
                                     maxBarThickness: 18
                                 }]
                             },
                             options: {
                                 responsive: true, maintainAspectRatio: false,
                                 layout: { padding: { top: 20 } },
                                 plugins: {
                                     tooltip: {
                                         callbacks: {
                                             title: function(ctx) { return ctx[0].label || ''; }
                                         }
                                     },
                                     legend: { display: false },
                                     datalabels: { anchor: 'end', align: 'top', color: 'var(--btxt)', font: { weight: 'bold', family: 'Inter', size: 11 }, formatter: val => val > 0 ? val : '' }
                                 },
                                 scales: {
                                     y: { beginAtZero: true, ticks: { stepSize: 1, font: { family: 'Inter' } }, grid: { drawBorder: false, color: 'rgba(0,0,0,0.05)' } },
                                     x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 10, weight: '600' }, autoSkip: false, maxRotation: 45, minRotation: 0 } }
                                 }
                             }
                         });
                         this.$wire.on('bosq-chart-updated', (event) => {
                             const payload = Array.isArray(event) ? event[0] : event;
                             if (chart && payload && payload.subAreaLabels) {
                                 chart.data.labels = JSON.parse(payload.subAreaLabels);
                                 chart.data.datasets[0].data = JSON.parse(payload.subAreaData);
                                 chart.update();
                             }
                         });
                     }
                 }"
                 wire:ignore>
                <canvas x-ref="canvas"></canvas>
            </div>
        </div>

        {{-- Chart 5: Temuan per Elemen QFS --}}
        <div class="bcard fu3" style="padding:24px; display:flex; flex-direction:column; justify-content:space-between; grid-column: 1 / -1;">
            <p style="font-size:12px;font-weight:700;color:var(--btxt2);text-transform:uppercase;letter-spacing:.8px;margin:0 0 16px;">Temuan per Elemen QFS</p>
            <div style="position:relative; width:100%; height:320px; flex:1; min-width:0;"
                 data-labels="{{ $elemenLabels }}"
                 data-values="{{ $elemenData }}"
                 x-data="{
                     init() {
                         if (typeof Chart === 'undefined') return;
                         if (typeof ChartDataLabels !== 'undefined') {
                             Chart.register(ChartDataLabels);
                         }
                         const labels = JSON.parse(this.$el.dataset.labels);
                         const data = JSON.parse(this.$el.dataset.values);
                         
                         let chart = new Chart(this.$refs.canvasElemen, {
                             type: 'bar',
                             data: {
                                 labels: labels,
                                 datasets: [{
                                     label: 'Jumlah Observasi',
                                     data: data,
                                     backgroundColor: '#3b82f6',
                                     borderColor: '#2563eb',
                                     borderWidth: 0,
                                     borderRadius: 8,
                                     maxBarThickness: 18
                                 }]
                             },
                             options: {
                                 responsive: true, maintainAspectRatio: false,
                                 layout: { padding: { top: 20 } },
                                 plugins: {
                                     tooltip: {
                                         callbacks: {
                                             title: function(ctx) { return ctx[0].label || ''; }
                                         }
                                     },
                                     legend: { display: false },
                                     datalabels: { anchor: 'end', align: 'top', color: 'var(--btxt)', font: { weight: 'bold', family: 'Inter', size: 11 }, formatter: val => val > 0 ? val : '' }
                                 },
                                 scales: {
                                     y: { beginAtZero: true, ticks: { stepSize: 1, font: { family: 'Inter' } }, grid: { drawBorder: false, color: 'rgba(0,0,0,0.05)' } },
                                     x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 10, weight: '600' }, autoSkip: false, maxRotation: 45, minRotation: 0 } }
                                 }
                             }
                         });
                         this.$wire.on('bosq-chart-updated', (event) => {
                             const payload = Array.isArray(event) ? event[0] : event;
                             if (chart && payload && payload.elemenLabels) {
                                 chart.data.labels = JSON.parse(payload.elemenLabels);
                                 chart.data.datasets[0].data = JSON.parse(payload.elemenData);
                                 chart.update();
                             }
                         });
                     }
                 }"
                 wire:ignore>
                <canvas x-ref="canvasElemen"></canvas>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js" data-navigate-once></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0" data-navigate-once></script>
</div>
