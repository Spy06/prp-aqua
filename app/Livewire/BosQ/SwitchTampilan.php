<?php

namespace App\Livewire\BosQ;

use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class SwitchTampilan extends Component
{
    use WithPagination;

    #[On('temuanAdded')]
    public function refreshOnAdd(): void
    {
        // Trigger re-render when temuan added
    }

    public function render()
    {
        return view('livewire.bosq.switch-tampilan');
    }
}
