<div class="fu" id="master-elemen-qfs-container" style="display:flex;flex-direction:column;gap:16px;">

    @if(session('success'))
    <div class="balert balert-success">
        <span class="material-symbols-outlined fil" style="font-size:18px;flex-shrink:0;">check_circle</span>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="balert balert-error">
        <span class="material-symbols-outlined fil" style="font-size:18px;flex-shrink:0;">error</span>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Page Header --}}
    <div class="bph">
        <div>
            <h2 class="bph-title">Master Elemen QFS</h2>
            <p class="bph-sub">Kelola elemen Quality & Food Safety (QFS) untuk standar observasi BOS'Q</p>
        </div>
        <button wire:click="openCreate" id="btn-tambah-elemen" class="bbtn bbtn-primary">
            <span class="material-symbols-outlined" style="font-size:18px;">add</span>
            Tambah Elemen QFS
        </button>
    </div>

    {{-- Form Tambah/Edit --}}
    @if($showForm)
    <div class="bcard fu1" style="padding:20px;">
        <h3 style="font-size:14px;font-weight:700;color:var(--btxt);margin:0 0 16px;">
            {{ $editingId ? 'Edit Elemen QFS' : 'Tambah Elemen QFS Baru' }}
        </h3>
        <div style="display:grid;grid-template-columns:120px 1.2fr 2fr;gap:14px;max-width:860px;">
            <div>
                <label for="form-custom-id" class="blabel">ID Elemen</label>
                <input type="number" id="form-custom-id" wire:model="customId" min="1"
                       placeholder="Auto"
                       class="binput" style="font-family:monospace;font-weight:700;" />
                <p style="font-size:11px;color:var(--btxt2);margin-top:2px;">(Kosongkan jika auto)</p>
                @error('customId') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="form-nama-elemen" class="blabel">Nama Elemen QFS <span style="color:var(--be);">*</span></label>
                <input type="text" id="form-nama-elemen" wire:model="nama_elemen"
                       placeholder="Contoh: Personal Hygiene, Storage & Warehousing"
                       class="binput" />
                @error('nama_elemen') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="form-deskripsi" class="blabel">Deskripsi / Catatan (Opsional)</label>
                <input type="text" id="form-deskripsi" wire:model="deskripsi"
                       placeholder="Penjelasan detail cakupan elemen QFS..."
                       class="binput" />
                @error('deskripsi') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:16px;">
            <button wire:click="resetForm" class="bbtn bbtn-secondary">Batal</button>
            <button wire:click="simpan" wire:loading.attr="disabled" id="btn-simpan-elemen" class="bbtn bbtn-primary">
                <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                Simpan
            </button>
        </div>
    </div>
    @endif

    {{-- Search Bar --}}
    <div style="display:flex;align-items:center;gap:12px;">
        <div style="max-width:320px;flex:1;position:relative;">
            <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--btxt2);font-size:18px;">search</span>
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Cari nama atau deskripsi elemen..."
                   class="binput" style="padding-left:40px;" />
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bcard fu2" style="overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="btbl">
                <thead>
                    <tr>
                        <th style="width:80px;text-align:left;">ID</th>
                        <th style="width:30%;text-align:left;">Nama Elemen QFS</th>
                        <th style="text-align:left;">Deskripsi</th>
                        <th style="width:110px;text-align:center;">Dipakai</th>
                        <th style="width:120px;text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($elemens as $el)
                    <tr>
                        <td>
                            <span style="font-family:monospace;font-size:12px;font-weight:700;color:var(--bp);background:var(--bp-light);padding:3px 8px;border-radius:6px;">#{{ $el->id }}</span>
                        </td>
                        <td style="font-weight:600;color:var(--btxt);">{{ $el->nama_elemen }}</td>
                        <td style="color:var(--btxt2);max-width:360px;">{{ $el->deskripsi ?? '-' }}</td>
                        <td style="text-align:center;">
                            <span class="bbadge bbadge-progress">{{ $el->temuans_count }}</span>
                        </td>
                        <td style="text-align:center;">
                            <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                <button wire:click="openEdit({{ $el->id }})" title="Edit"
                                        class="bbtn bbtn-secondary bbtn-sm" style="padding:5px 8px!important;">
                                    <span class="material-symbols-outlined" style="font-size:15px;">edit</span>
                                </button>
                                <button wire:click="hapus({{ $el->id }})"
                                        wire:confirm="Hapus elemen QFS '{{ $el->nama_elemen }}'?"
                                        class="bbtn bbtn-danger bbtn-sm" style="padding:5px 8px!important;">
                                    <span class="material-symbols-outlined" style="font-size:15px;">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:32px;color:var(--btxt2);">
                            <span class="material-symbols-outlined" style="font-size:36px;display:block;margin-bottom:8px;opacity:.3;">category</span>
                            Belum ada data Elemen QFS
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($elemens->hasPages())
        <div style="padding:12px 16px;border-top:1px solid var(--bbor);">
            {{ $elemens->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>
</div>
