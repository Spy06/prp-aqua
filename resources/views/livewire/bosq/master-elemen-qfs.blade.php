<div style="display:flex;flex-direction:column;gap:24px;" class="fu">
    
    {{-- Header --}}
    <div class="bph fu1">
        <div>
            <h2 class="bph-title">Master Data Elemen QFS — BOS'Q</h2>
            <p class="bph-sub">Kelola elemen Quality & Food Safety (QFS) untuk standar observasi BOS'Q</p>
        </div>
        <div>
            <button wire:click="create" class="bbtn bbtn-primary bbtn-sm">
                <span class="material-symbols-outlined" style="font-size:18px;">add</span>
                Tambah Elemen QFS Baru
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
            {{ $isEditing ? 'Edit Elemen QFS' : 'Tambah Elemen QFS Baru' }}
        </h3>
        <form wire:submit.prevent="save" style="display:flex;flex-direction:column;gap:14px;">
            <div>
                <label class="blabel">Nama Elemen QFS <span style="color:var(--error);">*</span></label>
                <input type="text" wire:model="nama_elemen" class="binput" placeholder="Contoh: Personal Hygiene, Pest Control, Storage & Warehousing" required />
                @error('nama_elemen') <span class="berr">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="blabel">Deskripsi / Catatan (Opsional)</label>
                <textarea wire:model="deskripsi" class="binput" rows="3" placeholder="Tuliskan penjelaskan detail cakupan elemen QFS ini..."></textarea>
                @error('deskripsi') <span class="berr">{{ $message }}</span> @enderror
            </div>

            <div style="display:flex;gap:10px;margin-top:4px;">
                <button type="submit" class="bbtn bbtn-primary bbtn-sm">
                    <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                    {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Elemen' }}
                </button>
                @if($isEditing || $nama_elemen || $deskripsi)
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
                    <span class="material-symbols-outlined fil" style="color:var(--bp-dark);font-size:20px;">category</span>
                </div>
                <div>
                    <h3 style="font-size:15px;font-weight:700;color:var(--btxt);margin:0;">Daftar Master Elemen QFS</h3>
                    <p style="font-size:12px;color:var(--btxt2);margin:0;">Total elemen QFS terdaftar</p>
                </div>
            </div>

            <div style="width:240px;">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari elemen..." class="binput" style="padding:6px 12px;font-size:12.5px;" />
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;text-align:left;">
                <thead>
                    <tr style="background:var(--bsur);border-bottom:1px solid var(--bbor);color:var(--btxt2);font-size:11px;text-transform:uppercase;letter-spacing:0.5px;">
                        <th style="padding:12px 16px;">ID</th>
                        <th style="padding:12px 16px;">Nama Elemen QFS</th>
                        <th style="padding:12px 16px;">Deskripsi</th>
                        <th style="padding:12px 16px;text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($elemens as $el)
                        <tr style="border-bottom:1px solid var(--bbor);">
                            <td style="padding:12px 16px;font-weight:700;color:var(--bp);">#{{ $el->id }}</td>
                            <td style="padding:12px 16px;font-weight:600;color:var(--btxt);">{{ $el->nama_elemen }}</td>
                            <td style="padding:12px 16px;color:var(--btxt2);max-width:320px;">{{ $el->deskripsi ?? '-' }}</td>
                            <td style="padding:12px 16px;text-align:center;">
                                <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                    <button wire:click="edit({{ $el->id }})" class="bbtn bbtn-secondary bbtn-sm">
                                        <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                                        Edit
                                    </button>
                                    <button wire:click="delete({{ $el->id }})" wire:confirm="Apakah Anda yakin ingin menghapus Elemen ini?" class="bbtn bbtn-danger bbtn-sm">
                                        <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding:32px;text-align:center;color:var(--btxt2);">
                                Belum ada data Elemen QFS.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($elemens->hasPages())
            <div style="padding:16px 20px;border-top:1px solid var(--bbor);">
                {{ $elemens->links() }}
            </div>
        @endif
    </div>

</div>
