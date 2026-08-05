<div style="display:flex;flex-direction:column;gap:20px;" class="fu">
    
    {{-- Header --}}
    <div class="bph fu1">
        <div>
            <h2 class="bph-title">Anggota Divisi Manajemen & Master Karyawan</h2>
            <p class="bph-sub">Kelola pendaftaran, pengubahan, penghapusan data karyawan per Departemen dan penetapan target Divisi Manajemen</p>
        </div>
        <div>
            <button wire:click="openCreate" class="bbtn bbtn-primary">
                <span class="material-symbols-outlined" style="font-size:18px;">person_add</span>
                Tambah Karyawan Baru
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
            <div class="bstat-icon" style="background:#e3f2fd;">
                <span class="material-symbols-outlined fil" style="color:#1565c0;font-size:22px;">badge</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#1565c0;">{{ $totalKaryawan }}</div>
                <div class="bstat-lbl">Total Karyawan Terdaftar</div>
            </div>
        </div>

        <div class="bstat">
            <div class="bstat-icon" style="background:#e8f5e9;">
                <span class="material-symbols-outlined fil" style="color:#2e7d32;font-size:22px;">verified_user</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#2e7d32;">{{ $totalManajemen }}</div>
                <div class="bstat-lbl">Anggota Divisi Manajemen (Target 2/minggu)</div>
            </div>
        </div>
    </div>

    {{-- Form Tambah / Edit Karyawan --}}
    @if($showForm)
    <div class="bcard fu1" style="padding:20px;">
        <h3 style="font-size:15px;font-weight:700;color:var(--btxt);margin:0 0 16px;">
            {{ $isEditing ? 'Edit Data Karyawan' : 'Tambah Karyawan Baru' }}
        </h3>
        <form wire:submit.prevent="save" style="display:flex;flex-direction:column;gap:14px;">
            <div style="display:grid;grid-template-columns:1fr 2fr 1.5fr;gap:14px;" class="max-md:flex-col">
                <div>
                    <label class="blabel">NIK (Nomor Induk Karyawan) <span style="color:var(--error);">*</span></label>
                    <input type="text" wire:model="nik" class="binput" placeholder="Contoh: 18633" required />
                    @error('nik') <span class="berr-msg">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="blabel">Nama Lengkap Karyawan <span style="color:var(--error);">*</span></label>
                    <input type="text" wire:model="nama" class="binput" placeholder="Masukkan nama lengkap karyawan..." required />
                    @error('nama') <span class="berr-msg">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="blabel">Departemen (Area) <span style="color:var(--error);">*</span></label>
                    <select wire:model="departemen_id" class="binput" required>
                        <option value="">-- Pilih Departemen --</option>
                        @foreach($departemens as $d)
                            <option value="{{ $d->id }}">{{ $d->nama_departemen }}</option>
                        @endforeach
                    </select>
                    @error('departemen_id') <span class="berr-msg">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:24px;margin-top:6px;flex-wrap:wrap;">
                <label style="display:inline-flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;font-weight:600;color:var(--btxt);">
                    <input type="checkbox" wire:model="is_anggota_divisi_manajemen" style="width:16px;height:16px;accent-color:var(--bp);" />
                    <span>Tetapkan sebagai Anggota Divisi Manajemen (Target 2/minggu)</span>
                </label>

                <label style="display:inline-flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;font-weight:600;color:var(--btxt);">
                    <input type="checkbox" wire:model="status_aktif" style="width:16px;height:16px;accent-color:#2e7d32;" />
                    <span>Status Karyawan Aktif</span>
                </label>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:10px;">
                <button type="button" wire:click="resetForm" class="bbtn bbtn-secondary">Batal</button>
                <button type="submit" wire:loading.attr="disabled" class="bbtn bbtn-primary">
                    <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                    {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Karyawan' }}
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- Tabel Data Karyawan --}}
    <div class="bcard fu2">
        <div class="bcard-header" style="justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div class="bcard-hicon" style="background:var(--bp-light);">
                    <span class="material-symbols-outlined fil" style="color:var(--bp-dark);font-size:20px;">groups</span>
                </div>
                <div>
                    <h3 style="font-size:15px;font-weight:700;color:var(--btxt);margin:0;">Manajemen Data Karyawan & Divisi Manajemen</h3>
                    <p style="font-size:12px;color:var(--btxt2);margin:0;">Kelola akun karyawan per departemen dan penetapan target observasi</p>
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <select wire:model.live="filterDivisiManajemen" class="binput" style="width:auto;padding:6px 12px;font-size:12.5px;">
                    <option value="">Semua Anggota</option>
                    <option value="1">Divisi Manajemen Saja (Active)</option>
                    <option value="0">Bukan Divisi Manajemen</option>
                </select>

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
                        <th style="padding:12px 16px;text-align:left;">Status System</th>
                        <th style="padding:12px 16px;text-align:center;">Divisi Manajemen (Target 2/minggu)</th>
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
                            <td style="padding:12px 16px;">
                                @if($k->status_aktif)
                                    <span style="font-size:11px;font-weight:700;background:#e8f5e9;color:#2e7d32;padding:3px 8px;border-radius:4px;border:1px solid #c8e6c9;">Aktif</span>
                                @else
                                    <span style="font-size:11px;font-weight:700;background:#ffebee;color:#c62828;padding:3px 8px;border-radius:4px;border:1px solid #ffcdd2;">Nonaktif</span>
                                @endif
                            </td>
                            <td style="padding:12px 16px;text-align:center;">
                                <button wire:click="toggleDivisiManajemen('{{ $k->nik }}')"
                                    class="bbtn bbtn-sm"
                                    style="{{ $k->is_anggota_divisi_manajemen ? 'background:#e8f5e9;color:#2e7d32;border:1.5px solid #a5d6a7;' : 'background:var(--bsur);color:var(--btxt2);border:1.5px solid var(--bbor);' }}">
                                    <span class="material-symbols-outlined" style="font-size:16px;">{{ $k->is_anggota_divisi_manajemen ? 'check_box' : 'check_box_outline_blank' }}</span>
                                    <span>{{ $k->is_anggota_divisi_manajemen ? 'Anggota Divisi Manajemen (Aktif)' : 'Bukan Anggota' }}</span>
                                </button>
                            </td>
                            <td style="padding:12px 16px;text-align:center;">
                                <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                    {{-- Edit Button --}}
                                    <button wire:click="edit('{{ $k->nik }}')" title="Edit Data Karyawan"
                                            class="bbtn bbtn-secondary bbtn-sm" style="width:34px;height:34px;padding:0!important;display:inline-flex;align-items:center;justify-content:center;border-radius:10px;">
                                        <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                                    </button>

                                    {{-- Toggle Status Button (Yellow/Amber if active to deactivate, Green if inactive to activate) --}}
                                    <button wire:click="toggleStatusAktif('{{ $k->nik }}')"
                                            wire:confirm="Ubah status aktif karyawan '{{ $k->nama }}'?"
                                            title="{{ $k->status_aktif ? 'Nonaktifkan Karyawan' : 'Aktifkan Karyawan' }}"
                                            class="bbtn bbtn-sm"
                                            style="width:34px;height:34px;padding:0!important;display:inline-flex;align-items:center;justify-content:center;border-radius:10px; {{ $k->status_aktif ? 'background:#fff8e1;color:#b78103;border:1px solid #ffe082;' : 'background:#e8f5e9;color:#2e7d32;border:1px solid #a5d6a7;' }}">
                                        <span class="material-symbols-outlined" style="font-size:16px;">{{ $k->status_aktif ? 'person_off' : 'person' }}</span>
                                    </button>

                                    {{-- Delete Button --}}
                                    <button wire:click="delete('{{ $k->nik }}')"
                                            wire:confirm="Apakah Anda yakin ingin menghapus karyawan '{{ $k->nama }}' (NIK: {{ $k->nik }}) dari sistem?"
                                            title="Hapus Karyawan"
                                            class="bbtn bbtn-danger bbtn-sm" style="width:34px;height:34px;padding:0!important;display:inline-flex;align-items:center;justify-content:center;border-radius:10px;">
                                        <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding:32px;text-align:center;color:var(--btxt2);">
                                Belum ada data Karyawan untuk filter ini.
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
