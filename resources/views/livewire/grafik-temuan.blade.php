<div class="space-y-4 sm:space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
        {{-- Card: Chart Departemen (Row 1, Col 1) --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-5"
             data-labels="{{ $chartLabels }}"
             data-values="{{ $chartData }}"
             x-data="{
                 init() {
                     const labels = JSON.parse(this.$el.dataset.labels);
                     const data = JSON.parse(this.$el.dataset.values);
                     Chart.register(ChartDataLabels);
                     let chart = new Chart(this.$refs.canvas, {
                         type: 'bar',
                         data: {
                             labels: labels,
                             datasets: [{
                                 label: 'Jumlah Temuan',
                                 data: data,
                                 backgroundColor: 'rgba(59, 130, 246, 0.5)',
                                 borderColor: 'rgb(59, 130, 246)',
                                 borderWidth: 1
                             }]
                         },
                         options: {
                             responsive: true,
                             maintainAspectRatio: false,
                             layout: {
                                 padding: {
                                     top: 20
                                 }
                             },
                             plugins: {
                                 legend: { display: false },
                                 datalabels: {
                                     anchor: 'end',
                                     align: 'top',
                                     color: 'rgb(59, 130, 246)',
                                     font: { weight: 'bold' }
                                 }
                             },
                             scales: {
                                 y: { beginAtZero: true, ticks: { stepSize: 1 }, suggestedMax: Math.max(...data) * 1.2 }
                             }
                         }
                     });

                     this.$wire.on('chart-updated', (event) => {
                         const payload = Array.isArray(event) ? event[0] : event;
                         if (chart && payload) {
                             chart.data.labels = JSON.parse(payload.deptLabels);
                             chart.data.datasets[0].data = JSON.parse(payload.deptData);
                             chart.options.scales.y.suggestedMax = Math.max(...chart.data.datasets[0].data) * 1.2;
                             chart.update();
                         }
                     });
                 }
             }"
             wire:ignore>
            <h2 class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-4">Temuan per Departemen</h2>
            <div class="w-full h-64">
                <canvas x-ref="canvas"></canvas>
            </div>
        </div>

        {{-- Card: Chart Status (Row 1, Col 2) --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-5"
             data-labels="{{ $statusLabels }}"
             data-values="{{ $statusData }}"
             x-data="{
                 init() {
                     const labels = JSON.parse(this.$el.dataset.labels);
                     const data = JSON.parse(this.$el.dataset.values);
                     Chart.register(ChartDataLabels);
                     let chart = new Chart(this.$refs.canvas, {
                         type: 'doughnut',
                         data: {
                             labels: labels,
                             datasets: [{
                                 data: data,
                                 backgroundColor: [
                                     'rgba(239, 68, 68, 0.7)', // Red for open
                                     'rgba(250, 204, 21, 0.7)', // Yellow for in progress
                                     'rgba(59, 130, 246, 0.7)',  // Blue for pending qa
                                     'rgba(34, 197, 94, 0.7)'    // Green for closed acc
                                 ],
                                 borderColor: [
                                     'rgb(239, 68, 68)',
                                     'rgb(250, 204, 21)',
                                     'rgb(59, 130, 246)',
                                     'rgb(34, 197, 94)'
                                 ],
                                 borderWidth: 1
                             }]
                         },
                         options: {
                             responsive: true,
                             maintainAspectRatio: false,
                             plugins: {
                                 legend: {
                                     position: 'bottom',
                                     labels: { boxWidth: 12, padding: 8 }
                                 },
                                 datalabels: {
                                     color: '#fff',
                                     font: { weight: 'bold' },
                                     formatter: (value, ctx) => {
                                         return value > 0 ? value : '';
                                     }
                                 }
                             }
                         }
                     });

                     this.$wire.on('chart-updated', (event) => {
                         const payload = Array.isArray(event) ? event[0] : event;
                         if (chart && payload) {
                             chart.data.labels = JSON.parse(payload.statusLabels);
                             chart.data.datasets[0].data = JSON.parse(payload.statusData);
                             chart.update();
                         }
                     });
                 }
             }"
             wire:ignore>
            <h2 class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-4">Proporsi Status Temuan</h2>
            <div class="w-full h-64">
                <canvas x-ref="canvas"></canvas>
            </div>
        </div>

        {{-- Card: Chart Klausul (Row 2, Col 1) --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-5"
             data-labels="{{ $klausulLabels }}"
             data-values="{{ $klausulData }}"
             x-data="{
                 init() {
                     const labels = JSON.parse(this.$el.dataset.labels);
                     const data = JSON.parse(this.$el.dataset.values);
                     Chart.register(ChartDataLabels);
                     let chart = new Chart(this.$refs.canvas, {
                         type: 'bar',
                         data: {
                             labels: labels,
                             datasets: [{
                                 label: 'Jumlah Temuan',
                                 data: data,
                                 backgroundColor: 'rgba(139, 92, 246, 0.5)',
                                 borderColor: 'rgb(139, 92, 246)',
                                 borderWidth: 1
                             }]
                         },
                         options: {
                             responsive: true,
                             maintainAspectRatio: false,
                             layout: {
                                 padding: {
                                     top: 20
                                 }
                             },
                             plugins: {
                                 legend: { display: false },
                                 datalabels: {
                                     anchor: 'end',
                                     align: 'top',
                                     color: 'rgb(139, 92, 246)',
                                     font: { weight: 'bold' }
                                 }
                             },
                             scales: {
                                 y: { beginAtZero: true, ticks: { stepSize: 1 }, suggestedMax: Math.max(...data) * 1.2 }
                             }
                         }
                     });

                     this.$wire.on('chart-updated', (event) => {
                         const payload = Array.isArray(event) ? event[0] : event;
                         if (chart && payload) {
                             chart.data.labels = JSON.parse(payload.klausulLabels);
                             chart.data.datasets[0].data = JSON.parse(payload.klausulData);
                             chart.options.scales.y.suggestedMax = Math.max(...chart.data.datasets[0].data) * 1.2;
                             chart.update();
                         }
                     });
                 }
             }"
             wire:ignore>
            <h2 class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-4">Temuan per Klausul PRP</h2>
            <div class="w-full h-64">
                <canvas x-ref="canvas"></canvas>
            </div>
        </div>

        {{-- Card: Chart Sub Area (Row 2, Col 2) --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-5">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
                <h2 class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Temuan tiap Sub Area</h2>
                <select wire:model.live="filterDepartemenSubArea" class="w-full sm:w-auto rounded-md border border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs px-2 py-1">
                    @foreach($departemens as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="w-full h-64"
                 data-labels="{{ $subAreaLabels }}"
                 data-values="{{ $subAreaData }}"
                 x-data="{
                     init() {
                         const labels = JSON.parse(this.$el.dataset.labels);
                         const data = JSON.parse(this.$el.dataset.values);
                         Chart.register(ChartDataLabels);
                         let chart = new Chart(this.$refs.canvas, {
                             type: 'bar',
                             data: {
                                 labels: labels,
                                 datasets: [{
                                     label: 'Jumlah Temuan',
                                     data: data,
                                     backgroundColor: 'rgba(249, 115, 22, 0.5)',
                                     borderColor: 'rgb(249, 115, 22)',
                                     borderWidth: 1
                                 }]
                             },
                             options: {
                                 responsive: true,
                                 maintainAspectRatio: false,
                                 layout: {
                                     padding: {
                                         top: 20
                                     }
                                 },
                                 plugins: {
                                     legend: { display: false },
                                     datalabels: {
                                         anchor: 'end',
                                         align: 'top',
                                         color: 'rgb(249, 115, 22)',
                                         font: { weight: 'bold' }
                                     }
                                 },
                                 scales: {
                                     y: { beginAtZero: true, ticks: { stepSize: 1 }, suggestedMax: Math.max(...data) * 1.2 }
                                 }
                             }
                         });
    
                         this.$wire.on('chart-updated', (event) => {
                             const payload = Array.isArray(event) ? event[0] : event;
                             if (chart && payload) {
                                 chart.data.labels = JSON.parse(payload.subAreaLabels);
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
