<div class="fu" id="master-karyawan-container" style="display:flex;flex-direction:column;gap:16px;">

    {{-- Alerts --}}
    @if(session('success'))
    <div class="balert balert-success fu">
        <span class="material-symbols-outlined fil" style="font-size:18px;flex-shrink:0;">check_circle</span>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="balert balert-error fu">
        <span class="material-symbols-outlined fil" style="font-size:18px;flex-shrink:0;">error</span>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Header --}}
    <div class="bph">
        <div>
            <h2 class="bph-title">Master Karyawan</h2>
            <p class="bph-sub">Kelola data karyawan yang terdaftar dalam sistem</p>
        </div>
        <button wire:click="openCreate" id="btn-tambah-karyawan" class="bbtn bbtn-primary">
            <span class="material-symbols-outlined" style="font-size:18px;">person_add</span>
            Tambah Karyawan
        </button>
    </div>

    {{-- Form Tambah/Edit --}}
    @if($showForm)
    <div class="bcard fu1" style="padding:20px;">
        <h3 style="font-size:14px;font-weight:700;color:var(--btxt);margin:0 0 16px;">
            {{ $editingNik ? "Edit Karyawan (NIK: {$editingNik})" : 'Tambah Karyawan Baru' }}
        </h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;">
            <div>
                <label for="form-nik" class="blabel">NIK <span style="color:var(--be);">*</span></label>
                <input type="text" id="form-nik" wire:model="nik"
                       {{ $editingNik ? 'disabled' : '' }}
                       placeholder="Contoh: 2024001"
                       class="binput" style="{{ $editingNik ? 'opacity:.6;cursor:not-allowed;' : '' }}" />
                @error('nik') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="form-nama" class="blabel">Nama Lengkap <span style="color:var(--be);">*</span></label>
                <input type="text" id="form-nama" wire:model="nama" placeholder="Nama lengkap" class="binput" />
                @error('nama') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="form-dept" class="blabel">Departemen <span style="color:var(--be);">*</span></label>
                <select id="form-dept" wire:model="departemen_id" class="binput">
                    <option value="">-- Pilih Departemen --</option>
                    @foreach($departemens as $d)
                        <option value="{{ $d->id }}">{{ $d->nama_departemen }}</option>
                    @endforeach
                </select>
                @error('departemen_id') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>
            <div style="display:flex;align-items:center;gap:10px;padding-top:20px;">
                <input type="checkbox" id="form-aktif" wire:model="status_aktif"
                       style="width:16px;height:16px;accent-color:var(--bp);" />
                <label for="form-aktif" style="font-size:13.5px;font-weight:500;color:var(--btxt);cursor:pointer;">Karyawan Aktif</label>
            </div>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:16px;">
            <button wire:click="resetForm" class="bbtn bbtn-secondary">Batal</button>
            <button wire:click="simpan" wire:loading.attr="disabled" id="btn-simpan-karyawan" class="bbtn bbtn-primary">
                <span wire:loading.remove wire:target="simpan">
                    <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                </span>
                <span wire:loading wire:target="simpan">
                    <span class="material-symbols-outlined" style="font-size:16px;animation:spin 1s linear infinite;">refresh</span>
                </span>
                Simpan
            </button>
        </div>
    </div>
    @endif

    {{-- Search --}}
    <div style="max-width:340px;">
        <div style="position:relative;">
            <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--btxt2);font-size:18px;">search</span>
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Cari NIK atau Nama..."
                   class="binput" style="padding-left:40px;" />
        </div>
    </div>

    {{-- Table --}}
    <div class="bcard fu2" style="overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="btbl" style="min-width:500px;">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Departemen</th>
                        <th style="text-align:center;">Status</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawans as $k)
                    <tr>
                        <td style="font-family:monospace;font-size:12.5px;">{{ $k->nik }}</td>
                        <td style="font-weight:600;">
                            {{ $k->nama }}
                            <div style="font-size:11.5px;color:var(--btxt2);display:none;" class="sm-dept">{{ $k->departemen->nama_departemen ?? '-' }}</div>
                        </td>
                        <td>{{ $k->departemen->nama_departemen ?? '-' }}</td>
                        <td style="text-align:center;">
                            @if($k->status_aktif)
                                <span class="bbadge bbadge-closed">Aktif</span>
                            @else
                                <span class="bbadge bbadge-open">Non-aktif</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                <button wire:click="openEdit('{{ $k->nik }}')" title="Edit"
                                        class="bbtn bbtn-secondary bbtn-sm" style="padding:5px 8px!important;">
                                    <span class="material-symbols-outlined" style="font-size:15px;">edit</span>
                                </button>
                                <button wire:click="toggleStatus('{{ $k->nik }}')"
                                        wire:confirm="Ubah status aktif karyawan '{{ $k->nama }}'?"
                                        title="Toggle Status"
                                        class="bbtn bbtn-sm {{ $k->status_aktif ? 'bbadge-open' : 'bbadge-closed' }}" style="padding:5px 8px!important; cursor:pointer;">
                                    <span class="material-symbols-outlined" style="font-size:15px;">{{ $k->status_aktif ? 'person_off' : 'person' }}</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:32px;color:var(--btxt2);">
                            <span class="material-symbols-outlined" style="font-size:36px;display:block;margin-bottom:8px;opacity:.3;">group</span>
                            Belum ada data karyawan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($karyawans->hasPages())
        <div style="padding:12px 16px;border-top:1px solid var(--bbor);">
            {{ $karyawans->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>
    <style>@keyframes spin{to{transform:rotate(360deg);}}</style>
</div>
