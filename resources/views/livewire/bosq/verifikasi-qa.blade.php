<div class="bcard" style="border-left:4px solid #1565c0;margin-top:20px;">
    <div class="bcard-header">
        <div class="bcard-hicon" style="background:#e3f2fd;">
            <span class="material-symbols-outlined fil" style="color:#1565c0;font-size:20px;">gpp_good</span>
        </div>
        <div>
            <div style="font-size:15px;font-weight:700;color:var(--btxt);">Verifikasi Tim QA — BOS'Q</div>
            <div style="font-size:12px;color:var(--btxt2);">Tinjau laporan observasi ini dan selesaikan verifikasi</div>
        </div>
    </div>

    <div class="bcard-body">
        <form wire:submit.prevent="setujui" style="display:flex;flex-direction:column;gap:16px;">
            <div>
                <label class="blabel" for="catatan_qa">
                    Catatan Verifikasi QA
                    <span style="font-size:10px;font-weight:400;color:var(--btxt2);text-transform:none;">(Opsional)</span>
                </label>
                <textarea wire:model="catatan_qa" id="catatan_qa" rows="3"
                    placeholder="Tuliskan catatan verifikasi QA jika ada..."
                    class="binput" style="resize:vertical;"></textarea>
                @error('catatan_qa')
                    <span class="berr">{{ $message }}</span>
                @enderror
            </div>

            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <button type="submit" class="bbtn bbtn-success">
                    <span class="material-symbols-outlined fil" style="font-size:18px;">check_circle</span>
                    Setujui & Verifikasi (Closed)
                </button>
            </div>
        </form>
    </div>
</div>
