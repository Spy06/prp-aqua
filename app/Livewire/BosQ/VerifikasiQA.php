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

        if ($this->temuan->status !== 'open') {
            return;
        }

        $tl = $this->temuan->tindakLanjut;
        if (!$tl) {
            $tl = BosqTindakLanjut::create([
                'bosq_temuan_id' => $this->temuan->id,
                'action'         => 'Verifikasi langsung oleh QA',
                'status'         => 'closed',
                'acc_qa'         => true,
                'tanggal_acc'    => now(),
                'catatan_qa'     => $this->catatan_qa ?: null,
            ]);
        } else {
            $tl->update([
                'acc_qa'      => true,
                'tanggal_acc' => now(),
                'status'      => 'closed',
                'catatan_qa'  => $this->catatan_qa ?: null,
            ]);
        }

        $this->temuan->update(['status' => 'closed']);

        // Kirim Email Notifikasi BOS'Q (Closed ACC)
        $emailService = app(\App\Services\EmailNotificationService::class);
        $emailService->sendBosqNotification($this->temuan, 'closed');

        session()->flash('success', 'Observasi BOS\'Q berhasil diverifikasi dan diselesaikan (Closed).');
        $this->redirectRoute('bosq.temuan.detail', $this->temuan->id);
    }

    public function render()
    {
        return view('livewire.bosq.verifikasi-qa');
    }
}
