<?php

namespace App\Livewire\BosQ;

use App\Models\BosqTemuan;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\On;
use Livewire\Component;

class DetailTemuan extends Component
{
    use AuthorizesRequests;

    public BosqTemuan $temuan;

    public function mount(BosqTemuan $temuan): void
    {
        $this->authorize('view', $temuan);
        $this->temuan = $temuan;
    }

    #[On('tindakLanjutUpdated')]
    public function handleUpdated(): void
    {
        $this->temuan = $this->temuan->fresh([
            'departemen', 'line', 'subArea', 'elemenQfs', 'pelapor.karyawan.departemen', 'auditee.karyawan.departemen', 'tindakLanjut',
        ]);
    }

    public function ubahStatusClosed(): void
    {
        $user = auth()->user();

        // Hanya Pelapor (Observer) atau Auditee yang berhak mengubah status observasi ini menjadi Closed. QA bertindak murni sebagai pengawas (oversight).
        if ($user->id !== $this->temuan->pelapor_id && $user->id !== $this->temuan->auditee_id) {
            session()->flash('error', 'Hanya observer (pelapor) atau auditee yang berhak mengubah status observasi ini menjadi Closed. QA hanya bertindak sebagai pengawas.');
            return;
        }

        if (in_array($this->temuan->status, ['closed', 'closed_acc'])) {
            session()->flash('info', 'Observasi ini sudah berstatus Closed.');
            return;
        }

        $this->temuan->update([
            'status' => 'closed',
        ]);

        if ($this->temuan->tindakLanjut) {
            $this->temuan->tindakLanjut->update([
                'status'      => 'closed',
                'acc_qa'      => true,
                'tanggal_acc' => now(),
            ]);
        }

        $this->temuan = $this->temuan->fresh([
            'departemen', 'line', 'subArea', 'elemenQfs', 'pelapor.karyawan.departemen', 'auditee.karyawan.departemen', 'tindakLanjut',
        ]);

        session()->flash('success', 'Status observasi berhasil diubah menjadi CLOSED!');
    }

    public function render()
    {
        $this->temuan->load([
            'departemen', 'line', 'subArea', 'elemenQfs', 'pelapor.karyawan.departemen', 'auditee.karyawan.departemen', 'tindakLanjut',
        ]);

        $user   = auth()->user();
        $temuan = $this->temuan;

        $isAuditee = $user->id === $temuan->auditee_id;
        $isPelapor = $user->id === $temuan->pelapor_id;
        $isQa      = $user->role === 'qa';

        $showTindakLanjutForm = false;
        $showVerifikasiForm   = false;

        return view('livewire.bosq.detail-temuan', [
            'temuan'              => $temuan,
            'isAuditee'           => $isAuditee,
            'isPelapor'           => $isPelapor,
            'isQa'                => $isQa,
            'showTindakLanjutForm'=> $showTindakLanjutForm,
            'showVerifikasiForm'  => $showVerifikasiForm,
        ]);
    }
}
