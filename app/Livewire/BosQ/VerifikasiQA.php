<?php

namespace App\Livewire\BosQ;

use App\Jobs\SendWhatsApp;
use App\Models\BosqTemuan;
use App\Models\BosqTindakLanjut;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;

class VerifikasiQA extends Component
{
    public BosqTemuan $temuan;
    public string $catatan_qa = '';

    public function mount(BosqTemuan $temuan): void
    {
        $this->temuan = $temuan;
    }

    public function setujui(): void
    {
        if (auth()->user()->role !== 'qa') {
            abort(403);
        }

        if ($this->temuan->status !== 'closed_pending_qa') {
            return;
        }

        $tl = $this->temuan->tindakLanjut;
        if ($tl) {
            $tl->update([
                'acc_qa'      => true,
                'tanggal_acc' => now(),
                'status'      => 'closed_acc',
                'catatan_qa'  => null,
            ]);
        }

        $this->temuan->update(['status' => 'closed_acc']);

        // Kirim WA ke Pelapor + Auditee
        $link = route('bosq.temuan.detail', $this->temuan->id);
        $msg  = "*[BOS'Q] Observasi #{$this->temuan->id} — ACC (Closed)*\n\n"
              . "Tindak lanjut telah disetujui oleh QA dan observasi resmi selesai.\n"
              . "Lihat detail di:\n{$link}";

        $pelapor = User::find($this->temuan->pelapor_id);
        $auditee = User::find($this->temuan->auditee_id);

        if ($pelapor && $pelapor->no_whatsapp) {
            SendWhatsApp::dispatch($pelapor->no_whatsapp, $msg);
        }
        if ($auditee && $auditee->no_whatsapp && $auditee->id !== $pelapor?->id) {
            SendWhatsApp::dispatch($auditee->no_whatsapp, $msg);
        }

        $this->redirectRoute('bosq.temuan.detail', $this->temuan->id);
    }

    public function tolak(): void
    {
        if (auth()->user()->role !== 'qa') {
            abort(403);
        }

        $this->validate([
            'catatan_qa' => 'required|string',
        ], [
            'catatan_qa.required' => 'Catatan QA wajib diisi saat menolak tindak lanjut.',
        ]);

        if ($this->temuan->status !== 'closed_pending_qa') {
            return;
        }

        $tl = $this->temuan->tindakLanjut;
        if ($tl) {
            $tl->update([
                'acc_qa'      => false,
                'tanggal_acc' => null,
                'status'      => 'in_progress',
                'catatan_qa'  => $this->catatan_qa,
            ]);
        }

        $this->temuan->update(['status' => 'in_progress']);

        // Kirim WA ke Auditee saja
        $auditee = User::find($this->temuan->auditee_id);
        if ($auditee && $auditee->no_whatsapp) {
            $link    = route('bosq.temuan.detail', $this->temuan->id);
            $catRing = Str::limit($this->catatan_qa, 80);
            $msg     = "*[BOS'Q] Observasi #{$this->temuan->id} — Dikembalikan*\n\n"
                     . "Tindak lanjut Anda ditolak oleh QA.\n"
                     . "Catatan: {$catRing}\n\n"
                     . "Perbaiki dan kirim ulang di:\n{$link}";
            SendWhatsApp::dispatch($auditee->no_whatsapp, $msg);
        }

        $this->redirectRoute('bosq.temuan.detail', $this->temuan->id);
    }

    public function render()
    {
        return view('livewire.bosq.verifikasi-qa');
    }
}
