<div class="fu" id="master-akun-user-container" style="display:flex;flex-direction:column;gap:16px;">

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
            <h2 class="bph-title">Manajemen Akun User</h2>
            <p class="bph-sub">Kelola data login, NIK, departemen, role akses (Karyawan / QA), dan password user</p>
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
            <span>Akun hanya bisa dibuat untuk NIK yang <strong>terdaftar dan aktif</strong> di Master PIC. Jika NIK belum ada, tambahkan dulu di tab Master PIC.</span>
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
            <div style="grid-column:1/-1;">
                <label for="form-role" class="blabel">Role Access <span style="color:var(--be);">*</span></label>
                <select id="form-role" wire:model="role_baru" class="binput">
                    <option value="karyawan">Karyawan (Pelapor / PIC)</option>
                    <option value="qa">QA (QA Admin / Verifikator)</option>
                </select>
                @error('role_baru') <p class="berr-msg">{{ $message }}</p> @enderror
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
    <div class="bcard fu1" style="padding:20px;border:1.5px solid var(--bp);">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
            <span class="material-symbols-outlined" style="color:var(--bp);font-size:24px;">manage_accounts</span>
            <div>
                <h3 style="font-size:15px;font-weight:700;color:var(--btxt);margin:0;">Edit Data Akun & Akses User</h3>
                <p style="font-size:12px;color:var(--btxt2);margin:0;">Anda dapat mengedit NIK, Nama, Email, Departemen, dan Role Akses (Karyawan / QA) pengguna ini.</p>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;">
            {{-- Edit NIK --}}
            <div>
                <label for="edit-nik" class="blabel">NIK User / Karyawan <span style="color:var(--be);">*</span></label>
                <input type="text" id="edit-nik" wire:model="edit_nik" class="binput" placeholder="Masukkan NIK..." />
                @error('edit_nik') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>

            {{-- Edit Nama --}}
            <div>
                <label for="edit-nama" class="blabel">Nama Lengkap <span style="color:var(--be);">*</span></label>
                <input type="text" id="edit-nama" wire:model="edit_nama" class="binput" placeholder="Masukkan nama..." />
                @error('edit_nama') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>

            {{-- Edit Email --}}
            <div>
                <label for="edit-email" class="blabel">Email Perusahaan</label>
                <input type="email" id="edit-email" wire:model="edit_email" class="binput" placeholder="nama@namaperusahaan.com" />
                @error('edit_email') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>

            {{-- Edit Departemen --}}
            <div>
                <label for="edit-dept" class="blabel">Departemen</label>
                <select id="edit-dept" wire:model="edit_departemen_id" class="binput">
                    <option value="">-- Tanpa Departemen --</option>
                    @foreach($departemens as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                    @endforeach
                </select>
                @error('edit_departemen_id') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>
            {{-- Edit Role --}}
            <div>
                <label for="edit-role" class="blabel">Role Access <span style="color:var(--be);">*</span></label>
                <select id="edit-role" wire:model="edit_role" class="binput">
                    <option value="karyawan">Karyawan (Pelapor / PIC)</option>
                    <option value="qa">QA (QA Admin / Verifikator)</option>
                </select>
                @error('edit_role') <p class="berr-msg">{{ $message }}</p> @enderror
            </div>

            {{-- Edit Password --}}
            <div>
                <label for="edit-password" class="blabel">Password Akun User <span style="color:var(--be);">*</span></label>
                <input type="text" id="edit-password" wire:model="edit_password" class="binput" placeholder="Masukkan password user..." />
                <span style="font-size:11px;color:var(--btxt2);margin-top:3px;display:block;">Default awal terisi NIK saat ini. Super Admin dapat langsung menghapus & menggantinya secara bebas.</span>
                @error('edit_password') <p class="berr-msg">{{ $message }}</p> @enderror

                <div style="margin-top:8px;">
                    <button type="button" wire:click="resetFormPasswordToNik"
                            wire:confirm="Yakin ingin mereset password akun '{{ $edit_nama }}' kembali ke NIK default ({{ $edit_nik }})?"
                            class="bbtn bbtn-secondary bbtn-sm" style="color:#d97706;border-color:#fde68a;background:#fffbeb;font-size:12px;padding:6px 12px!important;">
                        <span class="material-symbols-outlined" style="font-size:15px;color:#d97706;">lock_reset</span>
                        Reset Password ke NIK Default
                    </button>
                </div>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:18px;">
            <button wire:click="$set('showFormEdit', false)" class="bbtn bbtn-secondary">Batal</button>
            <button wire:click="simpanEdit" wire:loading.attr="disabled" class="bbtn bbtn-primary">
                <span class="material-symbols-outlined" style="font-size:16px;">save</span>
                Simpan Perubahan
            </button>
        </div>
    </div>
    @endif

    {{-- Search & Filter Bar --}}
    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        {{-- Search Input --}}
        <div style="max-width:320px;flex:1;">
            <div style="position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--btxt2);font-size:18px;">search</span>
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="Cari NIK, nama, atau email..."
                       class="binput" style="padding-left:40px;" />
            </div>
        </div>

        {{-- Filter Departemen --}}
        <div style="min-width:220px;">
            <select wire:model.live="filterDepartemen" class="binput">
                <option value="">-- Semua Departemen --</option>
                @foreach($departemens as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Table Akun User --}}
    <div class="bcard fu2" style="overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="btbl" style="min-width:700px;">
                <thead>
                    <tr>
                        <th>User / Karyawan</th>
                        <th>Departemen</th>
                        <th style="text-align:center;">Role Access</th>
                        <th style="text-align:center;">Status Akun</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div class="qs-av" style="width:36px;height:36px;font-size:14px;background:var(--bp);color:#fff;font-weight:700;display:flex;align-items:center;justify-content:center;border-radius:10px;">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:700;color:var(--btxt);font-size:13.5px;">{{ $u->name }}</div>
                                    <div style="font-size:11.5px;color:var(--btxt2);display:flex;align-items:center;gap:8px;">
                                        <span style="font-family:monospace;font-weight:600;color:var(--bp);">NIK: {{ $u->nik ?? '-' }}</span>
                                        @if($u->email)
                                            <span>• {{ $u->email }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:13px;color:var(--btxt2);">{{ $u->karyawan?->departemen?->nama_departemen ?? '-' }}</td>
                        <td style="text-align:center;">
                            @if($u->role === 'superadmin')
                                <span class="bbadge" style="background:linear-gradient(135deg, #7c3aed, #4f46e5);color:#ffffff;font-weight:700;padding:5px 12px;border-radius:20px;box-shadow:0 2px 6px rgba(124,58,237,0.3);display:inline-flex;align-items:center;gap:4px;">
                                    <span class="material-symbols-outlined" style="font-size:14px;">shield</span>
                                    Super Admin
                                </span>
                            @elseif($u->role === 'qa')
                                <span class="bbadge" style="background:#dcfce7;color:#15803d;border:1px solid #86efac;font-weight:700;padding:5px 12px;border-radius:20px;display:inline-flex;align-items:center;gap:4px;">
                                    <span class="material-symbols-outlined" style="font-size:14px;color:#16a34a;">admin_panel_settings</span>
                                    QA Admin
                                </span>
                            @else
                                <span class="bbadge" style="background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;font-weight:600;padding:5px 12px;border-radius:20px;display:inline-flex;align-items:center;gap:4px;">
                                    <span class="material-symbols-outlined" style="font-size:14px;color:#0284c7;">person</span>
                                    Karyawan
                                </span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @php
                                $isAktif = $u->karyawan?->status_aktif ?? true;
                            @endphp
                            @if($isAktif)
                                <span class="bbadge bbadge-closed">AKTIF</span>
                            @else
                                <span class="bbadge bbadge-open">NON-AKTIF</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                <button wire:click="openEdit({{ $u->id }})" title="Edit Akun, Role & Set Password Custom"
                                        class="bbtn bbtn-secondary bbtn-sm" style="padding:5px 8px!important;">
                                    <span class="material-symbols-outlined" style="font-size:15px;color:var(--bp);">edit</span>
                                </button>
                                <button wire:click="toggleStatusAkun({{ $u->id }})"
                                        wire:confirm="Ubah status aktif login akun '{{ $u->name }}'?"
                                        title="Aktifkan / Nonaktifkan Akun Login"
                                        class="bbtn bbtn-sm {{ ($u->karyawan?->status_aktif ?? true) ? 'bbadge-open' : 'bbadge-closed' }}" style="padding:5px 8px!important; cursor:pointer;">
                                    <span class="material-symbols-outlined" style="font-size:15px;">{{ ($u->karyawan?->status_aktif ?? true) ? 'person_off' : 'person' }}</span>
                                </button>
                                <button wire:click="hapusAkun({{ $u->id }})"
                                        wire:confirm="Yakin ingin menghapus akun '{{ $u->name }}' (NIK: {{ $u->nik }})?"
                                        title="Hapus Akun"
                                        class="bbtn bbtn-danger bbtn-sm" style="padding:5px 8px!important;">
                                    <span class="material-symbols-outlined" style="font-size:15px;">delete</span>
                                </button>
                            </div>
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