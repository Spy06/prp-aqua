<?php

namespace App\Livewire;

use App\Models\Temuan;
use Livewire\Component;

class SwitchTampilan extends Component
{
    public string $tab = 'pelapor'; // pelapor | pic

    public function mount(): void
    {
        $this->tab = 'pelapor';
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }

    public function render()
    {
        // Hitung badge PIC: temuan dengan status open atau in_progress yang perlu tindak lanjut
        $picBadge = Temuan::where('pic_id', auth()->id())
            ->whereIn('status', ['open', 'in_progress'])
            ->count();

        return view('livewire.switch-tampilan', [
            'picBadge' => $picBadge,
        ]);
    }
}
