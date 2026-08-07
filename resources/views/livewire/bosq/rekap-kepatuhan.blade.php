<div style="display:flex;flex-direction:column;gap:24px;" class="fu">

    {{-- Header & Title --}}
    <div class="bph fu1">
        <div>
            <h2 class="bph-title">Rekap persentase target observasi per departemen</h2>
            <p class="bph-sub">Ringkasan persentase per departemen & detail target per anggota (Week 1 s/d Week 4)
            </p>
        </div>
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('bosq.qa.export.rekap.csv', ['date' => $bulan_tahun . '-01']) }}" class="bbtn"
                style="background:#10b981;color:#ffffff;border:none;border-radius:20px;padding:7px 16px;font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:6px;text-decoration:none;box-shadow:0 2px 6px rgba(16,185,129,0.25);">
                <span class="material-symbols-outlined" style="font-size:16px;color:#fff;">table_chart</span> Excel
            </a>
            <a href="{{ route('bosq.qa.export.rekap.pdf', ['date' => $bulan_tahun . '-01']) }}" target="_blank"
                class="bbtn"
                style="background:#d83b01;color:#ffffff;border:none;border-radius:20px;padding:7px 16px;font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:6px;text-decoration:none;box-shadow:0 2px 6px rgba(216,59,1,0.25);">
                <span class="material-symbols-outlined" style="font-size:16px;color:#fff;">picture_as_pdf</span> PDF
            </a>
        </div>
    </div>

    {{-- Kustomisasi Tanggal Per-Week Card --}}
    <div class="bcard fu1" style="padding:18px 20px;background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;">
        <div
            style="display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:14px;flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:8px;">
                <span class="material-symbols-outlined" style="color:var(--bp-dark);font-size:20px;">tune</span>
                <span style="font-size:14px;font-weight:700;color:var(--btxt);">Kustomisasi Periode & Rentang Tanggal
                    Week 1 - Week 4:</span>
            </div>

            <div style="display:flex;align-items:center;gap:10px;">
                <label style="font-size:13px;font-weight:600;color:var(--btxt2);">Pilih Bulan:</label>
                <input type="month" wire:model.live="bulan_tahun" class="binput"
                    style="width:auto;padding:6px 12px;font-weight:700;" />
                <button wire:click="generateDefaultWeeks" class="bbtn bbtn-secondary bbtn-sm"
                    title="Reset Rentang Tanggal Default">
                    <span class="material-symbols-outlined" style="font-size:16px;">refresh</span>
                    Reset Tanggal
                </button>
            </div>
        </div>

        {{-- Input Tanggal Per-Week --}}
        <div
            style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:12px;padding:12px;background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;">
            {{-- Week 1 --}}
            <div style="display:flex;flex-direction:column;gap:4px;">
                <span style="font-size:11.5px;font-weight:700;color:#1e293b;display:flex;align-items:center;gap:4px;">
                    <span class="material-symbols-outlined"
                        style="font-size:14px;color:var(--bp);">calendar_today</span>
                    WEEK 1 (Tgl: {{ $weeks['w1']['label'] }})
                </span>
                <div style="display:flex;align-items:center;gap:4px;">
                    <input type="date" wire:model.live="week1_start" class="binput"
                        style="padding:4px 8px;font-size:12px;" />
                    <span style="font-size:11px;color:#64748b;">s/d</span>
                    <input type="date" wire:model.live="week1_end" class="binput"
                        style="padding:4px 8px;font-size:12px;" />
                </div>
            </div>

            {{-- Week 2 --}}
            <div style="display:flex;flex-direction:column;gap:4px;">
                <span style="font-size:11.5px;font-weight:700;color:#1e293b;display:flex;align-items:center;gap:4px;">
                    <span class="material-symbols-outlined"
                        style="font-size:14px;color:var(--bp);">calendar_today</span>
                    WEEK 2 (Tgl: {{ $weeks['w2']['label'] }})
                </span>
                <div style="display:flex;align-items:center;gap:4px;">
                    <input type="date" wire:model.live="week2_start" class="binput"
                        style="padding:4px 8px;font-size:12px;" />
                    <span style="font-size:11px;color:#64748b;">s/d</span>
                    <input type="date" wire:model.live="week2_end" class="binput"
                        style="padding:4px 8px;font-size:12px;" />
                </div>
            </div>

            {{-- Week 3 --}}
            <div style="display:flex;flex-direction:column;gap:4px;">
                <span style="font-size:11.5px;font-weight:700;color:#1e293b;display:flex;align-items:center;gap:4px;">
                    <span class="material-symbols-outlined"
                        style="font-size:14px;color:var(--bp);">calendar_today</span>
                    WEEK 3 (Tgl: {{ $weeks['w3']['label'] }})
                </span>
                <div style="display:flex;align-items:center;gap:4px;">
                    <input type="date" wire:model.live="week3_start" class="binput"
                        style="padding:4px 8px;font-size:12px;" />
                    <span style="font-size:11px;color:#64748b;">s/d</span>
                    <input type="date" wire:model.live="week3_end" class="binput"
                        style="padding:4px 8px;font-size:12px;" />
                </div>
            </div>

            {{-- Week 4 --}}
            <div style="display:flex;flex-direction:column;gap:4px;">
                <span style="font-size:11.5px;font-weight:700;color:#1e293b;display:flex;align-items:center;gap:4px;">
                    <span class="material-symbols-outlined"
                        style="font-size:14px;color:var(--bp);">calendar_today</span>
                    WEEK 4 (Tgl: {{ $weeks['w4']['label'] }})
                </span>
                <div style="display:flex;align-items:center;gap:4px;">
                    <input type="date" wire:model.live="week4_start" class="binput"
                        style="padding:4px 8px;font-size:12px;" />
                    <span style="font-size:11px;color:#64748b;">s/d</span>
                    <input type="date" wire:model.live="week4_end" class="binput"
                        style="padding:4px 8px;font-size:12px;" />
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL 1: REKAP RINGKASAN PER DEPARTEMEN --}}
    <div class="bcard fu2"
        style="overflow:hidden;border:1px solid #cbd5e1;border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.06);">
        <div
            style="padding:14px 18px;background:#1e293b;color:#ffffff;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:8px;">
                <span class="material-symbols-outlined" style="color:#38bdf8;font-size:22px;">table_chart</span>
                <div>
                    <h3 style="font-size:15px;font-weight:800;margin:0;letter-spacing:0.5px;">REKAP KEPATUHAN PER
                        DEPARTEMEN</h3>
                    <p style="font-size:11.5px;color:#94a3b8;margin:0;">Ringkasan pencapaian persentase target observasi
                        per departemen</p>
                </div>
            </div>
            <span
                style="font-size:12px;font-weight:700;padding:4px 10px;background:#334155;border-radius:20px;color:#38bdf8;">
                Periode: {{ $monthName }}
            </span>
        </div>

        <div style="overflow-x:auto;">
            <table
                style="width:100%;border-collapse:collapse;font-family:'Inter',sans-serif;font-size:13px;border:1px solid #94a3b8;">
                <thead>
                    <tr
                        style="background:#0f172a;color:#ffffff;text-align:center;font-weight:800;font-size:12.5px;letter-spacing:0.8px;">
                        <th
                            style="padding:10px 16px;text-align:left;border:1px solid #334155;width:300px;background:#1e293b;">
                            {{ $monthShort }}
                        </th>
                        <th style="padding:10px;border:1px solid #334155;width:120px;">WEEK 1</th>
                        <th style="padding:10px;border:1px solid #334155;width:120px;">WEEK 2</th>
                        <th style="padding:10px;border:1px solid #334155;width:120px;">WEEK 3</th>
                        <th style="padding:10px;border:1px solid #334155;width:120px;">WEEK 4</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deptSummary as $ds)
                        <tr style="border-bottom:1px solid #cbd5e1;background:#ffffff;">
                            <td
                                style="padding:8px 16px;border:1px solid #cbd5e1;font-weight:800;color:#0f172a;letter-spacing:0.5px;">
                                {{ $ds['nama'] }}
                            </td>

                            {{-- Week 1 --}}
                            @php $p1 = $ds['scores']['w1']['persen']; @endphp
                            <td
                                style="padding:8px;text-align:center;border:1px solid #cbd5e1;font-weight:800;font-size:13px;
                                               {{ $p1 == 100 ? 'background:#334155;color:#ffffff;' : ($p1 >= 50 ? 'background:#ffffff;color:#0f172a;' : 'background:#0f172a;color:#ffffff;') }}">
                                {{ $p1 }}%
                            </td>

                            {{-- Week 2 --}}
                            @php $p2 = $ds['scores']['w2']['persen']; @endphp
                            <td
                                style="padding:8px;text-align:center;border:1px solid #cbd5e1;font-weight:800;font-size:13px;
                                               {{ $p2 == 100 ? 'background:#334155;color:#ffffff;' : ($p2 >= 50 ? 'background:#ffffff;color:#0f172a;' : 'background:#0f172a;color:#ffffff;') }}">
                                {{ $p2 }}%
                            </td>

                            {{-- Week 3 --}}
                            @php $p3 = $ds['scores']['w3']['persen']; @endphp
                            <td
                                style="padding:8px;text-align:center;border:1px solid #cbd5e1;font-weight:800;font-size:13px;
                                               {{ $p3 == 100 ? 'background:#334155;color:#ffffff;' : ($p3 >= 50 ? 'background:#ffffff;color:#0f172a;' : 'background:#0f172a;color:#ffffff;') }}">
                                {{ $p3 }}%
                            </td>

                            {{-- Week 4 --}}
                            @php $p4 = $ds['scores']['w4']['persen']; @endphp
                            <td
                                style="padding:8px;text-align:center;border:1px solid #cbd5e1;font-weight:800;font-size:13px;
                                               {{ $p4 == 100 ? 'background:#334155;color:#ffffff;' : ($p4 >= 50 ? 'background:#ffffff;color:#0f172a;' : 'background:#0f172a;color:#ffffff;') }}">
                                {{ $p4 }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- TABEL 2: DETAIL MATRIKS PENCAPAIAN BQA PER KARYAWAN --}}
    <div class="bcard fu2"
        style="overflow:hidden;border:1px solid #cbd5e1;border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.06);">
        <div
            style="padding:14px 18px;background:#0f172a;color:#ffffff;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:8px;">
                <span class="material-symbols-outlined" style="color:#38bdf8;font-size:22px;">grid_on</span>
                <div>
                    <h3 style="font-size:15px;font-weight:800;margin:0;letter-spacing:0.5px;">DETAIL PENCAPAIAN BQA PER
                        ANGGOTA DEPARTEMEN</h3>
                    <p style="font-size:11.5px;color:#94a3b8;margin:0;">Detail pencapaian individu anggota divisi
                        manajemen di tiap minggu</p>
                </div>
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table
                style="width:100%;border-collapse:collapse;font-family:'Inter',sans-serif;font-size:12.5px;border:1px solid #94a3b8;">
                <thead>
                    {{-- Header Baris 1: Judul Pencapaian & Nama Bulan --}}
                    <tr style="background:#0f172a;color:#ffffff;text-align:center;">
                        <th
                            style="padding:10px 16px;text-align:left;border:1px solid #334155;width:320px;font-weight:800;font-size:13px;letter-spacing:0.5px;">
                            PENCAPAIAN BQA
                            <div style="font-size:11px;font-weight:400;color:#94a3b8;margin-top:2px;">Sum of % in WEEK
                            </div>
                        </th>
                        <th colspan="4"
                            style="padding:10px;border:1px solid #334155;font-weight:800;font-size:14px;letter-spacing:1px;background:#1e293b;color:#38bdf8;">
                            {{ $monthName }}
                        </th>
                    </tr>

                    {{-- Header Baris 2: Row Labels & Rentang Tanggal Patokan --}}
                    <tr style="background:#334155;color:#f8fafc;text-align:center;font-size:12px;font-weight:700;">
                        <th style="padding:8px 16px;text-align:left;border:1px solid #475569;background:#1e293b;">
                            Row Labels
                        </th>
                        <th style="padding:8px;border:1px solid #475569;width:110px;">{{ $weeks['w1']['label'] }}</th>
                        <th style="padding:8px;border:1px solid #475569;width:110px;">{{ $weeks['w2']['label'] }}</th>
                        <th style="padding:8px;border:1px solid #475569;width:110px;">{{ $weeks['w3']['label'] }}</th>
                        <th style="padding:8px;border:1px solid #475569;width:110px;">{{ $weeks['w4']['label'] }}</th>
                    </tr>

                    {{-- Header Baris 3: Column Labels & Week 1-4 --}}
                    <tr
                        style="background:#475569;color:#ffffff;text-align:center;font-size:11.5px;font-weight:800;letter-spacing:0.5px;">
                        <th
                            style="padding:8px 16px;text-align:left;border:1px solid #64748b;background:#334155;color:#cbd5e1;">
                            Column Labels
                        </th>
                        <th style="padding:8px;border:1px solid #64748b;background:#334155;">WEEK 1</th>
                        <th style="padding:8px;border:1px solid #64748b;background:#334155;">WEEK 2</th>
                        <th style="padding:8px;border:1px solid #64748b;background:#334155;">WEEK 3</th>
                        <th style="padding:8px;border:1px solid #64748b;background:#334155;">WEEK 4</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($matrixData as $deptGroup)
                        {{-- Baris Header Departemen --}}
                        <tr style="background:#94a3b8;color:#0f172a;font-weight:800;font-size:12px;letter-spacing:0.8px;">
                            <td colspan="5"
                                style="padding:7px 16px;border:1px solid #64748b;background:#cbd5e1;color:#0f172a;text-transform:uppercase;">
                                {{ $deptGroup['nama_departemen'] }}
                            </td>
                        </tr>

                        {{-- Baris Anggota Karyawan --}}
                        @forelse($deptGroup['members'] as $m)
                            <tr style="border-bottom:1px solid #cbd5e1;background:#ffffff;">
                                <td style="padding:7px 16px;border:1px solid #cbd5e1;font-weight:600;color:#1e293b;">
                                    {{ $m['nama'] }}
                                </td>

                                {{-- Week 1 --}}
                                @php $w1 = $m['scores']['w1']; @endphp
                                <td
                                    style="padding:7px;text-align:center;border:1px solid #cbd5e1;font-weight:700;
                                                           {{ $w1['persen'] == 100 ? 'background:#dcfce7;color:#15803d;' : ($w1['persen'] >= 50 ? 'background:#fef08a;color:#854d0e;' : 'background:#f1f5f9;color:#64748b;') }}">
                                    {{ $w1['persen'] }}
                                </td>

                                {{-- Week 2 --}}
                                @php $w2 = $m['scores']['w2']; @endphp
                                <td
                                    style="padding:7px;text-align:center;border:1px solid #cbd5e1;font-weight:700;
                                                           {{ $w2['persen'] == 100 ? 'background:#dcfce7;color:#15803d;' : ($w2['persen'] >= 50 ? 'background:#fef08a;color:#854d0e;' : 'background:#f1f5f9;color:#64748b;') }}">
                                    {{ $w2['persen'] }}
                                </td>

                                {{-- Week 3 --}}
                                @php $w3 = $m['scores']['w3']; @endphp
                                <td
                                    style="padding:7px;text-align:center;border:1px solid #cbd5e1;font-weight:700;
                                                           {{ $w3['persen'] == 100 ? 'background:#dcfce7;color:#15803d;' : ($w3['persen'] >= 50 ? 'background:#fef08a;color:#854d0e;' : 'background:#f1f5f9;color:#64748b;') }}">
                                    {{ $w3['persen'] }}
                                </td>

                                {{-- Week 4 --}}
                                @php $w4 = $m['scores']['w4']; @endphp
                                <td
                                    style="padding:7px;text-align:center;border:1px solid #cbd5e1;font-weight:700;
                                                           {{ $w4['persen'] == 100 ? 'background:#dcfce7;color:#15803d;' : ($w4['persen'] >= 50 ? 'background:#fef08a;color:#854d0e;' : 'background:#f1f5f9;color:#64748b;') }}">
                                    {{ $w4['persen'] }}
                                </td>
                            </tr>
                        @empty
                            <tr style="background:#ffffff;">
                                <td colspan="5"
                                    style="padding:10px 16px;text-align:center;color:#94a3b8;font-style:italic;border:1px solid #cbd5e1;">
                                    Belum ada anggota terdaftar di departemen ini
                                </td>
                            </tr>
                        @endforelse
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Keterangan / Legenda Indikator Kepatuhan --}}
    <div class="bcard fu2"
        style="padding:16px 20px;background:#ffffff;border:1px solid #cbd5e1;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
        <div style="display:flex;align-items:center;gap:18px;flex-wrap:wrap;font-size:13px;font-weight:600;">
            <div style="display:flex;align-items:center;gap:8px;color:#1e293b;font-weight:700;">
                <span>Indikator Target:</span>
            </div>

            {{-- 100% Tercapai --}}
            <div
                style="display:flex;align-items:center;gap:8px;padding:6px 14px;border-radius:20px;background:#dcfce7;color:#15803d;border:1px solid #86efac;">
                <span class="material-symbols-outlined" style="font-size:16px;color:#16a34a;">check_circle</span>
                <span><strong>100%</strong> (Tercapai - Target 2 Laporan/Minggu Selesai)</span>
            </div>

            {{-- 50%-99% Sebagian --}}
            <div
                style="display:flex;align-items:center;gap:8px;padding:6px 14px;border-radius:20px;background:#fef08a;color:#854d0e;border:1px solid #fde047;">
                <span class="material-symbols-outlined" style="font-size:16px;color:#ca8a04;">warning</span>
                <span><strong>50% - 99%</strong> (Sebagian - Laporan Belum Lengkap)</span>
            </div>

            {{-- 0% Belum Mengirim --}}
            <div
                style="display:flex;align-items:center;gap:8px;padding:6px 14px;border-radius:20px;background:#f1f5f9;color:#64748b;border:1px solid #cbd5e1;">
                <span class="material-symbols-outlined" style="font-size:16px;color:#94a3b8;">cancel</span>
                <span><strong>0%</strong> (Belum Ada Laporan Terkirim)</span>
            </div>
        </div>
    </div>

</div>