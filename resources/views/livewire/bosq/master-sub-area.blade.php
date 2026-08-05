<div class="fu" id="master-sub-area-container" style="display:flex;flex-direction:column;gap:18px;">

    {{-- Page Header --}}
    <div class="bph">
        <div>
            <h2 class="bph-title">Master Data Sub Area — BOS'Q</h2>
            <p class="bph-sub">Kelola pemetaan dan pendaftaran nama Sub Area per Departemen dalam sistem BOS'Q</p>
        </div>
        <button wire:click="openCreateSubArea" class="bbtn bbtn-primary">
            <span class="material-symbols-outlined" style="font-size:18px;">add</span>
            Tambah Sub Area Baru
        </button>
    </div>

    {{-- Flash Alerts --}}
    @if (session()->has('success'))
        <div class="balert balert-success fu">
            <span class="material-symbols-outlined fil" style="font-size:18px;flex-shrink:0;">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Form Tambah/Edit Sub Area --}}
    @if($showSubAreaForm)
    <div class="bcard fu1" style="padding:20px;">
        <h3 style="font-size:14px;font-weight:700;color:var(--btxt);margin:0 0 16px;">
            {{ $subAreaId ? 'Edit Sub Area' : 'Tambah Sub Area Baru' }}
        </h3>
        <div style="display:grid;grid-template-columns:1fr 2fr;gap:14px;max-width:720px;">
            <div>
                <label for="form-dept" class="blabel">Departemen (Area) <span style="color:var(--error);">*</span></label>
                <select id="form-dept" wire:model="departemen_id" class="binput">
                    <option value="">-- Pilih Departemen --</option>
                    @foreach($departemens as $d)
                        <option value="{{ $d->id }}">{{ $d->nama_departemen }}</option>
                    @endforeach
                </select>
                @error('departemen_id') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="form-nama-sub" class="blabel">Nama Sub Area <span style="color:var(--error);">*</span></label>
                <input type="text" id="form-nama-sub" wire:model="nama_sub_area"
                       placeholder="Contoh: SBO Filler Line 1, LAB Fiskim, Kantin"
                       class="binput" />
                @error('nama_sub_area') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:16px;">
            <button wire:click="resetForm" class="bbtn bbtn-secondary">Batal</button>
            <button wire:click="saveSubArea" wire:loading.attr="disabled" class="bbtn bbtn-primary">
                <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                Simpan Sub Area
            </button>
        </div>
    </div>
    @endif

    {{-- Filter Bar Departemen & Search --}}
    <div class="bcard fu1" style="padding:16px 20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;">
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <span class="material-symbols-outlined" style="color:var(--bp-dark);font-size:20px;">location_on</span>
                <span style="font-size:13px;font-weight:700;color:var(--btxt);">Filter Departemen (Area):</span>

                <select wire:model.live="filterDepartemenId" class="binput" style="width:auto;padding:7px 14px;font-size:13px;font-weight:600;">
                    <option value="">Semua Departemen</option>
                    @foreach($departemens as $d)
                        <option value="{{ $d->id }}">{{ $d->nama_departemen }}</option>
                    @endforeach
                </select>
            </div>

            <div style="max-width:280px;flex:1;position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--btxt2);font-size:18px;">search</span>
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="Cari nama sub area..."
                       class="binput" style="padding-left:40px;padding-top:7px;padding-bottom:7px;font-size:12.5px;" />
            </div>
        </div>
    </div>

    {{-- Data Table Sub Area --}}
    <div class="bcard fu2" style="overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="btbl">
                <thead>
                    <tr>
                        <th style="width:80px;text-align:left;">ID</th>
                        <th style="width:200px;text-align:left;">Departemen (Area)</th>
                        <th style="text-align:left;">Nama Sub Area</th>
                        <th style="width:140px;text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subAreas as $sa)
                    <tr>
                        <td>
                            <span style="font-family:monospace;font-size:12px;font-weight:700;color:var(--bp);background:var(--bp-light);padding:3px 8px;border-radius:6px;">#{{ $sa->id }}</span>
                        </td>
                        <td>
                            <span style="font-weight:700;font-size:12.5px;color:var(--btxt);">{{ $sa->departemen->nama_departemen ?? 'Umum' }}</span>
                        </td>
                        <td style="font-weight:600;color:var(--btxt);">
                            {{ $sa->nama_sub_area }}
                            @if(strtolower(trim($sa->nama_sub_area)) === 'others')
                                <span style="font-size:10px;font-weight:700;background:#ffebee;color:#c62828;padding:2px 6px;border-radius:4px;margin-left:4px;">OTHERS</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                <button wire:click="editSubArea({{ $sa->id }})" title="Edit Sub Area"
                                        class="bbtn bbtn-secondary bbtn-sm" style="padding:5px 10px!important;">
                                    <span class="material-symbols-outlined" style="font-size:15px;">edit</span>
                                    Edit
                                </button>
                                <button wire:click="deleteSubArea({{ $sa->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus Sub Area '{{ $sa->nama_sub_area }}'?"
                                        class="bbtn bbtn-danger bbtn-sm" style="padding:5px 10px!important;">
                                    <span class="material-symbols-outlined" style="font-size:15px;">delete</span>
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;padding:32px;color:var(--btxt2);">
                            <span class="material-symbols-outlined" style="font-size:36px;display:block;margin-bottom:8px;opacity:.3;">location_off</span>
                            Belum ada data Sub Area untuk filter ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($subAreas->hasPages())
        <div style="padding:12px 16px;border-top:1px solid var(--bbor);">
            {{ $subAreas->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>

</div>
