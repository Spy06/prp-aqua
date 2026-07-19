<div class="fu" id="master-klausul-container" style="display:flex;flex-direction:column;gap:16px;">

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
            <h2 class="bph-title">Master Klausul PRP</h2>
            <p class="bph-sub">Kelola klausul-klausul Program Rujukan Produksi (PRP)</p>
        </div>
        <button wire:click="openCreate" id="btn-tambah-klausul" class="bbtn bbtn-primary">
            <span class="material-symbols-outlined" style="font-size:18px;">add</span>
            Tambah Klausul
        </button>
    </div>

    @if($showForm)
    <div class="bcard fu1" style="padding:20px;">
        <h3 style="font-size:14px;font-weight:700;color:var(--btxt);margin:0 0 16px;">
            {{ $editingId ? 'Edit Klausul' : 'Tambah Klausul Baru' }}
        </h3>
        <div style="display:grid;grid-template-columns:1fr 2fr;gap:12px;max-width:600px;">
            <div>
                <label for="form-kode" class="blabel">Kode Klausul <span style="color:var(--be);">*</span></label>
                <input type="text" id="form-kode" wire:model="kode_klausul"
                       placeholder="Contoh: PRP-4.1"
                       class="binput" style="font-family:monospace;" />
                @error('kode_klausul') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="form-nama-klausul" class="blabel">Nama Klausul <span style="color:var(--be);">*</span></label>
                <input type="text" id="form-nama-klausul" wire:model="nama_klausul"
                       placeholder="Deskripsi klausul PRP"
                       class="binput" />
                @error('nama_klausul') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:16px;">
            <button wire:click="resetForm" class="bbtn bbtn-secondary">Batal</button>
            <button wire:click="simpan" wire:loading.attr="disabled" id="btn-simpan-klausul" class="bbtn bbtn-primary">
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
                        <th style="width:130px;">Kode</th>
                        <th>Nama Klausul</th>
                        <th style="text-align:center;">Dipakai</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($klausuls as $k)
                    <tr>
                        <td>
                            <span style="font-family:monospace;font-size:12px;font-weight:700;color:var(--bp);background:var(--bp-light);padding:3px 8px;border-radius:6px;">{{ $k->kode_klausul }}</span>
                        </td>
                        <td>{{ $k->nama_klausul }}</td>
                        <td style="text-align:center;">
                            <span class="bbadge bbadge-progress">{{ $k->temuans_count }}</span>
                        </td>
                        <td style="text-align:center;">
                            <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                <button wire:click="openEdit({{ $k->id }})" title="Edit"
                                        class="bbtn bbtn-secondary bbtn-sm" style="padding:5px 8px!important;">
                                    <span class="material-symbols-outlined" style="font-size:15px;">edit</span>
                                </button>
                                <button wire:click="hapus({{ $k->id }})"
                                        wire:confirm="Hapus klausul '{{ $k->kode_klausul }}'?"
                                        class="bbtn bbtn-danger bbtn-sm" style="padding:5px 8px!important;">
                                    <span class="material-symbols-outlined" style="font-size:15px;">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;padding:32px;color:var(--btxt2);">
                            <span class="material-symbols-outlined" style="font-size:36px;display:block;margin-bottom:8px;opacity:.3;">rule</span>
                            Belum ada klausul
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($klausuls->hasPages())
        <div style="padding:12px 16px;border-top:1px solid var(--bbor);">
            {{ $klausuls->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>
</div>
