<div style="display:flex;flex-direction:column;gap:20px;" class="fu">
    
    {{-- Header --}}
    <div class="bph fu1">
        <div>
            <h2 class="bph-title">Divisi Manajemen (BOS'Q)</h2>
            <p class="bph-sub">Kelola pendaftaran & status anggota Divisi Manajemen penanggung jawab observasi BOS'Q (Target 2/minggu).</p>
        </div>
        <div>
            <button wire:click="openCreate" class="bbtn bbtn-primary">
                <span class="material-symbols-outlined" style="font-size:18px;">person_add</span>
                Penetapan Anggota Manajemen Baru
            </button>
        </div>
    </div>

    {{-- Alert Success --}}
    @if (session()->has('success'))
        <div class="balert balert-success fu">
            <span class="material-symbols-outlined fil" style="font-size:20px;">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Stat Cards Summary --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:16px;" class="fu1">
        <div class="bstat">
            <div class="bstat-icon" style="background:#e8f5e9;">
                <span class="material-symbols-outlined fil" style="color:#2e7d32;font-size:22px;">verified_user</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#2e7d32;">{{ $totalManajemen }}</div>
                <div class="bstat-lbl">Anggota Divisi Manajemen Aktif (Target 2/minggu)</div>
            </div>
        </div>
    </div>

    {{-- Form Penetapan Anggota Divisi Manajemen --}}
    @if($showForm)
    <div class="bcard fu1" style="padding:20px;border:1.5px solid var(--bp);">
        <h3 style="font-size:15px;font-weight:700;color:var(--btxt);margin:0 0 16px;">
            {{ $editingNik ? "Edit Anggota Manajemen: {$selectedNama} (NIK: {$editingNik})" : 'Penetapan Anggota Divisi Manajemen BOS\'Q Baru' }}
        </h3>

        @if(!$editingNik)
        {{-- Autocomplete Employee Search Input --}}
        <div style="margin-bottom:16px;position:relative;">
            <label for="search-karyawan-bosq" class="blabel">Cari Nama Karyawan dari Pusat Data <span style="color:var(--be);">*</span></label>
            <div style="position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--btxt2);font-size:18px;">search</span>
                <input type="text" id="search-karyawan-bosq" wire:model.live.debounce.250ms="searchKaryawan"
                       placeholder="Ketik nama karyawan atau NIK..."
                       class="binput" style="padding-left:40px;" autocomplete="off" />
                @if($selectedNik)
                    <button wire:click="clearSelectedKaryawan" title="Hapus Pilihan"
                            style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#ef4444;display:flex;align-items:center;">
                        <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                    </button>
                @endif
            </div>
            @error('searchKaryawan') <p class="berr-msg">{{ $message }}</p> @enderror

            {{-- Recommendations Dropdown List --}}
            @if(count($recommendations) > 0)
            <div class="bcard" style="position:absolute;top:100%;left:0;right:0;z-index:50;margin-top:4px;max-height:220px;overflow-y:auto;box-shadow:0 10px 25px rgba(0,0,0,0.15);border:1px solid var(--bbor);padding:4px 0;background:var(--bg,#fff);">
                <div style="padding:6px 12px;font-size:11px;font-weight:700;color:var(--btxt2);background:var(--bbg,#f8fafc);border-bottom:1px solid var(--bbor);">
                    REKOMENDASI KARYAWAN PABRIK:
                </div>
                @foreach($recommendations as $rec)
                <div wire:click="selectKaryawan('{{ $rec['nik'] }}')"
                     style="padding:10px 14px;cursor:pointer;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--bbor);transition:background .15s ease;"
                     onmouseover="this.style.background='var(--bhov, #f1f5f9)'" onmouseout="this.style.background='transparent'">
                    <div>
                        <div style="font-weight:700;color:var(--btxt);font-size:13px;">{{ $rec['nama'] }}</div>
                        <div style="font-size:11.5px;color:var(--btxt2);">NIK: <span style="font-family:monospace;font-weight:600;color:var(--bp);">{{ $rec['nik'] }}</span> • Departemen: {{ $rec['departemen']['nama_departemen'] ?? '-' }}</div>
                    </div>
                    <span class="material-symbols-outlined" style="color:var(--bp);font-size:18px;">add_circle</span>
                </div>
                @endforeach
            </div>
            @elseif(strlen(trim($searchKaryawan)) >= 1 && !$selectedNik)
            <div class="bcard" style="position:absolute;top:100%;left:0;right:0;z-index:50;margin-top:4px;padding:12px;text-align:center;color:var(--btxt2);font-size:12.5px;box-shadow:0 10px 25px rgba(0,0,0,0.1);background:var(--bg,#fff);">
                Karyawan tidak ditemukan atau sudah ditetapkan sebagai Divisi Manajemen.
            </div>
            @endif
        </div>
        @endif

        {{-- Selected Employee Details --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;background:var(--bbg,#f8fafc);padding:14px;border-radius:10px;border:1px solid var(--bbor);">
            <div>
                <label class="blabel">Nama Karyawan Selected</label>
                <input type="text" value="{{ $selectedNama ?: 'Belum dipilih' }}" class="binput" disabled style="background:#e2e8f0;font-weight:700;" />
            </div>
            <div>
                <label class="blabel">Departemen</label>
                <input type="text" value="{{ $selectedDept ?: '-' }}" class="binput" disabled style="background:#e2e8f0;" />
            </div>
            <div style="display:flex;align-items:center;gap:10px;padding-top:10px;grid-column:1/-1;">
                <input type="checkbox" id="manajemen-aktif" wire:model="status_aktif" style="width:16px;height:16px;accent-color:var(--bp);" />
                <label for="manajemen-aktif" style="font-size:13.5px;font-weight:500;color:var(--btxt);cursor:pointer;">Status Aktif Anggota Divisi Manajemen</label>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:16px;">
            <button type="button" wire:click="resetForm" class="bbtn bbtn-secondary">Batal</button>
            <button type="button" wire:click="save" wire:loading.attr="disabled" class="bbtn bbtn-primary">
                <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                {{ $editingNik ? 'Simpan Perubahan' : 'Tetapkan Sebagai Divisi Manajemen' }}
            </button>
        </div>
    </div>
    @endif

    {{-- Tabel Data Divisi Manajemen --}}
    <div class="bcard fu2">
        <div class="bcard-header" style="justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div class="bcard-hicon" style="background:var(--bp-light);">
                    <span class="material-symbols-outlined fil" style="color:var(--bp-dark);font-size:20px;">groups</span>
                </div>
                <div>
                    <h3 style="font-size:15px;font-weight:700;color:var(--btxt);margin:0;">Anggota Divisi Manajemen BOS'Q</h3>
                    <p style="font-size:12px;color:var(--btxt2);margin:0;">Daftar anggota divisi manajemen terdaftar untuk target 2 observasi / minggu</p>
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <select wire:model.live="filterDepartemenId" class="binput" style="width:auto;padding:6px 12px;font-size:12.5px;">
                    <option value="">Semua Departemen</option>
                    @foreach($departemens as $d)
                        <option value="{{ $d->id }}">{{ $d->nama_departemen }}</option>
                    @endforeach
                </select>

                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama / NIK..." class="binput" style="width:180px;padding:6px 12px;font-size:12.5px;" />
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table class="btbl">
                <thead>
                    <tr>
                        <th style="padding:12px 16px;text-align:left;">NIK & Nama Karyawan</th>
                        <th style="padding:12px 16px;text-align:left;">Departemen</th>
                        <th style="padding:12px 16px;text-align:center;">Status Status</th>
                        <th style="padding:12px 16px;text-align:center;width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawans as $k)
                        <tr>
                            <td style="padding:12px 16px;">
                                <div style="font-weight:700;color:var(--btxt);">{{ $k->nama }}</div>
                                <div style="font-size:11.5px;color:var(--btxt2);font-family:monospace;">NIK: {{ $k->nik }}</div>
                            </td>
                            <td style="padding:12px 16px;font-weight:600;color:var(--btxt2);">
                                {{ $k->departemen->nama_departemen ?? '-' }}
                            </td>
                            <td style="padding:12px 16px;text-align:center;">
                                @if($k->status_aktif)
                                    <span style="font-size:11px;font-weight:700;background:#e8f5e9;color:#2e7d32;padding:3px 8px;border-radius:4px;border:1px solid #c8e6c9;">Aktif</span>
                                @else
                                    <span style="font-size:11px;font-weight:700;background:#ffebee;color:#c62828;padding:3px 8px;border-radius:4px;border:1px solid #ffcdd2;">Nonaktif</span>
                                @endif
                            </td>
                            <td style="padding:12px 16px;text-align:center;">
                                <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                    <button wire:click="openEdit('{{ $k->nik }}')" title="Edit Status Anggota"
                                            class="bbtn bbtn-secondary bbtn-sm" style="width:34px;height:34px;padding:0!important;display:inline-flex;align-items:center;justify-content:center;border-radius:10px;">
                                        <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                                    </button>

                                    <button wire:click="toggleStatusAktif('{{ $k->nik }}')"
                                            wire:confirm="Ubah status aktif karyawan '{{ $k->nama }}'?"
                                            title="{{ $k->status_aktif ? 'Nonaktifkan Karyawan' : 'Aktifkan Karyawan' }}"
                                            class="bbtn bbtn-sm"
                                            style="width:34px;height:34px;padding:0!important;display:inline-flex;align-items:center;justify-content:center;border-radius:10px; {{ $k->status_aktif ? 'background:#fff8e1;color:#b78103;border:1px solid #ffe082;' : 'background:#e8f5e9;color:#2e7d32;border:1px solid #a5d6a7;' }}">
                                        <span class="material-symbols-outlined" style="font-size:16px;">{{ $k->status_aktif ? 'person_off' : 'person' }}</span>
                                    </button>

                                    <button wire:click="toggleDivisiManajemen('{{ $k->nik }}')"
                                            wire:confirm="Hapus '{{ $k->nama }}' (NIK: {{ $k->nik }}) dari Anggota Divisi Manajemen BOS'Q?"
                                            title="Hapus dari Divisi Manajemen"
                                            class="bbtn bbtn-danger bbtn-sm" style="width:34px;height:34px;padding:0!important;display:inline-flex;align-items:center;justify-content:center;border-radius:10px;">
                                        <span class="material-symbols-outlined" style="font-size:16px;">person_remove</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding:32px;text-align:center;color:var(--btxt2);">
                                Belum ada anggota Divisi Manajemen terdaftar untuk filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($karyawans->hasPages())
            <div style="padding:16px 20px;border-top:1px solid var(--bbor);">
                {{ $karyawans->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>

</div>
