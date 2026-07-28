<div class="bcard fu" style="box-shadow: 0 4px 20px rgba(0,0,0,0.05); border-radius: 16px;">
    {{-- Card Header --}}
    <div class="bcard-header" style="justify-content:space-between; border-bottom: 1px solid var(--bbor); padding: 20px 24px;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div class="bcard-hicon" style="background:var(--bp-light); width: 44px; height: 44px; border-radius: 12px;">
                <span class="material-symbols-outlined fil" style="color:var(--bp-dark);font-size:22px;">edit_note</span>
            </div>
            <div>
                <div style="font-size:16px;font-weight:700;color:var(--btxt);">Form Observasi Baru</div>
                <div style="font-size:12px;color:var(--btxt2);">Isi semua field yang bertanda *</div>
            </div>
        </div>
    </div>

    {{-- Card Body --}}
    <div class="bcard-body" style="padding:24px;">

        @if(session()->has('success'))
            <div style="padding:14px 18px;background:#e8f5e9;border:1px solid #a5d6a7;border-radius:12px;color:#2e7d32;font-weight:600;font-size:13.5px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
                <span class="material-symbols-outlined fil" style="font-size:20px;flex-shrink:0;">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session()->has('error'))
            <div style="padding:14px 18px;background:#ffebee;border:1px solid #ef9a9a;border-radius:12px;color:#c62828;font-weight:600;font-size:13.5px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
                <span class="material-symbols-outlined fil" style="font-size:20px;flex-shrink:0;">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="submit" style="display:flex;flex-direction:column;gap:24px;">

            <div class="form-grid-2col">

                {{-- ── KOLOM KIRI: Lokasi & Deskripsi Observasi ── --}}
                <div style="display:flex;flex-direction:column;gap:18px;">

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

                    {{-- Temuan BQA (Dipindahkan ke bawah Elemen QFS) --}}
                    <div>
                        <label class="blabel" for="temuan_bqa">Temuan Behavior Quality Audit <span style="color:var(--error);">*</span></label>
                        <textarea wire:model="temuan_bqa" id="temuan_bqa"
                            placeholder="Jelaskan perilaku yang diobservasi secara rinci..."
                            rows="4" class="binput" style="resize:vertical;"></textarea>
                        @error('temuan_bqa') <span class="berr">{{ $message }}</span> @enderror
                    </div>

                </div>

                {{-- ── KOLOM KANAN: Klasifikasi & Auditee ── --}}
                <div x-data="{
                    tingkatResiko: $wire.entangle('tingkat_resiko'),
                    dampakTemuan: $wire.entangle('dampak_temuan')
                }" style="display:flex;flex-direction:column;gap:20px;">

                    {{-- CSS Khusus Card Selector --}}
                    <style>
                        .rcard {
                            display: flex;
                            align-items: center;
                            gap: 12px;
                            padding: 12px 16px;
                            border-radius: 12px;
                            border: 1.5px solid var(--bbor, #e4e4e7);
                            background: var(--bcard, #ffffff);
                            cursor: pointer;
                            user-select: none;
                            transition: all 0.15s ease-in-out;
                        }
                        .rcard:hover {
                            border-color: #a1a1aa;
                            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
                        }
                        .rcard-icon {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            width: 36px;
                            height: 36px;
                            border-radius: 10px;
                            background: var(--bsur, #f4f4f5);
                            color: var(--btxt2, #71717a);
                            transition: all 0.15s ease;
                        }
                        .rcard-title {
                            font-size: 13.5px;
                            font-weight: 700;
                            color: var(--btxt, #18181b);
                            line-height: 1.2;
                        }
                        .rcard-sub {
                            font-size: 11px;
                            color: var(--btxt2, #71717a);
                            margin-top: 2px;
                        }
                        .rcard-radio {
                            color: #d4d4d8;
                            display: flex;
                            align-items: center;
                            transition: color 0.15s ease;
                        }

                        /* ACTIVE STATES */
                        .rcard-fsr.active-fsr { border-color: #ef4444 !important; background: #fef2f2 !important; }
                        .rcard-fsr.active-fsr .rcard-icon { background: #fee2e2; color: #dc2626; }
                        .rcard-fsr.active-fsr .rcard-title { color: #991b1b; }
                        .rcard-fsr.active-fsr .rcard-radio { color: #dc2626; }

                        .rcard-mqr.active-mqr { border-color: #f97316 !important; background: #fff7ed !important; }
                        .rcard-mqr.active-mqr .rcard-icon { background: #ffedd5; color: #ea580c; }
                        .rcard-mqr.active-mqr .rcard-title { color: #9a3412; }
                        .rcard-mqr.active-mqr .rcard-radio { color: #ea580c; }

                        .rcard-min.active-min { border-color: #10b981 !important; background: #ecfdf5 !important; }
                        .rcard-min.active-min .rcard-icon { background: #d1fae5; color: #059669; }
                        .rcard-min.active-min .rcard-title { color: #065f46; }
                        .rcard-min.active-min .rcard-radio { color: #059669; }

                        .rcard-neg.active-neg { border-color: #ef4444 !important; background: #fef2f2 !important; }
                        .rcard-neg.active-neg .rcard-icon { background: #fee2e2; color: #dc2626; }
                        .rcard-neg.active-neg .rcard-title { color: #991b1b; }
                        .rcard-neg.active-neg .rcard-radio { color: #dc2626; }

                        .rcard-pos.active-pos { border-color: #3b82f6 !important; background: #eff6ff !important; }
                        .rcard-pos.active-pos .rcard-icon { background: #dbeafe; color: #2563eb; }
                        .rcard-pos.active-pos .rcard-title { color: #1e40af; }
                        .rcard-pos.active-pos .rcard-radio { color: #2563eb; }

                        @media (max-width: 640px) {
                            .rcard { padding: 10px 12px !important; gap: 8px !important; }
                            .rcard-icon { width: 32px !important; height: 32px !important; flex-shrink: 0 !important; }
                            .rcard-title { font-size: 13px !important; }
                            .rcard-sub { font-size: 10.5px !important; }
                            .dampak-grid { grid-template-columns: 1fr !important; gap: 8px !important; }
                            .form-footer-actions { flex-direction: column-reverse !important; width: 100% !important; }
                            .form-footer-actions button { width: 100% !important; justify-content: center !important; }
                        }
                    </style>

                    {{-- Tingkat Risiko --}}
                    <div>
                        <label class="blabel" style="margin-bottom:8px;display:block;">Tingkat Risiko <span style="color:var(--error);">*</span></label>
                        <div style="display:flex;flex-direction:column;gap:8px;">

                            {{-- Food Safety Risk --}}
                            <div @click="tingkatResiko = 'food_safety_risk'"
                                :class="tingkatResiko === 'food_safety_risk' ? 'active-fsr' : ''"
                                class="rcard rcard-fsr">
                                <div class="rcard-icon">
                                    <span class="material-symbols-outlined" style="font-size:20px;">warning</span>
                                </div>
                                <div style="flex:1;">
                                    <div class="rcard-title">Food Safety Risk</div>
                                    <div class="rcard-sub">Risiko tinggi keamanan pangan</div>
                                </div>
                                <div class="rcard-radio">
                                    <span class="material-symbols-outlined" style="font-size:20px;" x-text="tingkatResiko === 'food_safety_risk' ? 'check_circle' : 'radio_button_unchecked'"></span>
                                </div>
                            </div>

                            {{-- Major Quality Risk --}}
                            <div @click="tingkatResiko = 'major_quality_risk'"
                                :class="tingkatResiko === 'major_quality_risk' ? 'active-mqr' : ''"
                                class="rcard rcard-mqr">
                                <div class="rcard-icon">
                                    <span class="material-symbols-outlined" style="font-size:20px;">report_problem</span>
                                </div>
                                <div style="flex:1;">
                                    <div class="rcard-title">Major Quality Risk</div>
                                    <div class="rcard-sub">Risiko mutu produk signifikan</div>
                                </div>
                                <div class="rcard-radio">
                                    <span class="material-symbols-outlined" style="font-size:20px;" x-text="tingkatResiko === 'major_quality_risk' ? 'check_circle' : 'radio_button_unchecked'"></span>
                                </div>
                            </div>

                            {{-- Minor Quality Risk --}}
                            <div @click="tingkatResiko = 'minor_quality_risk'"
                                :class="tingkatResiko === 'minor_quality_risk' ? 'active-min' : ''"
                                class="rcard rcard-min">
                                <div class="rcard-icon">
                                    <span class="material-symbols-outlined" style="font-size:20px;">info</span>
                                </div>
                                <div style="flex:1;">
                                    <div class="rcard-title">Minor Quality Risk</div>
                                    <div class="rcard-sub">Ketidaksesuaian mutu kecil</div>
                                </div>
                                <div class="rcard-radio">
                                    <span class="material-symbols-outlined" style="font-size:20px;" x-text="tingkatResiko === 'minor_quality_risk' ? 'check_circle' : 'radio_button_unchecked'"></span>
                                </div>
                            </div>

                        </div>
                        @error('tingkat_resiko') <span class="berr">{{ $message }}</span> @enderror
                    </div>

                    {{-- Dampak Temuan --}}
                    <div>
                        <label class="blabel" style="margin-bottom:8px;display:block;">Dampak Observasi <span style="color:var(--error);">*</span></label>
                        <div class="dampak-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">

                            {{-- Negatif --}}
                            <div @click="dampakTemuan = 'negatif'"
                                :class="dampakTemuan === 'negatif' ? 'active-neg' : ''"
                                class="rcard rcard-neg">
                                <div class="rcard-icon">
                                    <span class="material-symbols-outlined" style="font-size:20px;">error</span>
                                </div>
                                <div style="flex:1;">
                                    <div class="rcard-title">Negatif</div>
                                    <div class="rcard-sub">Butuh Perbaikan</div>
                                </div>
                                <div class="rcard-radio">
                                    <span class="material-symbols-outlined" style="font-size:20px;" x-text="dampakTemuan === 'negatif' ? 'check_circle' : 'radio_button_unchecked'"></span>
                                </div>
                            </div>

                            {{-- Positif --}}
                            <div @click="dampakTemuan = 'positif'"
                                :class="dampakTemuan === 'positif' ? 'active-pos' : ''"
                                class="rcard rcard-pos">
                                <div class="rcard-icon">
                                    <span class="material-symbols-outlined" style="font-size:20px;">thumb_up</span>
                                </div>
                                <div style="flex:1;">
                                    <div class="rcard-title">Positif</div>
                                    <div class="rcard-sub">Perilaku Baik</div>
                                </div>
                                <div class="rcard-radio">
                                    <span class="material-symbols-outlined" style="font-size:20px;" x-text="dampakTemuan === 'positif' ? 'check_circle' : 'radio_button_unchecked'"></span>
                                </div>
                            </div>

                        </div>
                        @error('dampak_temuan') <span class="berr">{{ $message }}</span> @enderror
                    </div>

                    {{-- Action & Due Date (Jika Negatif) --}}
                    <div x-show="dampakTemuan === 'negatif'" x-collapse x-cloak style="display:flex;flex-direction:column;gap:14px;">
                        <div>
                            <label class="blabel" for="action_negatif" style="margin-bottom:6px;display:block;">Action (Jika Negatif) <span style="color:var(--error);">*</span></label>
                            <textarea wire:model="action_negatif" id="action_negatif" rows="3" class="binput" placeholder="Tuliskan tindakan / action perbaikan..."></textarea>
                            @error('action_negatif') <span class="berr">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="blabel" for="due_date_act" style="margin-bottom:6px;display:block;">Due Date Action <span style="color:var(--error);">*</span></label>
                            <input type="date" wire:model="due_date_action" id="due_date_act" class="binput">
                            @error('due_date_action') <span class="berr">{{ $message }}</span> @enderror
                        </div>
                    </div>

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
                                <input wire:model.live.debounce.150ms="auditeeSearch" id="auditee"
                                    placeholder="Cari nama atau NIK auditee..."
                                    type="text" class="binput" style="padding-left:38px;" />
                            </div>

                            @if(count($auditeeResults) > 0)
                                <div style="position:absolute;top:100%;left:0;right:0;margin-top:4px;z-index:30;background:var(--bcard);border:1px solid var(--bbor);border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,0.12);overflow:hidden;max-height:220px;overflow-y:auto;">
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
                            @elseif(strlen(trim($auditeeSearch)) >= 1)
                                <div style="position:absolute;top:100%;left:0;right:0;margin-top:4px;z-index:30;background:var(--bcard);border:1px solid var(--bbor);border-radius:10px;padding:14px;text-align:center;font-size:13px;color:var(--btxt2);box-shadow:0 8px 24px rgba(0,0,0,0.12);">
                                    Tidak ada auditee yang ditemukan dengan NIK / nama tersebut.
                                </div>
                            @endif
                        @endif
                        @error('auditee_id') <span class="berr">{{ $message }}</span> @enderror
                    </div>

                </div>
            </div>

            {{-- Form Footer Action Buttons --}}
            <div class="form-footer-actions" style="display:flex;justify-content:flex-end;gap:12px;padding-top:16px;border-top:1px solid var(--bbor);">
                <button type="button" @click="showForm = false" class="bbtn bbtn-secondary">
                    <span class="material-symbols-outlined" style="font-size:18px;">close</span>
                    Batal
                </button>
                <button type="submit" class="bbtn bbtn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="submit" class="material-symbols-outlined" style="font-size:18px;">send</span>
                    <span wire:loading wire:target="submit" class="material-symbols-outlined" style="font-size:18px;animation:spin 1s linear infinite;">sync</span>
                    Kirim Observasi BOS'Q
                </button>
            </div>
        </form>
    </div>
</div>
