<?php

namespace App\Livewire\BosQ;

use App\Models\BosqTemuan;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class SwitchTampilan extends Component
{
    use WithPagination;

    public string $tab = 'pelapor'; // pelapor | auditee

    public function mount(): void
    {
        $this->tab = 'pelapor';
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }

    #[On('temuanAdded')]
    public function refreshOnAdd(): void
    {
        // Trigger re-render when temuan added
    }

    public function render()
    {
        $auditeeBadge = BosqTemuan::where('auditee_id', auth()->id())
            ->whereIn('status', ['open', 'in_progress'])
            ->count();

        return view('livewire.bosq.switch-tampilan', [
            'auditeeBadge' => $auditeeBadge,
        ]);
    }
}
