<div style="display:flex;flex-direction:column;gap:24px;" class="fu">
    
    {{-- Header --}}
    <div class="bph fu1">
        <div>
            <h2 class="bph-title">Master Data Line — BOS'Q</h2>
            <p class="bph-sub">Kelola data Line produksi dan penetapan Default Auditee</p>
        </div>
        <div>
            <button wire:click="create" class="bbtn bbtn-primary bbtn-sm">
                <span class="material-symbols-outlined" style="font-size:18px;">add</span>
                Tambah Line Baru
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
            {{ $isEditing ? 'Edit Data Line' : 'Tambah Line Baru' }}
        </h3>
        <form wire:submit.prevent="save" style="display:flex;flex-direction:column;gap:14px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;" class="max-md:flex-col">
                <div>
                    <label class="blabel">Nama Line <span style="color:var(--error);">*</span></label>
                    <input type="text" wire:model="nama_line" class="binput" placeholder="Contoh: Line 1, Ergo Line 5, Husky" required />
                    @error('nama_line') <span class="berr">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="blabel">Default Auditee (Opsional)</label>
                    <select wire:model="default_auditee_id" class="binput">
                        <option value="">-- Pilih Default Auditee --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} (NIK: {{ $user->nik }})</option>
                        @endforeach
                    </select>
                    @error('default_auditee_id') <span class="berr">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:4px;">
                <button type="submit" class="bbtn bbtn-primary bbtn-sm">
                    <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                    {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Line' }}
                </button>
                @if($isEditing || $nama_line || $default_auditee_id)
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
                    <span class="material-symbols-outlined fil" style="color:var(--bp-dark);font-size:20px;">precision_manufacturing</span>
                </div>
                <div>
                    <h3 style="font-size:15px;font-weight:700;color:var(--btxt);margin:0;">Daftar Master Line</h3>
                    <p style="font-size:12px;color:var(--btxt2);margin:0;">Total data Line terdaftar</p>
                </div>
            </div>

            <div style="width:240px;">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari line..." class="binput" style="padding:6px 12px;font-size:12.5px;" />
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;text-align:left;">
                <thead>
                    <tr style="background:var(--bsur);border-bottom:1px solid var(--bbor);color:var(--btxt2);font-size:11px;text-transform:uppercase;letter-spacing:0.5px;">
                        <th style="padding:12px 16px;">ID</th>
                        <th style="padding:12px 16px;">Nama Line</th>
                        <th style="padding:12px 16px;">Default Auditee</th>
                        <th style="padding:12px 16px;text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lines as $line)
                        <tr style="border-bottom:1px solid var(--bbor);">
                            <td style="padding:12px 16px;font-weight:700;color:var(--bp);">#{{ $line->id }}</td>
                            <td style="padding:12px 16px;font-weight:600;color:var(--btxt);">{{ $line->nama_line }}</td>
                            <td style="padding:12px 16px;color:var(--btxt2);">
                                @if($line->defaultAuditee)
                                    <div style="font-weight:600;color:var(--btxt);">{{ $line->defaultAuditee->name }}</div>
                                    <div style="font-size:11px;">NIK: {{ $line->defaultAuditee->nik }}</div>
                                @else
                                    <span style="font-style:italic;color:var(--btxt2);">- Belum ditentukan -</span>
                                @endif
                            </td>
                            <td style="padding:12px 16px;text-align:center;">
                                <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                    <button wire:click="edit({{ $line->id }})" class="bbtn bbtn-secondary bbtn-sm">
                                        <span class="material-symbols-outlined" style="font-size:16px;">edit</span>
                                        Edit
                                    </button>
                                    <button wire:click="delete({{ $line->id }})" wire:confirm="Apakah Anda yakin ingin menghapus Line ini?" class="bbtn bbtn-danger bbtn-sm">
                                        <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding:32px;text-align:center;color:var(--btxt2);">
                                Belum ada data Line.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($lines->hasPages())
            <div style="padding:16px 20px;border-top:1px solid var(--bbor);">
                {{ $lines->links() }}
            </div>
        @endif
    </div>

</div>
