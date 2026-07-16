<div class="space-y-4 sm:space-y-6">
    {{-- Grid Grafik --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
        {{-- Card: Chart Departemen --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-5"
             data-labels="{{ $chartLabels }}"
             data-values="{{ $chartData }}"
             x-data="{
                 init() {
                     const labels = JSON.parse(this.$el.dataset.labels);
                     const data = JSON.parse(this.$el.dataset.values);
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
                             plugins: {
                                 legend: { display: false }
                             },
                             scales: {
                                 y: { beginAtZero: true, ticks: { stepSize: 1 } }
                             }
                         }
                     });

                     this.$wire.on('chart-updated', (event) => {
                         const payload = Array.isArray(event) ? event[0] : event;
                         if (chart && payload) {
                             chart.data.labels = JSON.parse(payload.deptLabels);
                             chart.data.datasets[0].data = JSON.parse(payload.deptData);
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

        {{-- Card: Chart Klausul --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-5"
             data-labels="{{ $klausulLabels }}"
             data-values="{{ $klausulData }}"
             x-data="{
                 init() {
                     const labels = JSON.parse(this.$el.dataset.labels);
                     const data = JSON.parse(this.$el.dataset.values);
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
                             plugins: {
                                 legend: { display: false }
                             },
                             scales: {
                                 y: { beginAtZero: true, ticks: { stepSize: 1 } }
                             }
                         }
                     });

                     this.$wire.on('chart-updated', (event) => {
                         const payload = Array.isArray(event) ? event[0] : event;
                         if (chart && payload) {
                             chart.data.labels = JSON.parse(payload.klausulLabels);
                             chart.data.datasets[0].data = JSON.parse(payload.klausulData);
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

        {{-- Card: Chart Status --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-5"
             data-labels="{{ $statusLabels }}"
             data-values="{{ $statusData }}"
             x-data="{
                 init() {
                     const labels = JSON.parse(this.$el.dataset.labels);
                     const data = JSON.parse(this.$el.dataset.values);
                     let chart = new Chart(this.$refs.canvas, {
                         type: 'doughnut',
                         data: {
                             labels: labels,
                             datasets: [{
                                 data: data,
                                 backgroundColor: [
                                     'rgba(250, 204, 21, 0.7)', // Yellow for open
                                     'rgba(59, 130, 246, 0.7)',  // Blue for in progress
                                     'rgba(168, 85, 247, 0.7)',  // Purple for pending qa
                                     'rgba(34, 197, 94, 0.7)'    // Green for closed acc
                                 ],
                                 borderColor: [
                                     'rgb(250, 204, 21)',
                                     'rgb(59, 130, 246)',
                                     'rgb(168, 85, 247)',
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
    </div>

    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4 sm:mb-6">
            <h2 class="text-base sm:text-lg font-semibold text-zinc-900 dark:text-zinc-100">Daftar Semua Temuan</h2>
            
            <div class="flex flex-col xs:flex-row gap-2 w-full sm:w-auto">
                <select wire:model.live="filterDepartemen" class="w-full sm:w-auto rounded-md border border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-2 py-1.5">
                    <option value="">Semua Departemen</option>
                    @foreach($departemens as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                    @endforeach
                </select>

                <select wire:model.live="filterStatus" class="w-full sm:w-auto rounded-md border border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-2 py-1.5">
                    <option value="">Semua Status</option>
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="closed_pending_qa">Pending QA</option>
                    <option value="closed_acc">Closed (ACC)</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto -mx-4 sm:mx-0">
            <div class="min-w-[600px] sm:min-w-0">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Tgl Temuan</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Departemen & Area</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">PIC</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Status</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($temuans as $t)
                        <tr>
                            <td class="px-3 sm:px-6 py-3 whitespace-nowrap text-xs sm:text-sm text-zinc-900 dark:text-zinc-100">
                                {{ $t->tanggal_temuan->format('d M Y') }}
                            </td>
                            <td class="px-3 sm:px-6 py-3 text-xs sm:text-sm text-zinc-900 dark:text-zinc-100">
                                <div class="font-medium">{{ $t->departemen->nama_departemen ?? '-' }}</div>
                                <div class="text-xs text-zinc-500">{{ $t->sub_area }}</div>
                            </td>
                            <td class="px-3 sm:px-6 py-3 whitespace-nowrap text-xs sm:text-sm text-zinc-900 dark:text-zinc-100">
                                {{ $t->pic->name ?? '-' }}
                            </td>
                            <td class="px-3 sm:px-6 py-3 whitespace-nowrap text-xs sm:text-sm">
                                @php
                                    $statusClass = match($t->status) {
                                        'open'              => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'in_progress'       => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                        'closed_pending_qa' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
                                        'closed_acc'        => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                        default             => 'bg-gray-100 text-gray-800',
                                    };
                                    $statusText = match($t->status) {
                                        'open'              => 'Open',
                                        'in_progress'       => 'In Progress',
                                        'closed_pending_qa' => 'Pending QA',
                                        'closed_acc'        => 'Closed (ACC)',
                                        default             => $t->status,
                                    };
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-6 py-3 whitespace-nowrap text-xs sm:text-sm text-zinc-900 dark:text-zinc-100">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('temuan.detail', $t->id) }}"
                                       class="text-indigo-600 dark:text-indigo-400 hover:underline text-xs">Detail</a>
                                    <span class="text-zinc-300 dark:text-zinc-600">|</span>
                                    <a href="{{ route('export.pdf.temuan', $t->id) }}"
                                       target="_blank"
                                       title="Export PDF Temuan #{{ $t->id }}"
                                       class="text-red-600 dark:text-red-400 hover:underline text-xs">PDF</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-zinc-500">Tidak ada data temuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
        <div class="mt-4">
            {{ $temuans->links('vendor.pagination.tailwind') }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js" data-navigate-once></script>
</div>
