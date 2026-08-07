<div class="bcard" style="border-left:4px solid #f59e0b;margin-top:20px;">
    <div class="bcard-header">
        <div style="display:flex;align-items:center;gap:10px;">
            <div class="bcard-hicon" style="background:#fff8e1;">
                <span class="material-symbols-outlined fil" style="color:#e65100;font-size:20px;">shield_check</span>
            </div>
            <div>
                <div style="font-size:15px;font-weight:700;color:var(--btxt);">Verifikasi QA</div>
                <div style="font-size:12px;color:var(--btxt2);">Tinjau tindak lanjut PIC dan berikan keputusan</div>
            </div>
        </div>
    </div>

    <div class="bcard-body">
        <form wire:submit.prevent="tolak" style="display:flex;flex-direction:column;gap:16px;">
            <div>
                <label class="blabel" for="catatan_qa">
                    Catatan QA
                    <span style="font-size:10px;font-weight:400;color:var(--btxt2);text-transform:none;">(Wajib diisi jika menolak)</span>
                </label>
                <textarea wire:model="catatan_qa" id="catatan_qa" rows="3"
                    placeholder="Tulis alasan jika bukti kurang jelas atau tindakan tidak sesuai..."
                    class="binput" style="resize:vertical;"></textarea>
                @error('catatan_qa')
                    <span class="berr">{{ $message }}</span>
                @enderror
            </div>

            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <button type="button" wire:click="setujui" class="bbtn bbtn-success">
                    <span class="material-symbols-outlined fil" style="font-size:18px;">check_circle</span>
                    Setujui (Closed ACC)
                </button>
                <button type="submit" class="bbtn bbtn-danger">
                    <span class="material-symbols-outlined fil" style="font-size:18px;">cancel</span>
                    Tolak (Kembali ke In Progress)
                </button>
            </div>
        </form>
    </div>
</div>
