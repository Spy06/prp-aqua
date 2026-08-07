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
            <h2 class="bph-title">Master PIC (SIVERA)</h2>
            <p class="bph-sub">Kelola daftar Person In Charge (PIC) penanggung jawab perbaikan temuan audit di sistem SIVERA.</p>
        </div>
        <button wire:click="openCreate" id="btn-tambah-pic" class="bbtn bbtn-primary">
            <span class="material-symbols-outlined" style="font-size:18px;">person_add</span>
            Penunjukan PIC Baru
        </button>
    </div>

    {{-- Form Tambah/Edit PIC --}}
    @if($showForm)
    <div class="bcard fu1" style="padding:20px;border:1.5px solid var(--bp);">
        <h3 style="font-size:14px;font-weight:700;color:var(--btxt);margin:0 0 16px;">
            {{ $editingNik ? "Edit Data PIC: {$selectedNama} (NIK: {$editingNik})" : 'Penunjukan PIC SIVERA Baru' }}
        </h3>

        @if(!$editingNik)
        {{-- Autocomplete Employee Search Input --}}
        <div style="margin-bottom:16px;position:relative;">
            <label for="search-karyawan-pic" class="blabel">Cari Nama Karyawan dari Pusat Data <span style="color:var(--be);">*</span></label>
            <div style="position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--btxt2);font-size:18px;">search</span>
                <input type="text" id="search-karyawan-pic" wire:model.live.debounce.250ms="searchKaryawan"
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
                Karyawan tidak ditemukan atau sudah ditunjuk sebagai PIC.
            </div>
            @endif
        </div>
        @endif

        {{-- Form Details --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;background:var(--bbg,#f8fafc);padding:14px;border-radius:10px;border:1px solid var(--bbor);">
            <div>
                <label class="blabel">Nama PIC Selected</label>
                <input type="text" value="{{ $selectedNama ?: 'Belum dipilih' }}" class="binput" disabled style="background:#e2e8f0;font-weight:700;" />
            </div>
            <div>
                <label class="blabel">Departemen</label>
                <input type="text" value="{{ $selectedDept ?: '-' }}" class="binput" disabled style="background:#e2e8f0;" />
            </div>
            <div>
                <label for="pic-email" class="blabel">Email Notifikasi (Opsional)</label>
                <input type="email" id="pic-email" wire:model="email" placeholder="email@perusahaan.com" class="binput" />
                <span style="font-size:11px;color:var(--btxt2);margin-top:3px;display:block;">Digunakan untuk pengiriman email notifikasi temuan baru.</span>
                @error('email') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>
            <div style="display:flex;align-items:center;gap:10px;padding-top:10px;grid-column:1/-1;">
                <input type="checkbox" id="pic-aktif" wire:model="status_aktif" style="width:16px;height:16px;accent-color:var(--bp);" />
                <label for="pic-aktif" style="font-size:13.5px;font-weight:500;color:var(--btxt);cursor:pointer;">Status Aktif PIC SIVERA</label>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:16px;">
            <button wire:click="resetForm" class="bbtn bbtn-secondary">Batal</button>
            <button wire:click="simpan" wire:loading.attr="disabled" id="btn-simpan-pic" class="bbtn bbtn-primary">
                <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                {{ $editingNik ? 'Simpan Perubahan' : 'Penunjukan Sebagai PIC' }}
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
                       placeholder="Cari NIK, Nama PIC, atau Email..."
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

    {{-- Table PIC --}}
    <div class="bcard fu2" style="overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="btbl" style="min-width:650px;">
                <thead>
                    <tr>
                        <th>NIK / Share ID</th>
                        <th>Nama PIC SIVERA</th>
                        <th>Departemen</th>
                        <th>Email Notifikasi</th>
                        <th style="text-align:center;">Status</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pics as $p)
                    <tr>
                        <td style="font-family:monospace;font-size:12.5px;font-weight:700;color:var(--bp);">{{ $p->nik }}</td>
                        <td style="font-weight:700;">
                            {{ $p->nama }}
                        </td>
                        <td>
                            <span style="display:inline-flex;align-items:center;gap:4px;font-weight:500;">
                                <span class="material-symbols-outlined" style="font-size:15px;color:var(--btxt2);">domain</span>
                                {{ $p->departemen->nama_departemen ?? '-' }}
                            </span>
                        </td>
                        <td style="font-size:12.5px;color:var(--btxt2);">
                            @if($p->user?->email)
                                <span style="display:inline-flex;align-items:center;gap:4px;color:#0f172a;font-weight:500;">
                                    <span class="material-symbols-outlined" style="font-size:14px;color:var(--bp);">mail</span>
                                    {{ $p->user->email }}
                                </span>
                            @else
                                <span style="font-size:11px;color:#94a3b8;font-style:italic;">- (Kosong)</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($p->status_aktif)
                                <span class="bbadge bbadge-closed">Aktif</span>
                            @else
                                <span class="bbadge bbadge-open">Non-aktif</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                <button wire:click="openEdit('{{ $p->nik }}')" title="Edit Data PIC"
                                        class="bbtn bbtn-secondary bbtn-sm" style="padding:5px 8px!important;">
                                    <span class="material-symbols-outlined" style="font-size:15px;">edit</span>
                                </button>
                                <button wire:click="toggleStatus('{{ $p->nik }}')"
                                        wire:confirm="Ubah status aktif PIC '{{ $p->nama }}'?"
                                        title="Toggle Status PIC"
                                        class="bbtn bbtn-sm {{ $p->status_aktif ? 'bbadge-open' : 'bbadge-closed' }}" style="padding:5px 8px!important; cursor:pointer;">
                                    <span class="material-symbols-outlined" style="font-size:15px;">{{ $p->status_aktif ? 'person_off' : 'person' }}</span>
                                </button>
                                <button wire:click="hapusPic('{{ $p->nik }}')"
                                        wire:confirm="Hapus status PIC untuk '{{ $p->nama }}' (NIK: {{ $p->nik }}) dari Master PIC SIVERA?"
                                        title="Hapus dari Master PIC"
                                        class="bbtn bbtn-danger bbtn-sm" style="padding:5px 8px!important;">
                                    <span class="material-symbols-outlined" style="font-size:15px;">person_remove</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:32px;color:var(--btxt2);">
                            <span class="material-symbols-outlined" style="font-size:36px;display:block;margin-bottom:8px;opacity:.3;">badge</span>
                            Belum ada karyawan yang ditunjuk sebagai Master PIC di SIVERA
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pics->hasPages())
        <div style="padding:12px 16px;border-top:1px solid var(--bbor);">
            {{ $pics->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>
</div>
