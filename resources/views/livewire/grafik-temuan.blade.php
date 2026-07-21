<div style="display:flex;flex-direction:column;gap:24px;" class="fu" wire:poll.3s>
    <style>
        /* Card Abstract Circle Decorations */
        .berry-stat {
            color: #fff !important;
            position: relative;
            overflow: hidden;
            border: none !important;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
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

        .stat-blue { background: linear-gradient(135deg, #f57c00 0%, #ffc107 100%); }
        .stat-blue::after {
            content: ""; position: absolute; width: 210px; height: 210px;
            background: #e65100; border-radius: 50%; top: -85px; right: -95px; opacity: 0.4;
        }
        .stat-blue::before {
            content: ""; position: absolute; width: 210px; height: 210px;
            background: #e65100; border-radius: 50%; top: -125px; right: -15px; opacity: 0.2;
        }

        .stat-orange { background: linear-gradient(135deg, #c62828 0%, #f44336 100%); }
        .stat-orange::after {
            content: ""; position: absolute; width: 210px; height: 210px;
            background: #b71c1c; border-radius: 50%; top: -85px; right: -95px; opacity: 0.35;
        }
        .stat-orange::before {
            content: ""; position: absolute; width: 210px; height: 210px;
            background: #b71c1c; border-radius: 50%; top: -125px; right: -15px; opacity: 0.18;
        }

        .stat-green { background: linear-gradient(135deg, #00c853 0%, #4caf50 100%); }
        .stat-green::after {
            content: ""; position: absolute; width: 210px; height: 210px;
            background: #004d40; border-radius: 50%; top: -85px; right: -95px; opacity: 0.4;
        }
        .stat-green::before {
            content: ""; position: absolute; width: 210px; height: 210px;
            background: #004d40; border-radius: 50%; top: -125px; right: -15px; opacity: 0.2;
        }
        
        .stat-cyan { background: linear-gradient(135deg, #1565c0 0%, #2196f3 100%); }
        .stat-cyan::after {
            content: ""; position: absolute; width: 210px; height: 210px;
            background: #0d47a1; border-radius: 50%; top: -85px; right: -95px; opacity: 0.4;
        }
        .stat-cyan::before {
            content: ""; position: absolute; width: 210px; height: 210px;
            background: #0d47a1; border-radius: 50%; top: -125px; right: -15px; opacity: 0.2;
        }

        .stat-avatar {
            width: 44px; height: 44px;
            border-radius: 8px;
            background: rgba(255,255,255,0.22);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 16px;
            position: relative; z-index: 2;
        }
        .stat-avatar span { color: #fff; font-size: 24px; }
        .stat-content { position: relative; z-index: 2; }
        .stat-title { font-size: 13.5px; font-weight: 500; opacity: 0.85; margin: 0 0 6px; }
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

        /* 2x2 Symmetric Responsive Grid Layout */
        .charts-2x2-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        .charts-2x2-grid > div {
            min-width: 0;
        }

        .chart-card-box {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            min-height: 420px;
        }

        @media (max-width: 991.98px) {
            .charts-2x2-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .chart-card-box {
                min-height: auto;
            }
        }
    </style>

    {{-- Stat Cards Grid (Berry style gradient blocks) --}}
    <div class="berry-stat-grid">
        <div class="berry-stat stat-purple fu">
            <div class="stat-avatar">
                <span class="material-symbols-outlined fil">assignment</span>
            </div>
            <div class="stat-content">
                <p class="stat-title">Total Temuan</p>
                <h3 class="stat-num">{{ $totalTemuan }}</h3>
            </div>
        </div>
        <div class="berry-stat stat-orange fu1">
            <div class="stat-avatar">
                <span class="material-symbols-outlined fil">error</span>
            </div>
            <div class="stat-content">
                <p class="stat-title">Open</p>
                <h3 class="stat-num">{{ $totalOpen }}</h3>
            </div>
        </div>
        <div class="berry-stat stat-blue fu2">
            <div class="stat-avatar">
                <span class="material-symbols-outlined fil">pending</span>
            </div>
            <div class="stat-content">
                <p class="stat-title">In Progress</p>
                <h3 class="stat-num">{{ $totalInProgress }}</h3>
            </div>
        </div>
        <div class="berry-stat stat-cyan fu3">
            <div class="stat-avatar">
                <span class="material-symbols-outlined fil">hourglass_top</span>
            </div>
            <div class="stat-content">
                <p class="stat-title">Pending QA</p>
                <h3 class="stat-num">{{ $totalPendingQa }}</h3>
            </div>
        </div>
        <div class="berry-stat stat-green fu4">
            <div class="stat-avatar">
                <span class="material-symbols-outlined fil">task_alt</span>
            </div>
            <div class="stat-content">
                <p class="stat-title">Closed (ACC)</p>
                <h3 class="stat-num">{{ $totalClosedAcc }}</h3>
            </div>
        </div>
    </div>

    {{-- Charts 2x2 Grid (2 Atas, 2 Bawah) --}}
    <div class="charts-2x2-grid">

        {{-- Chart: Departemen --}}
        <div class="bcard fu" style="padding:24px; display:flex; flex-direction:column; justify-content:space-between;"
             data-labels="{{ $chartLabels }}"
             data-values="{{ $chartData }}"
             x-data="{
                 init() {
                     const rawLabels = JSON.parse(this.$el.dataset.labels);
                     const labels = rawLabels.map(l => (typeof l === 'string' && l.includes(' ')) ? l.split(' ') : l);
                     const data = JSON.parse(this.$el.dataset.values);
                     Chart.register(ChartDataLabels);
                     let chart = new Chart(this.$refs.canvas, {
                         type: 'bar',
                         data: {
                             labels: labels,
                             datasets: [{
                                 label: 'Jumlah Temuan',
                                 data: data,
                                 backgroundColor: 'rgba(103,58,183,0.7)',
                                 borderColor: 'rgb(103,58,183)',
                                 borderWidth: 0,
                                 borderRadius: 8,
                                 barThickness: 24
                             }]
                         },
                         options: {
                             responsive: true, maintainAspectRatio: false,
                             layout: { padding: { top: 20 } },
                             plugins: {
                                 tooltip: {
                                     callbacks: {
                                         title: function(ctx) { return (ctx[0].label || '').replace(/,/g, ' '); }
                                     }
                                 },
                                 legend: { display: false },
                                 datalabels: { anchor: 'end', align: 'top', color: 'var(--btxt)', font: { weight: 'bold', family: 'Inter', size: window.innerWidth < 768 ? 7.5 : 9 } }
                             },
                             scales: {
                                 y: { beginAtZero: true, ticks: { stepSize: 1, font: { family: 'Inter' } }, grid: { drawBorder: false, color: 'rgba(0,0,0,0.05)' } },
                                 x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 10 }, autoSkip: false, maxRotation: 45, minRotation: 0 } }
                             }
                         }
                     });
                     this.$wire.on('chart-updated', (event) => {
                         const payload = Array.isArray(event) ? event[0] : event;
                         if (chart && payload) {
                             const newLabels = JSON.parse(payload.deptLabels);
                             chart.data.labels = newLabels.map(l => (typeof l === 'string' && l.includes(' ')) ? l.split(' ') : l);
                             chart.data.datasets[0].data = JSON.parse(payload.deptData);
                             chart.options.scales.y.suggestedMax = Math.max(...chart.data.datasets[0].data) * 1.2;
                             chart.update();
                         }
                     });
                 }
             }"
             wire:ignore>
            <p style="font-size:12px;font-weight:700;color:var(--btxt2);text-transform:uppercase;letter-spacing:.8px;margin:0 0 16px;">Temuan per Departemen</p>
            <div style="position:relative; width:100%; height:320px; flex:1; min-width:0;"><canvas x-ref="canvas"></canvas></div>
        </div>

        {{-- Chart: Status Doughnut (SHADCN Donut Chart Replica) --}}
        <div class="bcard fu1" style="padding:24px; display:flex; flex-direction:column; align-items:center;"
             data-labels="{{ $statusLabels }}"
             data-values="{{ $statusData }}"
             x-data="{
                 statusLabels: [],
                 statusData: [],
                 colors: ['#f44336', '#ffc107', '#2196f3', '#00c853'],
                 hiddenSegments: [],
                 hoveredIndex: null,
                 activeLabel: 'Total Temuan',
                 activeValue: 0,
                 activePercentage: null,
                 totalValue: 0,
                 size: 200,
                 strokeWidth: 26,
                 
                 init() {
                     this.statusLabels = JSON.parse(this.$el.dataset.labels);
                     this.statusData = JSON.parse(this.$el.dataset.values);
                     this.recalc();

                     this.$wire.on('chart-updated', (event) => {
                         const payload = Array.isArray(event) ? event[0] : event;
                         if (payload) {
                             this.statusLabels = JSON.parse(payload.statusLabels);
                             this.statusData = JSON.parse(payload.statusData);
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
                     this.activeLabel = 'Total Temuan';
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
                     
                     // Calculate cumulative percentage of preceding visible segments
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
                         color: this.colors[idx],
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
                         this.activeLabel = 'Total Temuan';
                         this.activeValue = this.totalValue;
                         this.activePercentage = null;
                     }
                 }
             }"
             wire:ignore>
            
            <p style="font-size:12px;font-weight:700;color:var(--btxt2);text-transform:uppercase;letter-spacing:.8px;margin:0 0 16px; width:100%;">Proporsi Status Temuan</p>
            
            <div style="position:relative; width:200px; height:200px; margin-bottom:16px;">
                {{-- Native SVG Donut Chart with static circles mapping to Alpine props --}}
                <svg width="200" height="200" viewBox="0 0 200 200" class="overflow-visible -rotate-90">
                    <!-- Base background ring -->
                    <circle cx="100" cy="100" :r="radius" fill="transparent" stroke="var(--bbor)" :stroke-width="strokeWidth" style="opacity: 0.35;"></circle>
                    
                    <!-- Circle 0: Open -->
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

                    <!-- Circle 1: In Progress -->
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

                    <!-- Circle 2: Pending QA -->
                    <circle cx="100" cy="100" :r="radius"
                            fill="none" pointer-events="stroke"
                            :stroke="getSegmentProps(2).color"
                            :stroke-width="strokeWidth"
                            :stroke-dasharray="getSegmentProps(2).dasharray"
                            :stroke-dashoffset="getSegmentProps(2).dashoffset"
                            stroke-linecap="round"
                            class="transition-all duration-300 origin-center cursor-pointer"
                            :style="{
                                filter: hoveredIndex === 2 ? 'drop-shadow(0px 0px 6px ' + colors[2] + ') brightness(1.1)' : 'none',
                                transform: hoveredIndex === 2 ? 'scale(1.04)' : 'scale(1)',
                                transition: 'filter 0.2s ease-out, transform 0.2s ease-out, stroke-dasharray 0.3s ease-out, stroke-dashoffset 0.3s ease-out',
                                display: getSegmentProps(2).visible ? 'inline' : 'none'
                            }"
                            @mouseenter="hoverSegment(2)"
                            @mouseleave="hoverSegment(null)">
                    </circle>

                    <!-- Circle 3: Closed (ACC) -->
                    <circle cx="100" cy="100" :r="radius"
                            fill="none" pointer-events="stroke"
                            :stroke="getSegmentProps(3).color"
                            :stroke-width="strokeWidth"
                            :stroke-dasharray="getSegmentProps(3).dasharray"
                            :stroke-dashoffset="getSegmentProps(3).dashoffset"
                            stroke-linecap="round"
                            class="transition-all duration-300 origin-center cursor-pointer"
                            :style="{
                                filter: hoveredIndex === 3 ? 'drop-shadow(0px 0px 6px ' + colors[3] + ') brightness(1.1)' : 'none',
                                transform: hoveredIndex === 3 ? 'scale(1.04)' : 'scale(1)',
                                transition: 'filter 0.2s ease-out, transform 0.2s ease-out, stroke-dasharray 0.3s ease-out, stroke-dashoffset 0.3s ease-out',
                                display: getSegmentProps(3).visible ? 'inline' : 'none'
                            }"
                            @mouseenter="hoverSegment(3)"
                            @mouseleave="hoverSegment(null)">
                    </circle>
                </svg>
                
                {{-- Dynamic Center Content --}}
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

            {{-- Custom Interactive Legend --}}
            <div style="display:flex; flex-direction:column; gap:4px; width:100%; padding-top:16px; border-top:1px solid var(--bbor);">
                <template x-for="(label, index) in statusLabels">
                    <div class="flex items-center justify-between p-2 rounded-lg cursor-pointer transition-all duration-150"
                         style="background: transparent;"
                         :style="[
                             hoveredIndex === index ? { background: 'var(--bs-light)' } : {},
                             hiddenSegments.includes(index) ? { opacity: 0.4 } : {}
                         ]"
                         @mouseenter="hoverSegment(index)"
                         @mouseleave="hoverSegment(null)"
                         @click="toggleSegment(index)">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span style="width:8px; height:8px; border-radius:50%; display:inline-block;" 
                                  :style="{'background-color': colors[index]}"></span>
                            <span style="font-size:12.5px; font-weight:600; color:var(--btxt);"
                                  :style="hiddenSegments.includes(index) ? { 'text-decoration': 'line-through' } : {}"
                                  x-text="label"></span>
                        </div>
                        <span style="font-size:12.5px; font-weight:700; color:var(--btxt2);"
                              :style="hiddenSegments.includes(index) ? { 'text-decoration': 'line-through' } : {}"
                              x-text="statusData[index]"></span>
                    </div>
                </template>
            </div>
        </div>

        {{-- Chart: Klausul --}}
        <div class="bcard fu2" style="padding:24px; display:flex; flex-direction:column; justify-content:space-between;"
             data-labels="{{ $klausulLabels }}"
             data-values="{{ $klausulData }}"
             x-data="{
                 init() {
                     const rawLabels = JSON.parse(this.$el.dataset.labels);
                     const labels = rawLabels.map(l => (typeof l === 'string' && l.includes(' ')) ? l.split(' ') : l);
                     const data = JSON.parse(this.$el.dataset.values);
                     Chart.register(ChartDataLabels);
                     let chart = new Chart(this.$refs.canvas, {
                         type: 'bar',
                         data: {
                             labels: labels,
                             datasets: [{
                                 label: 'Jumlah Temuan',
                                 data: data,
                                 backgroundColor: 'rgba(33,150,243,0.7)',
                                 borderColor: 'rgb(33,150,243)',
                                 borderWidth: 0,
                                 borderRadius: 8,
                                 barThickness: 14
                             }]
                         },
                         options: {
                             responsive: true, maintainAspectRatio: false,
                             layout: { padding: { top: 20 } },
                             plugins: {
                                 tooltip: {
                                     callbacks: {
                                         title: function(ctx) { return (ctx[0].label || '').replace(/,/g, ' '); }
                                     }
                                 },
                                 legend: { display: false },
                                 datalabels: { anchor: 'end', align: 'top', color: 'var(--btxt)', font: { weight: 'bold', family: 'Inter', size: window.innerWidth < 768 ? 7.5 : 9 } }
                             },
                             scales: {
                                 y: { beginAtZero: true, ticks: { stepSize: 1, font: { family: 'Inter' } }, grid: { drawBorder: false, color: 'rgba(0,0,0,0.05)' } },
                                 x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 10 }, autoSkip: false, maxRotation: 45, minRotation: 0 } }
                             }
                         }
                     });
                     this.$wire.on('chart-updated', (event) => {
                         const payload = Array.isArray(event) ? event[0] : event;
                         if (chart && payload) {
                             const newLabels = JSON.parse(payload.klausulLabels);
                             chart.data.labels = newLabels.map(l => (typeof l === 'string' && l.includes(' ')) ? l.split(' ') : l);
                             chart.data.datasets[0].data = JSON.parse(payload.klausulData);
                             chart.options.scales.y.suggestedMax = Math.max(...chart.data.datasets[0].data) * 1.2;
                             chart.update();
                         }
                     });
                 }
             }"
             wire:ignore>
            <p style="font-size:12px;font-weight:700;color:var(--btxt2);text-transform:uppercase;letter-spacing:.8px;margin:0 0 16px;">Temuan per Klausul PRP</p>
            <div style="position:relative; width:100%; height:320px; flex:1; min-width:0;"><canvas x-ref="canvas"></canvas></div>
        </div>

        {{-- Chart: Sub Area --}}
        <div class="bcard fu3" style="padding:24px; display:flex; flex-direction:column; justify-content:space-between;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;gap:12px;flex-wrap:wrap;">
                <p style="font-size:12px;font-weight:700;color:var(--btxt2);text-transform:uppercase;letter-spacing:.8px;margin:0;">Temuan tiap Sub Area</p>
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
                         const rawLabels = JSON.parse(this.$el.dataset.labels);
                         const labels = rawLabels.map(l => (typeof l === 'string' && l.includes(' ')) ? l.split(' ') : l);
                         const data = JSON.parse(this.$el.dataset.values);
                         Chart.register(ChartDataLabels);
                         let chart = new Chart(this.$refs.canvas, {
                             type: 'bar',
                             data: {
                                 labels: labels,
                                 datasets: [{
                                     label: 'Jumlah Temuan',
                                     data: data,
                                     backgroundColor: 'rgba(255,152,0,0.7)',
                                     borderColor: 'rgb(255,152,0)',
                                     borderWidth: 0,
                                     borderRadius: 8,
                                     barThickness: 24
                                 }]
                             },
                             options: {
                                 responsive: true, maintainAspectRatio: false,
                                 layout: { padding: { top: 20 } },
                                 plugins: {
                                     tooltip: {
                                         callbacks: {
                                             title: function(ctx) { return (ctx[0].label || '').replace(/,/g, ' '); }
                                         }
                                     },
                                     legend: { display: false },
                                     datalabels: { anchor: 'end', align: 'top', color: 'var(--btxt)', font: { weight: 'bold', family: 'Inter', size: window.innerWidth < 768 ? 7.5 : 9 } }
                                 },
                                 scales: {
                                     y: { beginAtZero: true, ticks: { stepSize: 1, font: { family: 'Inter' } }, grid: { drawBorder: false, color: 'rgba(0,0,0,0.05)' } },
                                     x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 10 }, autoSkip: false, maxRotation: 45, minRotation: 0 } }
                                 }
                             }
                         });
                         this.$wire.on('chart-updated', (event) => {
                             const payload = Array.isArray(event) ? event[0] : event;
                             if (chart && payload) {
                                 const newLabels = JSON.parse(payload.subAreaLabels);
                                 chart.data.labels = newLabels.map(l => (typeof l === 'string' && l.includes(' ')) ? l.split(' ') : l);
                                 chart.data.datasets[0].data = JSON.parse(payload.subAreaData);
                                 chart.options.scales.y.suggestedMax = Math.max(...chart.data.datasets[0].data) * 1.2;
                                 chart.update();
                             }
                         });
                     }
                 }"
                 wire:ignore>
                <canvas x-ref="canvas"></canvas>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js" data-navigate-once></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0" data-navigate-once></script>
</div>
