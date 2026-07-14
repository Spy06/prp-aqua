<div class="space-y-6">
    {{-- Grid Grafik --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Card: Chart Departemen --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-5" wire:ignore>
            <h2 class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-4">Temuan per Departemen</h2>
            <div class="w-full h-64">
                <canvas id="temuanChart"></canvas>
            </div>
        </div>

        {{-- Card: Chart Klausul --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-5" wire:ignore>
            <h2 class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-4">Temuan per Klausul PRP</h2>
            <div class="w-full h-64">
                <canvas id="klausulChart"></canvas>
            </div>
        </div>

        {{-- Card: Chart Status --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-5" wire:ignore>
            <h2 class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-4">Proporsi Status Temuan</h2>
            <div class="w-full h-64">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Daftar Semua Temuan</h2>
            
            <div class="flex gap-2">
                <select wire:model.live="filterDepartemen" class="rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Semua Departemen</option>
                    @foreach($departemens as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                    @endforeach
                </select>

                <select wire:model.live="filterStatus" class="rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Semua Status</option>
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="closed_pending_qa">Pending QA</option>
                    <option value="closed_acc">Closed (ACC)</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Tgl Temuan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Departemen & Area</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">PIC</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($temuans as $t)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                {{ $t->tanggal_temuan->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                <div class="font-medium">{{ $t->departemen->nama_departemen ?? '-' }}</div>
                                <div class="text-xs text-zinc-500">{{ $t->sub_area }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                {{ $t->pic->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('temuan.detail', $t->id) }}"
                                       class="text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Detail</a>
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
        <div class="mt-4">
            {{ $temuans->links() }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            const ctxDept = document.getElementById('temuanChart');
            const ctxKlausul = document.getElementById('klausulChart');
            const ctxStatus = document.getElementById('statusChart');
            let chartDept, chartKlausul, chartStatus;

            const initDeptChart = (labels, data) => {
                if (chartDept) chartDept.destroy();
                chartDept = new Chart(ctxDept, {
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
            };

            const initKlausulChart = (labels, data) => {
                if (chartKlausul) chartKlausul.destroy();
                chartKlausul = new Chart(ctxKlausul, {
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
            };

            const initStatusChart = (labels, data) => {
                if (chartStatus) chartStatus.destroy();
                chartStatus = new Chart(ctxStatus, {
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
            };

            // Initial render
            initDeptChart({!! $chartLabels !!}, {!! $chartData !!});
            initKlausulChart({!! $klausulLabels !!}, {!! $klausulData !!});
            initStatusChart({!! $statusLabels !!}, {!! $statusData !!});

            Livewire.on('chart-updated', (event) => {
                const payload = event[0];
                initDeptChart(JSON.parse(payload.deptLabels), JSON.parse(payload.deptData));
                initKlausulChart(JSON.parse(payload.klausulLabels), JSON.parse(payload.klausulData));
                initStatusChart(JSON.parse(payload.statusLabels), JSON.parse(payload.statusData));
            });
        });
    </script>
</div>
