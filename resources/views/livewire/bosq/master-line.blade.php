<div class="fu" id="master-line-container" style="display:flex;flex-direction:column;gap:18px;">

    {{-- Page Header --}}
    <div class="bph">
        <div>
            <h2 class="bph-title">Master Line & PIC Observasi</h2>
            <p class="bph-sub">Kelola penunjukan PIC penanggung jawab per Sub Area & Departemen dalam sistem BOS'Q</p>
        </div>
    </div>

    {{-- Flash Alerts --}}
    @if (session()->has('success'))
        <div class="balert balert-success fu">
            <span class="material-symbols-outlined fil" style="font-size:18px;flex-shrink:0;">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('info'))
        <div class="balert balert-info fu">
            <span class="material-symbols-outlined fil" style="font-size:18px;flex-shrink:0;">info</span>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    {{-- Filter Bar Departemen & Search --}}
    <div class="bcard fu1" style="padding:16px 20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;">
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <span class="material-symbols-outlined" style="color:var(--bp-dark);font-size:20px;">precision_manufacturing</span>
                <span style="font-size:13px;font-weight:700;color:var(--btxt);">Filter Departemen (Area):</span>

                <select wire:model.live="filterDepartemenId" class="binput" style="width:auto;padding:7px 14px;font-size:13px;font-weight:600;">
                    <option value="">Semua Departemen</option>
                    @foreach($departemens as $d)
                        <option value="{{ $d->id }}">{{ $d->nama_departemen }}</option>
                    @endforeach
                </select>
            </div>

            <div style="max-width:280px;flex:1;position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--btxt2);font-size:18px;">search</span>
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="Cari sub area atau nama PIC..."
                       class="binput" style="padding-left:40px;padding-top:7px;padding-bottom:7px;font-size:12.5px;" />
            </div>
        </div>
    </div>

    {{-- Data Table Sub Area & PICs (Vertical List) --}}
    <div class="bcard fu2" style="overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="btbl">
                <thead>
                    <tr>
                        <th style="width:80px;padding:16px 20px;text-align:left;">ID</th>
                        <th style="width:180px;padding:16px 20px;text-align:left;">Departemen (Area)</th>
                        <th style="width:240px;padding:16px 20px;text-align:left;">Nama Sub Area</th>
                        <th style="padding:16px 20px;text-align:left;">PIC Penanggung Jawab</th>
                        <th style="width:170px;padding:16px 20px;text-align:center;">Aksi Kelola PIC</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subAreas as $sa)
                    <tr style="border-bottom:1px solid var(--bbor);transition:background 0.15s;">
                        <td style="padding:16px 20px;vertical-align:middle;">
                            <span style="font-family:monospace;font-size:12px;font-weight:700;color:var(--bp);background:var(--bp-light);padding:4px 10px;border-radius:6px;display:inline-block;">#{{ $sa->id }}</span>
                        </td>
                        <td style="padding:16px 20px;vertical-align:middle;">
                            <span style="font-weight:700;font-size:13px;color:var(--btxt);">{{ $sa->departemen->nama_departemen ?? 'Umum' }}</span>
                        </td>
                        <td style="padding:16px 20px;vertical-align:middle;font-weight:600;font-size:13.5px;color:var(--btxt);">
                            {{ $sa->nama_sub_area }}
                            @if(strtolower(trim($sa->nama_sub_area)) === 'others')
                                <span style="font-size:10px;font-weight:700;background:#ffebee;color:#c62828;padding:2px 8px;border-radius:4px;margin-left:6px;">OTHERS</span>
                            @endif
                        </td>
                        <td style="padding:16px 20px;vertical-align:middle;">
                            <div style="display:flex;flex-direction:column;align-items:flex-start;gap:8px;">
                                @forelse($sa->pics as $pic)
                                    <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;background:#e3f2fd;border:1px solid #90caf9;color:#1565c0;padding:6px 12px;border-radius:8px;font-size:12.5px;font-weight:600;width:100%;max-width:360px;">
                                        <div style="display:flex;align-items:center;gap:8px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            <span class="material-symbols-outlined" style="font-size:16px;color:#1565c0;flex-shrink:0;">person</span>
                                            <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $pic->name }}</span>
                                            <span style="font-size:11px;opacity:0.8;font-family:monospace;flex-shrink:0;">({{ $pic->nik }})</span>
                                        </div>
                                        <button wire:click="removePic({{ $sa->id }}, {{ $pic->id }})"
                                                wire:confirm="Hapus {{ $pic->name }} dari PIC Sub Area {{ $sa->nama_sub_area }}?"
                                                title="Hapus PIC"
                                                style="border:none;background:none;color:#c62828;cursor:pointer;padding:2px 4px;display:flex;align-items:center;border-radius:4px;"
                                                onmouseover="this.style.background='#ffcdd2'" onmouseout="this.style.background='none'">
                                            <span class="material-symbols-outlined" style="font-size:16px;">close</span>
                                        </button>
                                    </div>
                                @empty
                                    <span style="font-size:12.5px;color:var(--btxt2);font-style:italic;">- Belum ada PIC -</span>
                                @endforelse
                            </div>
                        </td>
                        <td style="padding:16px 20px;vertical-align:middle;text-align:center;">
                            <button wire:click="openManagePics({{ $sa->id }})" class="bbtn bbtn-secondary bbtn-sm" style="padding:7px 14px!important;border-radius:8px;font-size:12.5px!important;font-weight:600;">
                                <span class="material-symbols-outlined" style="font-size:16px;">person_add</span>
                                + Kelola PIC
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:40px;color:var(--btxt2);">
                            <span class="material-symbols-outlined" style="font-size:40px;display:block;margin-bottom:10px;opacity:.3;">location_off</span>
                            Belum ada data Sub Area untuk filter ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($subAreas->hasPages())
        <div style="padding:16px 20px;border-top:1px solid var(--bbor);">
            {{ $subAreas->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>

    {{-- MODAL INTERAKTIF KELOLA PIC SUB AREA --}}
    @if($managingSubArea)
    <dialog open id="manage-pic-modal" style="position:fixed;inset:0;margin:auto;border-radius:16px;border:1px solid var(--bbor);background:var(--bcard);padding:24px;max-width:540px;width:92%;color:var(--btxt);outline:none;box-shadow:0 24px 60px rgba(0,0,0,.2);z-index:100;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--bbor);">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;border-radius:10px;background:#e3f2fd;display:flex;align-items:center;justify-content:center;">
                    <span class="material-symbols-outlined" style="color:#1565c0;font-size:20px;">person_add</span>
                </div>
                <div>
                    <h3 style="font-size:15px;font-weight:700;margin:0;color:var(--btxt);">Kelola PIC Sub Area</h3>
                    <p style="font-size:12px;color:var(--btxt2);margin:0;">{{ $managingSubArea->nama_sub_area }} (Dept: {{ $managingSubArea->departemen->nama_departemen ?? 'Umum' }})</p>
                </div>
            </div>
            <button wire:click="closeManagePics" style="border:none;background:none;color:var(--btxt2);cursor:pointer;">
                <span class="material-symbols-outlined" style="font-size:20px;">close</span>
            </button>
        </div>

        {{-- Autocomplete Live Search Input --}}
        <div style="position:relative;margin-bottom:20px;">
            <label class="blabel">Cari & Tambah PIC Baru</label>
            <div style="position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--btxt2);font-size:18px;">search</span>
                <input type="text" wire:model.live.debounce.200ms="picSearch"
                       placeholder="Ketik nama atau NIK karyawan..."
                       class="binput" style="padding-left:40px;" />
            </div>

            {{-- Recommendations Dropdown List --}}
            @if(count($picResults) > 0)
                <div style="position:absolute;top:100%;left:0;right:0;background:var(--bcard);border:1px solid var(--bbor);border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,0.15);margin-top:4px;max-height:220px;overflow-y:auto;z-index:110;">
                    @foreach($picResults as $user)
                        <div wire:click="addPic({{ $user['id'] }})"
                             style="padding:10px 14px;border-bottom:1px solid var(--bbor);cursor:pointer;display:flex;align-items:center;justify-content:space-between;transition:background 0.15s;"
                             onmouseover="this.style.background='var(--bp-light)'" onmouseout="this.style.background='none'">
                            <div>
                                <div style="font-weight:600;font-size:13px;color:var(--btxt);">{{ $user['name'] }}</div>
                                <div style="font-size:11px;color:var(--btxt2);">NIK: {{ $user['nik'] }}</div>
                            </div>
                            <span class="bbtn bbtn-primary bbtn-sm" style="padding:3px 8px!important;font-size:11px!important;">+ Pilih</span>
                        </div>
                    @endforeach
                </div>
            @elseif(strlen(trim($picSearch)) >= 1)
                <div style="position:absolute;top:100%;left:0;right:0;background:var(--bcard);border:1px solid var(--bbor);border-radius:10px;padding:12px;text-align:center;font-size:12px;color:var(--btxt2);box-shadow:0 8px 24px rgba(0,0,0,0.15);margin-top:4px;z-index:110;">
                    Tidak ditemukan karyawan dengan kata kunci "{{ $picSearch }}".
                </div>
            @endif
        </div>

        {{-- Lista PIC Terdaftar saat ini --}}
        <div>
            <div class="blabel" style="margin-bottom:8px;">Daftar PIC Terdaftar ({{ $managingSubArea->pics->count() }} Orang):</div>
            <div style="display:flex;flex-direction:column;gap:8px;max-height:200px;overflow-y:auto;">
                @forelse($managingSubArea->pics as $pic)
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 12px;background:var(--bsur);border:1px solid var(--bbor);border-radius:10px;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:28px;height:28px;border-radius:50%;background:var(--bp);color:#fff;font-weight:700;font-size:11px;display:flex;align-items:center;justify-content:center;">
                                {{ strtoupper(substr($pic->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:13px;color:var(--btxt);">{{ $pic->name }}</div>
                                <div style="font-size:11px;color:var(--btxt2);">NIK: {{ $pic->nik }}</div>
                            </div>
                        </div>
                        <button wire:click="removePic({{ $managingSubArea->id }}, {{ $pic->id }})"
                                class="bbtn bbtn-danger bbtn-sm" style="padding:4px 8px!important;">
                            <span class="material-symbols-outlined" style="font-size:14px;">delete</span>
                            Hapus
                        </button>
                    </div>
                @empty
                    <div style="text-align:center;padding:16px;color:var(--btxt2);font-size:12.5px;font-style:italic;">
                        Belum ada PIC yang ditunjuk untuk Sub Area ini. Cari dan pilih karyawan di atas untuk menambahkan.
                    </div>
                @endforelse
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;margin-top:20px;padding-top:12px;border-top:1px solid var(--bbor);">
            <button wire:click="closeManagePics" class="bbtn bbtn-primary">Selesai</button>
        </div>
    </dialog>
    <div style="position:fixed;inset:0;background:rgba(0,0,0,0.45);backdrop-filter:blur(3px);z-index:90;" wire:click="closeManagePics"></div>
    @endif
</div>
