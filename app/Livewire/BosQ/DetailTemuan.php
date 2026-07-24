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
            'departemen', 'line', 'subArea', 'elemenQfs', 'pelapor', 'auditee', 'tindakLanjut',
        ]);
    }

    public function render()
    {
        $this->temuan->load([
            'departemen', 'line', 'subArea', 'elemenQfs', 'pelapor', 'auditee', 'tindakLanjut',
        ]);

        $user   = auth()->user();
        $temuan = $this->temuan;

        $isAuditee = $user->id === $temuan->auditee_id;
        $isPelapor = $user->id === $temuan->pelapor_id;
        $isQa      = $user->role === 'qa';

        // Auditee bisa mengisi tindak lanjut selama status belum closed_acc (dan bukan QA)
        $showTindakLanjutForm = $isAuditee
            && !$isQa
            && $temuan->dampak_temuan === 'negatif'
            && !in_array($temuan->status, ['closed_acc']);

        // QA bisa verifikasi hanya saat closed_pending_qa
        $showVerifikasiForm = $isQa && $temuan->status === 'closed_pending_qa';

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
