<div class="fu" id="master-departemen-container" style="display:flex;flex-direction:column;gap:16px;">

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

    <div class="bph">
        <div>
            <h2 class="bph-title">Master Departemen</h2>
            <p class="bph-sub">Kelola data departemen dalam sistem</p>
        </div>
        <button wire:click="openCreate" id="btn-tambah-dept" class="bbtn bbtn-primary">
            <span class="material-symbols-outlined" style="font-size:18px;">add</span>
            Tambah Departemen
        </button>
    </div>

    @if($showForm)
    <div class="bcard fu1" style="padding:20px;">
        <h3 style="font-size:14px;font-weight:700;color:var(--btxt);margin:0 0 16px;">
            {{ $editingId ? 'Edit Departemen' : 'Tambah Departemen Baru' }}
        </h3>
        <div style="max-width:400px;">
            <label for="form-dept-nama" class="blabel">Nama Departemen <span style="color:var(--be);">*</span></label>
            <input type="text" id="form-dept-nama" wire:model="nama_departemen"
                   placeholder="Contoh: Quality Assurance"
                   class="binput" />
            @error('nama_departemen') <p class="berr-msg">{{ $message }}</p> @enderror
        </div>
        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:16px;">
            <button wire:click="resetForm" class="bbtn bbtn-secondary">Batal</button>
            <button wire:click="simpan" wire:loading.attr="disabled" id="btn-simpan-dept" class="bbtn bbtn-primary">
                <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                Simpan
            </button>
        </div>
    </div>
    @endif

    <div class="bcard fu2" style="overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="btbl">
                <thead>
                    <tr>
                        <th>Nama Departemen</th>
                        <th style="text-align:center;">Karyawan</th>
                        <th style="text-align:center;">Temuan</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departemens as $d)
                    <tr>
                        <td style="font-weight:600;">{{ $d->nama_departemen }}</td>
                        <td style="text-align:center;">
                            <span class="bbadge bbadge-progress">{{ $d->karyawans_count }}</span>
                        </td>
                        <td style="text-align:center;">
                            <span class="bbadge bbadge-pending">{{ $d->temuans_count }}</span>
                        </td>
                        <td style="text-align:center;">
                            <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                <button wire:click="openEdit({{ $d->id }})" title="Edit"
                                        class="bbtn bbtn-secondary bbtn-sm" style="padding:5px 8px!important;">
                                    <span class="material-symbols-outlined" style="font-size:15px;">edit</span>
                                </button>
                                <button wire:click="hapus({{ $d->id }})"
                                        wire:confirm="Yakin hapus departemen '{{ $d->nama_departemen }}'?"
                                        title="Hapus"
                                        class="bbtn bbtn-danger bbtn-sm" style="padding:5px 8px!important;">
                                    <span class="material-symbols-outlined" style="font-size:15px;">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;padding:32px;color:var(--btxt2);">
                            <span class="material-symbols-outlined" style="font-size:36px;display:block;margin-bottom:8px;opacity:.3;">domain</span>
                            Belum ada departemen
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($departemens->hasPages())
        <div style="padding:12px 16px;border-top:1px solid var(--bbor);">
            {{ $departemens->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>
</div>
