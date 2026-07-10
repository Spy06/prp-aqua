<div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 mt-6">
    <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-6">Daftar Laporan Temuan Anda</h2>

    @if($temuans->isEmpty())
        <div class="text-center py-8 text-zinc-500 dark:text-zinc-400 border border-dashed border-zinc-300 dark:border-zinc-700 rounded-lg">
            Belum ada temuan yang Anda laporkan.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Area / Sub Area</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-zinc-200 dark:bg-zinc-800 dark:divide-zinc-700">
                    @foreach($temuans as $temuan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                {{ $temuan->tanggal_temuan->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">
                                <div class="font-medium">{{ $temuan->departemen->nama_departemen ?? '-' }}</div>
                                <div class="text-zinc-500 text-xs">{{ $temuan->sub_area }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusBadgeClass = match($temuan->status) {
                                        'open' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-500',
                                        'in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-500',
                                        'closed_pending_qa' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-500',
                                        'closed_acc' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-500',
                                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-500',
                                    };
                                    $statusText = match($temuan->status) {
                                        'open' => 'Open',
                                        'in_progress' => 'In Progress',
                                        'closed_pending_qa' => 'Pending QA',
                                        'closed_acc' => 'Closed (ACC)',
                                        default => $temuan->status,
                                    };
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusBadgeClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('temuan.detail', $temuan->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
