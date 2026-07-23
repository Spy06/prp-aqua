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
            <h2 class="bph-title">Master Departemen & Sub Area</h2>
            <p class="bph-sub">Kelola data departemen dan daftar sub area lokasi temuan</p>
        </div>
        <button wire:click="openCreate" id="btn-tambah-dept" class="bbtn bbtn-primary">
            <span class="material-symbols-outlined" style="font-size:18px;">add</span>
            Tambah Departemen
        </button>
    </div>

    {{-- Form Edit / Tambah Departemen --}}
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

    {{-- Tabel Master Departemen --}}
    <div class="bcard fu2" style="overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="btbl">
                <thead>
                    <tr>
                        <th>Nama Departemen</th>
                        <th style="text-align:center;">Daftar Sub Area</th>
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
                            <button wire:click="openSubAreaModal({{ $d->id }})" class="bbtn bbtn-secondary bbtn-sm" style="font-size:12px;padding:4px 10px;">
                                <span class="material-symbols-outlined" style="font-size:15px;color:var(--bp);">location_on</span>
                                <span>{{ $d->sub_areas_count }} Sub Area</span>
                            </button>
                        </td>
                        <td style="text-align:center;">
                            <span class="bbadge bbadge-progress">{{ $d->karyawans_count }}</span>
                        </td>
                        <td style="text-align:center;">
                            <span class="bbadge bbadge-pending">{{ $d->temuans_count }}</span>
                        </td>
                        <td style="text-align:center;">
                            <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                <button wire:click="openSubAreaModal({{ $d->id }})" title="Kelola Sub Area"
                                        class="bbtn bbtn-secondary bbtn-sm" style="padding:5px 8px!important;color:var(--bp);">
                                    <span class="material-symbols-outlined" style="font-size:15px;">tune</span>
                                </button>
                                <button wire:click="openEdit({{ $d->id }})" title="Edit Departemen"
                                        class="bbtn bbtn-secondary bbtn-sm" style="padding:5px 8px!important;">
                                    <span class="material-symbols-outlined" style="font-size:15px;">edit</span>
                                </button>
                                <button wire:click="hapus({{ $d->id }})"
                                        wire:confirm="Yakin hapus departemen '{{ $d->nama_departemen }}'?"
                                        title="Hapus Departemen"
                                        class="bbtn bbtn-danger bbtn-sm" style="padding:5px 8px!important;">
                                    <span class="material-symbols-outlined" style="font-size:15px;">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:32px;color:var(--btxt2);">
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

    {{-- ═══ MODAL KELOLA SUB AREA ═══ --}}
    @if($selectedDept)
    <div style="position:fixed;top:0;left:0;right:0;bottom:0;width:100vw;height:100vh;margin:0;z-index:99999;display:flex;align-items:center;justify-content:center;padding:16px;background:rgba(15,23,42,0.65);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);">
        <div class="bcard fu" style="width:100%;max-width:680px;max-height:90vh;display:flex;flex-direction:column;overflow:hidden;box-shadow:0 25px 50px -12px rgba(0,0,0,0.35);">
            
            {{-- Modal Header --}}
            <div style="padding:16px 20px;border-bottom:1px solid var(--bbor);display:flex;align-items:center;justify-content:space-between;background:var(--bsur);">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:36px;height:36px;border-radius:10px;background:var(--bp-light);color:var(--bp-dark);display:flex;align-items:center;justify-content:center;">
                        <span class="material-symbols-outlined" style="font-size:20px;">location_on</span>
                    </div>
                    <div>
                        <h3 style="margin:0;font-size:15px;font-weight:700;color:var(--btxt);">Sub Area — {{ $selectedDept->nama_departemen }}</h3>
                        <p style="margin:0;font-size:12px;color:var(--btxt2);">Kelola daftar sub area untuk lokasi temuan di departemen ini</p>
                    </div>
                </div>
                <button wire:click="closeSubAreaModal" style="background:none;border:none;color:var(--btxt2);cursor:pointer;padding:4px;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                    <span class="material-symbols-outlined" style="font-size:20px;">close</span>
                </button>
            </div>

            {{-- Modal Body --}}
            <div style="padding:20px;overflow-y:auto;flex:1;display:flex;flex-direction:column;gap:16px;">

                @if(session('subarea_success'))
                <div class="balert balert-success" style="margin:0;">
                    <span class="material-symbols-outlined fil" style="font-size:18px;flex-shrink:0;">check_circle</span>
                    <span>{{ session('subarea_success') }}</span>
                </div>
                @endif

                {{-- Form Tambah Sub Area --}}
                <div style="background:var(--bsur);padding:14px;border-radius:10px;border:1px solid var(--bbor);">
                    <label for="input-new-subarea" class="blabel" style="margin-bottom:6px;display:block;">Tambah Sub Area Baru</label>
                    <div style="display:flex;gap:8px;">
                        <input type="text" id="input-new-subarea" wire:model="newSubAreaName"
                               wire:keydown.enter="tambahSubArea"
                               placeholder="Contoh: Line 1, Ruang Laboratorium, Gudang A"
                               class="binput" style="flex:1;" />
                        <button wire:click="tambahSubArea" class="bbtn bbtn-primary bbtn-sm" style="flex-shrink:0;">
                            <span class="material-symbols-outlined" style="font-size:16px;">add</span>
                            Tambah
                        </button>
                    </div>
                    @error('newSubAreaName') <p class="berr-msg" style="margin-top:4px;">{{ $message }}</p> @enderror
                </div>

                {{-- Cek apakah opsi 'Others' sudah ada --}}
                @php
                    $hasOthers = $subAreasList->contains(fn($sa) => strtolower($sa->nama_sub_area) === 'others');
                @endphp
                @if(!$hasOthers)
                <div style="background:#fff8e1;border:1px solid #ffe082;padding:10px 14px;border-radius:8px;display:flex;align-items:center;justify-content:space-between;gap:10px;">
                    <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:#896200;">
                        <span class="material-symbols-outlined" style="font-size:18px;">info</span>
                        <span>Opsi <strong>Others</strong> belum tersedia di departemen ini.</span>
                    </div>
                    <button wire:click="tambahOpsiOthers" class="bbtn bbtn-secondary bbtn-sm" style="background:#fff;border-color:#ffd54f;color:#896200;font-weight:700;">
                        + Tambah Opsi Others
                    </button>
                </div>
                @endif

                {{-- List Sub Area --}}
                <div>
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                        <span style="font-size:12px;font-weight:700;color:var(--btxt);text-transform:uppercase;letter-spacing:0.5px;">Daftar Sub Area ({{ $subAreasList->count() }})</span>
                    </div>

                    <div style="border:1px solid var(--bbor);border-radius:10px;overflow:hidden;background:var(--bcard);">
                        @forelse($subAreasList as $index => $sa)
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;{{ !$loop->last ? 'border-bottom:1px solid var(--bbor);' : '' }}">
                            @if($editingSubAreaId === $sa->id)
                                {{-- Inline Edit Mode --}}
                                <div style="display:flex;gap:6px;flex:1;margin-right:8px;">
                                    <input type="text" wire:model="editingSubAreaName" wire:keydown.enter="simpanEditSubArea" class="binput" style="padding:4px 8px;font-size:13px;" />
                                    <button wire:click="simpanEditSubArea" class="bbtn bbtn-primary bbtn-sm" style="padding:4px 8px!important;">
                                        <span class="material-symbols-outlined" style="font-size:15px;">check</span>
                                    </button>
                                    <button wire:click="cancelEditSubArea" class="bbtn bbtn-secondary bbtn-sm" style="padding:4px 8px!important;">
                                        <span class="material-symbols-outlined" style="font-size:15px;">close</span>
                                    </button>
                                </div>
                            @else
                                {{-- Normal Display Mode --}}
                                <div style="display:flex;align-items:center;gap:8px;overflow:hidden;">
                                    <span style="font-size:11px;font-weight:700;color:var(--btxt2);width:20px;">#{{ $index + 1 }}</span>
                                    <span style="font-size:13px;font-weight:600;color:var(--btxt);" class="truncate">
                                        {{ $sa->nama_sub_area }}
                                    </span>
                                    @if(strtolower($sa->nama_sub_area) === 'others')
                                        <span style="font-size:10px;font-weight:700;background:var(--bp-light);color:var(--bp-dark);padding:1px 6px;border-radius:4px;">Default</span>
                                    @endif
                                </div>
                                <div style="display:flex;align-items:center;gap:4px;">
                                    <button wire:click="editSubArea({{ $sa->id }})" title="Edit nama" class="bbtn bbtn-secondary bbtn-sm" style="padding:4px 6px!important;">
                                        <span class="material-symbols-outlined" style="font-size:14px;">edit</span>
                                    </button>
                                    <button wire:click="hapusSubArea({{ $sa->id }})" wire:confirm="Hapus sub area '{{ $sa->nama_sub_area }}'?" title="Hapus sub area" class="bbtn bbtn-danger bbtn-sm" style="padding:4px 6px!important;">
                                        <span class="material-symbols-outlined" style="font-size:14px;">delete</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                        @empty
                        <div style="padding:24px;text-align:center;color:var(--btxt2);font-size:13px;">
                            Belum ada sub area untuk departemen ini.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div style="padding:12px 20px;border-top:1px solid var(--bbor);display:flex;justify-content:flex-end;background:var(--bsur);">
                <button wire:click="closeSubAreaModal" class="bbtn bbtn-secondary bbtn-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
