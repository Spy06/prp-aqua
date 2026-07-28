<div>
    {{-- Flash Messages --}}
    @if(session()->has('success'))
        <div class="balert balert-success fu" style="margin-bottom:20px;">
            <span class="material-symbols-outlined fil" style="font-size:20px;flex-shrink:0;">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session()->has('error'))
        <div class="balert balert-error fu" style="margin-bottom:20px;">
            <span class="material-symbols-outlined fil" style="font-size:20px;flex-shrink:0;">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="bph fu">
        <div>
            <h2 class="bph-title">Catat Observasi BOS'Q</h2>
            <p class="bph-sub">Rekam perilaku keamanan pangan yang Anda amati.</p>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bcard fu1">
        <div class="bcard-header">
            <div class="bcard-hicon" style="background:#e3f2fd;">
                <span class="material-symbols-outlined fil" style="color:#1565c0;font-size:20px;">edit_note</span>
            </div>
            <div>
                <div style="font-size:14px;font-weight:700;color:var(--btxt);">Form Observasi Baru</div>
                <div style="font-size:12px;color:var(--btxt2);">Isi semua field yang bertanda *</div>
            </div>
        </div>

        <div class="bcard-body">
            <form wire:submit="submit">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

                    {{-- ── KOLOM KIRI ── --}}
                    <div style="display:flex;flex-direction:column;gap:16px;">

                        {{-- Tanggal Temuan --}}
                        <div>
                            <label class="blabel" for="tgl">Tanggal Observasi <span style="color:var(--error);">*</span></label>
                            <input type="date" wire:model="tanggal_temuan" id="tgl" class="binput">
                            @error('tanggal_temuan') <span class="berr">{{ $message }}</span> @enderror
                        </div>

                        {{-- Departemen --}}
                        <div>
                            <label class="blabel" for="dept">Departemen <span style="color:var(--error);">*</span></label>
                            <select wire:model.live="departemen_id" id="dept" class="binput">
                                <option value="">Pilih Departemen</option>
                                @foreach($departemens as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                                @endforeach
                            </select>
                            @error('departemen_id') <span class="berr">{{ $message }}</span> @enderror
                        </div>

                        {{-- Sub Area --}}
                        <div>
                            <label class="blabel" for="subarea">Sub Area <span style="color:var(--error);">*</span></label>
                            <select wire:model.live="sub_area_id" id="subarea" class="binput" {{ empty($departemen_id) ? 'disabled' : '' }}>
                                <option value="">Pilih Sub Area</option>
                                @foreach($subAreas as $sa)
                                    <option value="{{ $sa->id }}">{{ $sa->nama_sub_area }}</option>
                                @endforeach
                            </select>
                            @error('sub_area_id') <span class="berr">{{ $message }}</span> @enderror
                        </div>

                        {{-- Detail Sub Area (Hanya jika memilih 'Others') --}}
                        @if($this->isSubAreaOthers)
                        <div>
                            <label class="blabel" for="detail_sa">Detail Sub Area <span style="color:var(--error);">*</span></label>
                            <input type="text" wire:model="detail_sub_area" id="detail_sa" class="binput" placeholder="Tuliskan nama detail area spesifik...">
                            @error('detail_sub_area') <span class="berr">{{ $message }}</span> @enderror
                        </div>
                        @endif

                        {{-- Elemen QFS --}}
                        <div>
                            <label class="blabel" for="elemen">Elemen QFS <span style="color:var(--error);">*</span></label>
                            <select wire:model="elemen_qfs_id" id="elemen" class="binput">
                                <option value="">Pilih Elemen QFS</option>
                                @foreach($elemenList as $el)
                                    <option value="{{ $el->id }}">{{ $el->nama_elemen }}</option>
                                @endforeach
                            </select>
                            @error('elemen_qfs_id') <span class="berr">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    {{-- ── KOLOM KANAN ── --}}
                    <div style="display:flex;flex-direction:column;gap:16px;">

                        {{-- Temuan BQA --}}
                        <div>
                            <label class="blabel" for="temuan_bqa">Temuan Behavior Quality Audit <span style="color:var(--error);">*</span></label>
                            <textarea wire:model="temuan_bqa" id="temuan_bqa"
                                placeholder="Jelaskan perilaku yang diobservasi secara rinci..."
                                rows="5" class="binput" style="resize:vertical;"></textarea>
                            @error('temuan_bqa') <span class="berr">{{ $message }}</span> @enderror
                        </div>

                        {{-- Tingkat Risiko --}}
                        <div>
                            <label class="blabel">Tingkat Risiko <span style="color:var(--error);">*</span></label>
                            <div style="display:flex;flex-direction:column;gap:8px;margin-top:4px;">
                                @foreach([
                                    'food_safety_risk'   => ['Food Safety Risk',   '#c62828', '#ffebee'],
                                    'major_quality_risk' => ['Major Quality Risk', '#e65100', '#fff3e0'],
                                    'minor_quality_risk' => ['Minor Quality Risk', '#1565c0', '#e3f2fd'],
                                ] as $val => [$label, $clr, $bg])
                                    <label style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;border:1.5px solid {{ $tingkat_resiko === $val ? $clr : 'var(--bbor)' }};background:{{ $tingkat_resiko === $val ? $bg : 'transparent' }};cursor:pointer;transition:all .2s;">
                                        <input type="radio" wire:model.live="tingkat_resiko" value="{{ $val }}" style="accent-color:{{ $clr }};">
                                        <span style="font-size:13.5px;font-weight:600;color:{{ $tingkat_resiko === $val ? $clr : 'var(--btxt)' }};">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('tingkat_resiko') <span class="berr">{{ $message }}</span> @enderror
                        </div>

                        {{-- Dampak Temuan --}}
                        <div>
                            <label class="blabel">Dampak Observasi <span style="color:var(--error);">*</span></label>
                            <div style="display:flex;gap:10px;margin-top:4px;">
                                @foreach(['negatif' => ['Negatif (Butuh Tindak Lanjut)', '#c62828', '#ffebee'], 'positif' => ['Positif (Perilaku Baik)', '#2e7d32', '#e8f5e9']] as $val => [$label, $clr, $bg])
                                    <label style="flex:1;display:flex;align-items:center;gap:8px;padding:10px 14px;border-radius:10px;border:1.5px solid {{ $dampak_temuan === $val ? $clr : 'var(--bbor)' }};background:{{ $dampak_temuan === $val ? $bg : 'transparent' }};cursor:pointer;transition:all .2s;">
                                        <input type="radio" wire:model.live="dampak_temuan" value="{{ $val }}" style="accent-color:{{ $clr }};">
                                        <span style="font-size:13px;font-weight:600;color:{{ $dampak_temuan === $val ? $clr : 'var(--btxt)' }};">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('dampak_temuan') <span class="berr">{{ $message }}</span> @enderror
                        </div>

                        {{-- Action (Jika Negatif) --}}
                        @if($dampak_temuan === 'negatif')
                        <div>
                            <label class="blabel" for="action_negatif">Action (Jika Negatif) <span style="color:var(--error);">*</span></label>
                            <textarea wire:model="action_negatif" id="action_negatif" rows="3" class="binput" placeholder="Tuliskan tindakan / action perbaikan..."></textarea>
                            @error('action_negatif') <span class="berr">{{ $message }}</span> @enderror
                        </div>
                        @endif

                        {{-- Auditee Search --}}
                        <div style="position:relative;">
                            <label class="blabel" for="auditee">Auditee (yang diobservasi) <span style="color:var(--error);">*</span></label>

                            @if($auditee_id)
                                <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px;border:1.5px solid #a5d6a7;border-radius:10px;background:#e8f5e9;">
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <span class="material-symbols-outlined fil" style="color:#2e7d32;font-size:20px;">check_circle</span>
                                        <span style="font-size:13.5px;font-weight:600;color:#2e7d32;">{{ $auditeeSearch }}</span>
                                    </div>
                                    <button type="button" wire:click="clearAuditee" style="font-size:12.5px;color:var(--error);background:none;border:none;cursor:pointer;font-weight:600;font-family:inherit;">
                                        Ganti
                                    </button>
                                </div>
                            @else
                                <div style="position:relative;">
                                    <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:18px;color:var(--btxt2);">search</span>
                                    <input wire:model.live.debounce.300ms="auditeeSearch" id="auditee"
                                        placeholder="Cari nama atau NIK auditee..."
                                        type="text" class="binput" style="padding-left:38px;" />
                                </div>

                                @if(count($auditeeResults) > 0)
                                    <div style="position:absolute;top:100%;left:0;right:0;margin-top:4px;z-index:20;background:var(--bcard);border:1px solid var(--bbor);border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,0.1);overflow:hidden;max-height:200px;overflow-y:auto;">
                                        @foreach($auditeeResults as $res)
                                            <button type="button"
                                                wire:click="selectAuditee({{ $res['id'] }}, '{{ addslashes($res['name'] ?? $res['nik']) }}')"
                                                style="width:100%;text-align:left;padding:10px 14px;background:none;border:none;border-bottom:1px solid var(--bbor);cursor:pointer;font-family:inherit;transition:background .15s;"
                                                onmouseover="this.style.background='var(--bp-light)'" onmouseout="this.style.background='none'">
                                                <div style="font-size:13.5px;font-weight:600;color:var(--btxt);">{{ $res['name'] ?? 'User' }}</div>
                                                <div style="font-size:12px;color:var(--btxt2);margin-top:2px;">NIK: {{ $res['nik'] }}</div>
                                            </button>
                                        @endforeach
                                    </div>
                                @elseif(strlen($auditeeSearch) >= 2)
                                    <div style="position:absolute;top:100%;left:0;right:0;margin-top:4px;z-index:20;background:var(--bcard);border:1px solid var(--bbor);border-radius:10px;padding:14px;text-align:center;font-size:13px;color:var(--btxt2);">
                                        Tidak ada karyawan yang ditemukan.
                                    </div>
                                @endif
                            @endif
                            @error('auditee_id') <span class="berr">{{ $message }}</span> @enderror
                        </div>

                    </div>
                </div>

                {{-- Form Actions --}}
                <div style="display:flex;flex-wrap:wrap;justify-content:flex-end;gap:10px;padding-top:20px;margin-top:20px;border-top:1px solid var(--bbor);">
                    <a href="{{ route('bosq.beranda') }}" wire:navigate class="bbtn bbtn-secondary">
                        <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                        Batal
                    </a>
                    <button type="submit" wire:loading.attr="disabled" class="bbtn bbtn-primary">
                        <span wire:loading.remove wire:target="submit" class="material-symbols-outlined" style="font-size:18px;">send</span>
                        <span wire:loading wire:target="submit" class="material-symbols-outlined" style="font-size:18px;animation:spin 1s linear infinite;">sync</span>
                        <span wire:loading.remove wire:target="submit">Simpan Observasi</span>
                        <span wire:loading wire:target="submit">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        @media (max-width: 640px) {
            form > div[style*="grid-template-columns"] { grid-template-columns: 1fr !important; }
        }
    </style>
</div>
