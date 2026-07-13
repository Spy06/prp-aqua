<div class="space-y-6">
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6" wire:ignore>
        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Grafik Temuan per Departemen</h2>
        <div class="w-full h-64">
            <canvas id="temuanChart"></canvas>
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
                                <a href="{{ route('temuan.detail', $t->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Detail</a>
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
            const ctx = document.getElementById('temuanChart');
            let chart;

            const initChart = (labels, data) => {
                if (chart) {
                    chart.destroy();
                }
                chart = new Chart(ctx, {
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
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            };

            // Initial render
            let labels = {!! $chartLabels !!};
            let data = {!! $chartData !!};
            initChart(labels, data);

            Livewire.on('chart-updated', (event) => {
                let newLabels = JSON.parse(event[0].labels);
                let newData = JSON.parse(event[0].data);
                initChart(newLabels, newData);
            });
        });
    </script>
</div>
