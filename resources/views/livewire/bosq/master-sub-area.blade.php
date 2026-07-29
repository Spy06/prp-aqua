<div style="display:flex;flex-direction:column;gap:24px;" class="fu">
    
    {{-- Header --}}
    <div class="bph fu1">
        <div>
            <h2 class="bph-title">Master Data Sub Area — BOS'Q</h2>
            <p class="bph-sub">Kelola pemetaan Sub Area per Departemen dalam sistem BOS'Q</p>
        </div>
        <div>
            <button wire:click="create" class="bbtn bbtn-primary bbtn-sm">
                <span class="material-symbols-outlined" style="font-size:18px;">add</span>
                Tambah Sub Area Baru
            </button>
        </div>
    </div>

    {{-- Alert Success --}}
    @if (session()->has('success'))
        <div class="balert balert-success fu">
            <span class="material-symbols-outlined" style="font-size:20px;">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Form Tambah/Edit --}}
    <div class="bcard fu1" style="padding:20px;">
        <h3 style="font-size:15px;font-weight:700;color:var(--btxt);margin:0 0 16px;">
            {{ $isEditing ? 'Edit Sub Area' : 'Tambah Sub Area Baru' }}
        </h3>
        <form wire:submit.prevent="save" style="display:flex;flex-direction:column;gap:14px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;" class="max-md:flex-col">
                <div>
                    <label class="blabel">Departemen (Opsional / Umum)</label>
                    <select wire:model="departemen_id" class="binput">
                        <option value="">-- Semua / Sub Area Umum --</option>
                        @foreach($departemens as $d)
                            <option value="{{ $d->id }}">{{ $d->nama_departemen }}</option>
                        @endforeach
                    </select>
                    @error('departemen_id') <span class="berr">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="blabel">Nama Sub Area <span style="color:var(--error);">*</span></label>
                    <input type="text" wire:model="nama_sub_area" class="binput" placeholder="Contoh: SBO Filler Line 1, LAB Fiskim, Kantin" required />
                    @error('nama_sub_area') <span class="berr">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:4px;">
                <button type="submit" class="bbtn bbtn-primary bbtn-sm">
                    <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                    {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Sub Area' }}
                </button>
                @if($isEditing || $nama_sub_area || $departemen_id)
                    <button type="button" wire:click="resetForm" class="bbtn bbtn-secondary bbtn-sm">
                        Batal
                    </button>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabel Data --}}
    <div class="bcard fu2">
        <div class="bcard-header" style="justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div class="bcard-hicon" style="background:var(--bp-light);">
                    <span class="material-symbols-outlined fil" style="color:var(--bp-dark);font-size:20px;">location_on</span>
                </div>
                <div>
                    <h3 style="font-size:15px;font-weight:700;color:var(--btxt);margin:0;">Daftar Master Sub Area</h3>
                    <p style="font-size:12px;color:var(--btxt2);margin:0;">Total Sub Area terdaftar dalam sistem</p>
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <select wire:model.live="filterDepartemenId" class="binput" style="width:auto;padding:6px 12px;font-size:12.5px;">
                    <option value="">Semua Departemen</option>
                    @foreach($departemens as $d)
                        <option value="{{ $d->id }}">{{ $d->nama_departemen }}</option>
                    @endforeach
                </select>

                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari sub area..." class="binput" style="width:200px;padding:6px 12px;font-size:12.5px;" />
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;text-align:left;">
                <thead>
                    <tr style="background:var(--bsur);border-bottom:1px solid var(--bbor);color:var(--btxt2);font-size:11px;text-transform:uppercase;letter-spacing:0.5px;">
                        <th style="padding:12px 16px;">ID</th>
                        <th style="padding:12px 16px;">Departemen</th>
                        <th style="padding:12px 16px;">Nama Sub Area</th>
                        <th style="padding:12px 16px;text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subAreas as $sa)
                        <tr style="border-bottom:1px solid var(--bbor);">
                            <td style="padding:12px 16px;font-weight:700;color:var(--bp);">#{{ $sa->id }}</td>
                            <td style="padding:12px 16px;">
                                @if($sa->departemen)
                                    <span style="font-weight:600;color:var(--btxt);">{{ $sa->departemen->nama_departemen }}</span>
                                @else
                                    <span style="font-size:11.5px;color:var(--btxt2);background:var(--bsur);padding:3px 8px;border-radius:4px;border:1px solid var(--bbor);">Sub Area Umum</span>
                                @endif
                            </td>
                            <td style="padding:12px 16px;font-weight:600;color:var(--btxt);">
                                {{ $sa->nama_sub_area }}
                                @if(strtolower(trim($sa->nama_sub_area)) === 'others')
                                    <span style="font-size:10px;font-weight:700;background:#ffebee;color:#c62828;padding:2px 6px;border-radius:4px;margin-left:6px;">SPECIAL (OPSIONAL INPUT)</span>
                                @endif
                            </td>
                            <td style="padding:12px 16px;text-align:center;">
                                <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                    <button wire:click="edit({{ $sa->id }})" class="bbtn bbtn-secondary bbtn-sm">
                                        <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                                        Edit
                                    </button>
                                    <button wire:click="delete({{ $sa->id }})" wire:confirm="Apakah Anda yakin ingin menghapus Sub Area ini?" class="bbtn bbtn-danger bbtn-sm">
                                        <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding:32px;text-align:center;color:var(--btxt2);">
                                Belum ada data Sub Area.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subAreas->hasPages())
            <div style="padding:16px 20px;border-top:1px solid var(--bbor);">
                {{ $subAreas->links() }}
            </div>
        @endif
    </div>

</div>
