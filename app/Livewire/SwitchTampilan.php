<?php

namespace App\Livewire;

use Livewire\Component;

class SwitchTampilan extends Component
{
    public $tab = 'pelapor'; // pelapor | pic

    public function mount()
    {
        $this->tab = 'pelapor';
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
    }

    public function render()
    {
        return view('livewire.switch-tampilan');
    }
}
