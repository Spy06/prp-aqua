<?php

namespace App\Livewire;

use App\Models\Temuan;
use App\Models\TindakLanjut;
use Livewire\Component;
use App\Notifications\TemuanNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class VerifikasiQA extends Component
{
    public Temuan $temuan;
    public $catatan_qa = '';

    public function mount(Temuan $temuan)
    {
        $this->temuan = $temuan;
    }

    public function setujui()
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
                'acc_qa' => true,
                'tanggal_acc' => now(),
                'status' => 'closed_acc',
                'catatan_qa' => null, // clear previous if any
            ]);
        }

        $this->temuan->update([
            'status' => 'closed_acc'
        ]);

        // Kirim Notifikasi WA ke Pelapor dan PIC
        $pelaporUser = User::find($this->temuan->pelapor_id);
        $picUser = User::find($this->temuan->pic_id);

        $url = route('temuan.detail', $this->temuan->id);
        $messagePelapor = "Temuan #{$this->temuan->id} sudah disetujui QA dan resmi closed:\n" . $url;
        $messagePic = "Temuan #{$this->temuan->id} sudah disetujui QA dan resmi closed:\n" . $url;

        // Kirim Email Notifikasi SIVERA (Closed ACC)
        $emailService = app(\App\Services\EmailNotificationService::class);
        $emailService->sendSiveraNotification($this->temuan, 'closed');

        // Just to ensure UI updates
        $this->redirectRoute('temuan.detail', $this->temuan->id);
    }

    public function tolak()
    {
        if (auth()->user()->role !== 'qa') {
            abort(403);
        }

        $this->validate([
            'catatan_qa' => 'required|string',
        ]);

        if ($this->temuan->status !== 'closed_pending_qa') {
            return;
        }

        $tl = $this->temuan->tindakLanjut;
        
        if ($tl) {
            $tl->update([
                'acc_qa' => false,
                'tanggal_acc' => null,
                'status' => 'in_progress',
                'catatan_qa' => $this->catatan_qa,
            ]);
        }

        $this->temuan->update([
            'status' => 'in_progress'
        ]);

        // Send notif email to PIC only
        $picUser = User::find($this->temuan->pic_id);
        if ($picUser && $picUser->email) {
            app(\App\Services\EmailNotificationService::class)->sendSiveraNotification($this->temuan, 'tindaklanjut', $picUser->email);
        }

        $this->redirectRoute('temuan.detail', $this->temuan->id);
    }

    public function render()
    {
        return view('livewire.verifikasi-q-a');
    }
}
