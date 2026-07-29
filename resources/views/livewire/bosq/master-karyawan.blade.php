<div style="display:flex;flex-direction:column;gap:24px;" class="fu">
    
    {{-- Header --}}
    <div class="bph fu1">
        <div>
            <h2 class="bph-title">Anggota Divisi Manajemen — BOS'Q</h2>
            <p class="bph-sub">Kelola penetapan status Anggota Divisi Manajemen per karyawan untuk penetapan target mingguan observasi</p>
        </div>
    </div>

    {{-- Alert Success --}}
    @if (session()->has('success'))
        <div class="balert balert-success fu">
            <span class="material-symbols-outlined" style="font-size:20px;">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Stat Cards Summary --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:16px;" class="fu1">
        <div class="bstat">
            <div class="bstat-icon" style="background:#e3f2fd;">
                <span class="material-symbols-outlined fil" style="color:#1565c0;font-size:22px;">badge</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#1565c0;">{{ $totalKaryawan }}</div>
                <div class="bstat-lbl">Total Karyawan</div>
            </div>
        </div>

        <div class="bstat">
            <div class="bstat-icon" style="background:#e8f5e9;">
                <span class="material-symbols-outlined fil" style="color:#2e7d32;font-size:22px;">verified_user</span>
            </div>
            <div>
                <div class="bstat-val" style="color:#2e7d32;">{{ $totalManajemen }}</div>
                <div class="bstat-lbl">Anggota Divisi Manajemen (Target 2/minggu)</div>
            </div>
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="bcard fu2">
        <div class="bcard-header" style="justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div class="bcard-hicon" style="background:var(--bp-light);">
                    <span class="material-symbols-outlined fil" style="color:var(--bp-dark);font-size:20px;">groups</span>
                </div>
                <div>
                    <h3 style="font-size:15px;font-weight:700;color:var(--btxt);margin:0;">Manajemen Penetapan Target Karyawan</h3>
                    <p style="font-size:12px;color:var(--btxt2);margin:0;">Aktifkan toggle untuk menetapkan karyawan sebagai anggota Divisi Manajemen</p>
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <select wire:model.live="filterDivisiManajemen" class="binput" style="width:auto;padding:6px 12px;font-size:12.5px;">
                    <option value="">Semua Anggota</option>
                    <option value="1">Divisi Manajemen Saja (Active)</option>
                    <option value="0">Bukan Divisi Manajemen</option>
                </select>

                <select wire:model.live="filterDepartemenId" class="binput" style="width:auto;padding:6px 12px;font-size:12.5px;">
                    <option value="">Semua Departemen</option>
                    @foreach($departemens as $d)
                        <option value="{{ $d->id }}">{{ $d->nama_departemen }}</option>
                    @endforeach
                </select>

                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama / NIK..." class="binput" style="width:180px;padding:6px 12px;font-size:12.5px;" />
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;text-align:left;">
                <thead>
                    <tr style="background:var(--bsur);border-bottom:1px solid var(--bbor);color:var(--btxt2);font-size:11px;text-transform:uppercase;letter-spacing:0.5px;">
                        <th style="padding:12px 16px;">NIK & Nama Karyawan</th>
                        <th style="padding:12px 16px;">Departemen</th>
                        <th style="padding:12px 16px;">Status Sistem</th>
                        <th style="padding:12px 16px;text-align:center;">Divisi Manajemen (Target 2/minggu)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawans as $k)
                        <tr style="border-bottom:1px solid var(--bbor);">
                            <td style="padding:12px 16px;">
                                <div style="font-weight:700;color:var(--btxt);">{{ $k->nama }}</div>
                                <div style="font-size:11.5px;color:var(--btxt2);">NIK: {{ $k->nik }}</div>
                            </td>
                            <td style="padding:12px 16px;font-weight:600;color:var(--btxt2);">
                                {{ $k->departemen->nama_departemen ?? '-' }}
                            </td>
                            <td style="padding:12px 16px;">
                                @if($k->status_aktif)
                                    <span style="font-size:11px;font-weight:700;background:#e8f5e9;color:#2e7d32;padding:3px 8px;border-radius:4px;border:1px solid #c8e6c9;">Aktif</span>
                                @else
                                    <span style="font-size:11px;font-weight:700;background:#ffebee;color:#c62828;padding:3px 8px;border-radius:4px;border:1px solid #ffcdd2;">Nonaktif</span>
                                @endif
                            </td>
                            <td style="padding:12px 16px;text-align:center;">
                                <button wire:click="toggleDivisiManajemen('{{ $k->nik }}')"
                                    class="bbtn bbtn-sm"
                                    style="{{ $k->is_anggota_divisi_manajemen ? 'background:#e8f5e9;color:#2e7d32;border:1.5px solid #a5d6a7;' : 'background:var(--bsur);color:var(--btxt2);border:1.5px solid var(--bbor);' }}">
                                    <span class="material-symbols-outlined" style="font-size:16px;">{{ $k->is_anggota_divisi_manajemen ? 'check_box' : 'check_box_outline_blank' }}</span>
                                    <span>{{ $k->is_anggota_divisi_manajemen ? 'Anggota Divisi Manajemen (Aktif)' : 'Bukan Anggota' }}</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding:32px;text-align:center;color:var(--btxt2);">
                                Belum ada data Karyawan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($karyawans->hasPages())
            <div style="padding:16px 20px;border-top:1px solid var(--bbor);">
                {{ $karyawans->links() }}
            </div>
        @endif
    </div>

</div>
