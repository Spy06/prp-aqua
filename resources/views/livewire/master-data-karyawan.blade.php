<div class="fu" id="master-data-karyawan-container" style="display:flex;flex-direction:column;gap:16px;">

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
            <h2 class="bph-title">Pusat Master Data Karyawan</h2>
            <p class="bph-sub">Pengelolaan pusat seluruh data karyawan pabrik (SIVERA & BOS'Q) — NIK/Share ID, Nama, Departemen, dan Email Opsional.</p>
        </div>
        <button wire:click="openCreate" id="btn-tambah-karyawan-pusat" class="bbtn bbtn-primary">
            <span class="material-symbols-outlined" style="font-size:18px;">person_add</span>
            Tambah Karyawan Baru
        </button>
    </div>

    {{-- Form Tambah/Edit --}}
    @if($showForm)
    <div class="bcard fu1" style="padding:20px;">
        <h3 style="font-size:14px;font-weight:700;color:var(--btxt);margin:0 0 16px;">
            {{ $editingNik ? "Edit Data Karyawan (NIK: {$editingNik})" : 'Tambah Karyawan Baru' }}
        </h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;">
            <div>
                <label for="form-nik" class="blabel">NIK / Share ID <span style="color:var(--be);">*</span></label>
                <input type="text" id="form-nik" wire:model="nik"
                       placeholder="Contoh: 2024001"
                       class="binput" />
                <span style="font-size:11px;color:var(--btxt2);margin-top:3px;display:block;">
                    NIK/Share ID berfungsi sebagai identitas unik karyawan. Password default awal karyawan baru adalah NIK ini (dapat disesuaikan oleh Super Admin).
                </span>
                @error('nik') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="form-nama" class="blabel">Nama Lengkap Karyawan <span style="color:var(--be);">*</span></label>
                <input type="text" id="form-nama" wire:model="nama" placeholder="Nama lengkap karyawan" class="binput" />
                @error('nama') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="form-email" class="blabel">Alamat Email (Opsional)</label>
                <input type="email" id="form-email" wire:model="email" placeholder="contoh: pic@perusahaan.com" class="binput" />
                <span style="font-size:11px;color:var(--btxt2);margin-top:3px;display:block;">Opsional. Diisi jika karyawan bertindak sebagai PIC/penerima email. Kosongkan untuk karyawan biasa.</span>
                @error('email') <p class="berr-msg">{{ $message }}</p> @enderror
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
        </div>
        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:16px;">
            <button wire:click="resetForm" class="bbtn bbtn-secondary">Batal</button>
            <button wire:click="simpan" wire:loading.attr="disabled" id="btn-simpan-karyawan-pusat" class="bbtn bbtn-primary">
                <span wire:loading.remove wire:target="simpan">
                    <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                </span>
                <span wire:loading wire:target="simpan">
                    <span class="material-symbols-outlined" style="font-size:16px;animation:spin 1s linear infinite;">refresh</span>
                </span>
                Simpan Data Karyawan
            </button>
        </div>
    </div>
    @endif

    {{-- Search & Filter Bar --}}
    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <div style="max-width:320px;flex:1;">
            <div style="position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--btxt2);font-size:18px;">search</span>
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="Cari NIK/Share ID, Nama, atau Email..."
                       class="binput" style="padding-left:40px;" />
            </div>
        </div>

        <div style="min-width:220px;">
            <select wire:model.live="filterDepartemen" class="binput">
                <option value="">-- Semua Departemen --</option>
                @foreach($departemens as $d)
                    <option value="{{ $d->id }}">{{ $d->nama_departemen }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="bcard fu2" style="overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="btbl" style="min-width:600px;">
                <thead>
                    <tr>
                        <th>NIK / Share ID</th>
                        <th>Nama Karyawan</th>
                        <th>Departemen</th>
                        <th>Email (Opsional)</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawans as $k)
                    <tr>
                        <td style="font-family:monospace;font-size:12.5px;font-weight:700;color:var(--bp);">{{ $k->nik }}</td>
                        <td style="font-weight:600;">
                            {{ $k->nama }}
                        </td>
                        <td>
                            <span style="display:inline-flex;align-items:center;gap:4px;font-weight:500;">
                                <span class="material-symbols-outlined" style="font-size:15px;color:var(--btxt2);">domain</span>
                                {{ $k->departemen->nama_departemen ?? '-' }}
                            </span>
                        </td>
                        <td style="font-size:12.5px;color:var(--btxt2);">
                            @if($k->user?->email)
                                <span style="display:inline-flex;align-items:center;gap:4px;color:#0f172a;font-weight:500;">
                                    <span class="material-symbols-outlined" style="font-size:14px;color:var(--bp);">mail</span>
                                    {{ $k->user->email }}
                                </span>
                            @else
                                <span style="font-size:11px;color:#94a3b8;font-style:italic;">- (Opsional/Kosong)</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                <button wire:click="openEdit('{{ $k->nik }}')" title="Edit Karyawan"
                                        class="bbtn bbtn-secondary bbtn-sm" style="padding:5px 8px!important;">
                                    <span class="material-symbols-outlined" style="font-size:15px;">edit</span>
                                </button>
                                <button wire:click="hapus('{{ $k->nik }}')"
                                        wire:confirm="Yakin ingin menghapus data karyawan '{{ $k->nama }}' (NIK: {{ $k->nik }})?"
                                        title="Hapus Karyawan"
                                        class="bbtn bbtn-danger bbtn-sm" style="padding:5px 8px!important;">
                                    <span class="material-symbols-outlined" style="font-size:15px;">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:32px;color:var(--btxt2);">
                            <span class="material-symbols-outlined" style="font-size:36px;display:block;margin-bottom:8px;opacity:.3;">badge</span>
                            Belum ada data karyawan terdaftar di sistem
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
