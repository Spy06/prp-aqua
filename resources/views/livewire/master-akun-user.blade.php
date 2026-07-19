<div class="fu" id="master-akun-user-container" style="display:flex;flex-direction:column;gap:16px;">

    @if(session('success'))
    <div class="balert balert-success">
        <span class="material-symbols-outlined fil" style="font-size:18px;flex-shrink:0;">check_circle</span>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="bph">
        <div>
            <h2 class="bph-title">Manajemen Akun User</h2>
            <p class="bph-sub">Kelola akun login karyawan dan QA</p>
        </div>
        <button wire:click="openCreate" id="btn-buat-akun" class="bbtn bbtn-primary">
            <span class="material-symbols-outlined" style="font-size:18px;">manage_accounts</span>
            Buat Akun Baru
        </button>
    </div>

    {{-- Form: Buat Akun Baru --}}
    @if($showFormCreate)
    <div class="bcard fu1" style="padding:20px;">
        <h3 style="font-size:14px;font-weight:700;color:var(--btxt);margin:0 0 4px;">Buat Akun User Baru</h3>
        <p style="font-size:12.5px;color:var(--btxt2);margin:0 0 16px;">Akun dibuat berdasarkan NIK karyawan yang sudah terdaftar.</p>

        <div class="balert balert-warn" style="margin-bottom:16px;">
            <span class="material-symbols-outlined" style="font-size:18px;flex-shrink:0;">info</span>
            <span>Akun hanya bisa dibuat untuk NIK yang <strong>terdaftar dan aktif</strong> di Master Karyawan. Jika NIK tidak ada, tambahkan dulu di tab Master Karyawan.</span>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;">
            {{-- NIK --}}
            <div style="grid-column:1/-1;">
                <label for="form-nik-baru" class="blabel">NIK Karyawan <span style="color:var(--be);">*</span></label>
                <input type="text" id="form-nik-baru" wire:model.live="nik_baru"
                       placeholder="Masukkan NIK untuk mencari karyawan..."
                       class="binput" />
                @error('nik_baru') <p class="berr-msg">{{ $message }}</p> @enderror

                @if($nikSearchResult)
                <div class="balert balert-success" style="margin-top:8px;margin-bottom:0;padding:8px 12px;">
                    <span class="material-symbols-outlined fil" style="font-size:16px;flex-shrink:0;">check_circle</span>
                    Karyawan ditemukan: <strong>{{ $nikSearchResult }}</strong> — siap dibuatkan akun.
                </div>
                @elseif($nikSearchError)
                <div class="balert balert-error" style="margin-top:8px;margin-bottom:0;padding:8px 12px;">
                    <span class="material-symbols-outlined fil" style="font-size:16px;flex-shrink:0;">error</span>
                    {{ $nikSearchError }}
                </div>
                @endif
            </div>

            {{-- Role --}}
            <div>
                <label for="form-role" class="blabel">Role <span style="color:var(--be);">*</span></label>
                <select id="form-role" wire:model="role_baru" class="binput">
                    <option value="karyawan">Karyawan (dapat melaporkan & tindak lanjut)</option>
                    <option value="qa">QA (akses penuh termasuk verifikasi & master data)</option>
                </select>
                @error('role_baru') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>

            {{-- No. WhatsApp --}}
            <div>
                <label for="form-wa" class="blabel">No. WhatsApp <span style="color:var(--be);">*</span></label>
                <input type="text" id="form-wa" wire:model="no_whatsapp_baru"
                       placeholder="628xxxxxxxxxx"
                       class="binput" />
                <p style="font-size:11.5px;color:var(--btxt2);margin-top:4px;">Format: 628xxx (diawali 628 tanpa +)</p>
                @error('no_whatsapp_baru') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:16px;">
            <button wire:click="$set('showFormCreate', false)" class="bbtn bbtn-secondary">Batal</button>
            <button wire:click="buatAkun" wire:loading.attr="disabled" id="btn-submit-akun"
                    class="bbtn bbtn-primary"
                    {{ !$nikSearchResult ? 'disabled' : '' }}
                    style="{{ !$nikSearchResult ? 'opacity:.5;cursor:not-allowed;' : '' }}">
                <span class="material-symbols-outlined" style="font-size:16px;">person_add</span>
                Buat Akun
            </button>
        </div>
    </div>
    @endif

    {{-- Form: Edit Akun --}}
    @if($showFormEdit)
    <div class="bcard fu1" style="padding:20px;">
        <h3 style="font-size:14px;font-weight:700;color:var(--btxt);margin:0 0 16px;">Edit Akun User</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;">
            <div>
                <label for="edit-role" class="blabel">Role</label>
                <select id="edit-role" wire:model="edit_role" class="binput">
                    <option value="karyawan">Karyawan</option>
                    <option value="qa">QA</option>
                </select>
                @error('edit_role') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="edit-wa" class="blabel">No. WhatsApp</label>
                <input type="text" id="edit-wa" wire:model="edit_no_whatsapp"
                       placeholder="628xxxxxxxxxx"
                       class="binput" />
                @error('edit_no_whatsapp') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:16px;">
            <button wire:click="$set('showFormEdit', false)" class="bbtn bbtn-secondary">Batal</button>
            <button wire:click="simpanEdit" wire:loading.attr="disabled" class="bbtn bbtn-primary">
                <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                Simpan Perubahan
            </button>
        </div>
    </div>
    @endif

    {{-- Search --}}
    <div style="max-width:340px;">
        <div style="position:relative;">
            <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--btxt2);font-size:18px;">search</span>
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Cari NIK atau nama..."
                   class="binput" style="padding-left:40px;" />
        </div>
    </div>

    {{-- Table --}}
    <div class="bcard fu2" style="overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="btbl" style="min-width:520px;">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Departemen</th>
                        <th style="text-align:center;">Role</th>
                        <th>No. WA</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td style="font-family:monospace;font-size:12.5px;">{{ $u->nik }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div class="qs-av" style="width:30px;height:30px;font-size:11px;flex-shrink:0;">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
                                <span style="font-weight:600;font-size:13.5px;">{{ $u->name }}</span>
                            </div>
                        </td>
                        <td style="font-size:13px;color:var(--btxt2);">{{ $u->karyawan?->departemen?->nama_departemen ?? '-' }}</td>
                        <td style="text-align:center;">
                            @if($u->role === 'qa')
                                <span class="bbadge bbadge-pending">QA</span>
                            @else
                                <span class="bbadge bbadge-progress">Karyawan</span>
                            @endif
                        </td>
                        <td style="font-family:monospace;font-size:12px;color:var(--btxt2);">{{ $u->no_whatsapp ?? '-' }}</td>
                        <td style="text-align:center;">
                            <button wire:click="openEdit({{ $u->id }})" title="Edit"
                                    class="bbtn bbtn-secondary bbtn-sm" style="padding:5px 8px!important;">
                                <span class="material-symbols-outlined" style="font-size:15px;">edit</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:32px;color:var(--btxt2);">
                            <span class="material-symbols-outlined" style="font-size:36px;display:block;margin-bottom:8px;opacity:.3;">manage_accounts</span>
                            Tidak ada akun user ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div style="padding:12px 16px;border-top:1px solid var(--bbor);">
            {{ $users->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>
</div>