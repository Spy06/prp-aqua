<div class="space-y-4 sm:space-y-6" id="rekap-periode-container">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-lg sm:text-xl font-semibold text-zinc-900 dark:text-zinc-100">Rekap Periode</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                Rangkuman temuan dalam rentang waktu tertentu
            </p>
        </div>

        {{-- Export Buttons --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('export.excel', $queryParams) }}"
               target="_blank"
               class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition">
                <flux:icon.table-cells variant="outline" class="w-4 h-4" />
                Excel
            </a>
            <a href="{{ route('export.pdf.rekap', $queryParams) }}"
               target="_blank"
               class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">
                <flux:icon.document-text variant="outline" class="w-4 h-4" />
                PDF
            </a>
        </div>
    </div>

    {{-- Filter Panel --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-5">
        <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-4">Filter Periode</h3>

        {{-- Tipe Filter --}}
        <div class="flex flex-wrap gap-3 mb-4">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" wire:model.live="filterTipe" value="bulan"
                       class="text-blue-600 focus:ring-blue-500" id="filter-bulan" />
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Per Bulan</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" wire:model.live="filterTipe" value="tahun"
                       class="text-blue-600 focus:ring-blue-500" id="filter-tahun" />
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Per Tahun</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" wire:model.live="filterTipe" value="custom"
                       class="text-blue-600 focus:ring-blue-500" id="filter-custom" />
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Custom Range</span>
            </label>
        </div>

        {{-- Filter Per Bulan --}}
        @if($filterTipe === 'bulan')
            <div class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[120px]">
                    <label class="block text-xs text-zinc-500 mb-1">Bulan</label>
                    <select wire:model.live="filterBulan"
                            id="select-bulan"
                            class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 text-sm px-3 py-2">
                        @foreach($bulanList as $kode => $nama)
                            <option value="{{ $kode }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[100px]">
                    <label class="block text-xs text-zinc-500 mb-1">Tahun</label>
                    <select wire:model.live="filterBulanTahun"
                            id="select-bulan-tahun"
                            class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 text-sm px-3 py-2">
                        @foreach($tahunList as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        {{-- Filter Per Tahun --}}
        @if($filterTipe === 'tahun')
            <div>
                <label class="block text-xs text-zinc-500 mb-1">Tahun</label>
                <select wire:model.live="filterTahun"
                        id="select-tahun"
                        class="rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 text-sm px-3 py-2">
                    @foreach($tahunList as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        {{-- Filter Custom Range --}}
        @if($filterTipe === 'custom')
            <div class="grid grid-cols-2 gap-3 sm:flex sm:flex-wrap sm:gap-4 items-end">
                <div>
                    <label for="tanggal-awal" class="block text-xs text-zinc-500 mb-1">Tanggal Awal</label>
                    <input type="date" id="tanggal-awal"
                           wire:model.live="tanggalAwal"
                           class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 text-sm px-3 py-2" />
                </div>
                <div>
                    <label for="tanggal-akhir" class="block text-xs text-zinc-500 mb-1">Tanggal Akhir</label>
                    <input type="date" id="tanggal-akhir"
                           wire:model.live="tanggalAkhir"
                           class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 text-sm px-3 py-2" />
                </div>
            </div>
        @endif

        {{-- Filter Departemen & Status --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-700">
            <div>
                <label for="filter-dept" class="block text-xs font-semibold text-zinc-500 mb-1">Filter Departemen</label>
                <select id="filter-dept" wire:model.live="filterDepartemen"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 text-sm px-3 py-2">
                    <option value="">Semua Departemen</option>
                    @foreach($allDepartemens as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="filter-status" class="block text-xs font-semibold text-zinc-500 mb-1">Filter Status</label>
                <select id="filter-status" wire:model.live="filterStatus"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 text-sm px-3 py-2">
                    <option value="">Semua Status</option>
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="closed_pending_qa">Pending QA</option>
                    <option value="closed_acc">Closed ACC</option>
                </select>
            </div>
        </div>

        {{-- Range yang aktif --}}
        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-3">
            Menampilkan data: <span class="font-semibold text-zinc-700 dark:text-zinc-200">
                {{ \Carbon\Carbon::parse($awal)->translatedFormat('d M Y') }} — {{ \Carbon\Carbon::parse($akhir)->translatedFormat('d M Y') }}
            </span>
        </p>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-4 sm:p-5">
            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1">Total</p>
            <p class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-zinc-100">{{ $total }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-4 sm:p-5">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
                <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">Open</p>
            </div>
            <p class="text-xl sm:text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $perStatus['open'] }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-4 sm:p-5">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-2.5 h-2.5 rounded-full bg-blue-500"></div>
                <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">Progress</p>
            </div>
            <p class="text-xl sm:text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $perStatus['in_progress'] }}</p>
        </div>
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-4 sm:p-5">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-2.5 h-2.5 rounded-full bg-purple-500"></div>
                <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">Pending QA</p>
            </div>
            <p class="text-xl sm:text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $perStatus['closed_pending_qa'] }}</p>
        </div>
        <div class="col-span-2 sm:col-span-1 bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-4 sm:p-5">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-2.5 h-2.5 rounded-full bg-green-500"></div>
                <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">Closed ACC</p>
            </div>
            <p class="text-xl sm:text-2xl font-bold text-green-600 dark:text-green-400">{{ $perStatus['closed_acc'] }}</p>
        </div>
    </div>

    {{-- Breakdown Per Departemen --}}
    @if($perDepartemen->isNotEmpty())
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-700">
                <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">Breakdown per Departemen</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 dark:bg-zinc-900/50">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">Departemen</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">Total</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-yellow-600 dark:text-yellow-400 uppercase tracking-wide">Open</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wide">In Progress</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-purple-600 dark:text-purple-400 uppercase tracking-wide">Pending QA</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-green-600 dark:text-green-400 uppercase tracking-wide">Closed ACC</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700/50">
                        @foreach($perDepartemen as $row)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30 transition">
                                <td class="px-6 py-3 font-medium text-zinc-900 dark:text-zinc-100">{{ $row['nama'] }}</td>
                                <td class="px-4 py-3 text-center font-bold text-zinc-900 dark:text-zinc-100">{{ $row['total'] }}</td>
                                <td class="px-4 py-3 text-center text-yellow-700 dark:text-yellow-400">{{ $row['open'] }}</td>
                                <td class="px-4 py-3 text-center text-blue-700 dark:text-blue-400">{{ $row['in_progress'] }}</td>
                                <td class="px-4 py-3 text-center text-purple-700 dark:text-purple-400">{{ $row['closed_pending_qa'] }}</td>
                                <td class="px-4 py-3 text-center text-green-700 dark:text-green-400">{{ $row['closed_acc'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="text-center py-12 bg-white dark:bg-zinc-800 rounded-xl border border-dashed border-zinc-300 dark:border-zinc-700">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Tidak ada temuan pada periode yang dipilih.</p>
        </div>
    @endif
</div>
