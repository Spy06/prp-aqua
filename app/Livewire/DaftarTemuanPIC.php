<?php

namespace App\Livewire;

use App\Models\Temuan;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Carbon;
use Livewire\WithPagination;

class DaftarTemuanPIC extends Component
{
    use WithPagination;

    /**
     * Refresh ketika status tindak lanjut diperbarui oleh TindakLanjutPIC.
     */
    #[On('tindakLanjutUpdated')]
    public function refresh(): void
    {
        // re-render otomatis dipicu oleh event listener
    }

    public function render()
    {
        $today = Carbon::today();
        $picId = auth()->id();

        // Hitung badge & metrics (di database level)
        $badgeCount = Temuan::where('pic_id', $picId)->whereIn('status', ['open', 'in_progress'])->count();
        $metrics = [
            'open' => Temuan::where('pic_id', $picId)->where('status', 'open')->count(),
            'in_progress' => Temuan::where('pic_id', $picId)->where('status', 'in_progress')->count(),
            'pending_qa' => Temuan::where('pic_id', $picId)->where('status', 'closed_pending_qa')->count(),
            'closed' => Temuan::where('pic_id', $picId)->where('status', 'closed_acc')->count(),
        ];
        $totalAktif = Temuan::where('pic_id', $picId)->whereNotIn('status', ['closed_acc'])->count();

        $todayDate = Carbon::today()->toDateString();
        $threeDaysFromNow = Carbon::today()->addDays(3)->toDateString();

        // Ambil semua temuan di mana user login adalah PIC dengan Pagination + DB Sorting
        $temuans = Temuan::with(['departemen', 'pelapor', 'klausul', 'tindakLanjut'])
            ->leftJoin('tindak_lanjut', 'temuan.id', '=', 'tindak_lanjut.temuan_id')
            ->select('temuan.*') // hindari conflict id table
            ->where('temuan.pic_id', $picId)
            ->whereNotIn('temuan.status', ['closed_acc'])
            ->orderByRaw("
                CASE 
                    WHEN temuan.status = 'closed_pending_qa' THEN 3
                    WHEN tindak_lanjut.due_date IS NULL THEN 2
                    WHEN tindak_lanjut.due_date < '{$todayDate}' THEN 0
                    WHEN tindak_lanjut.due_date <= '{$threeDaysFromNow}' THEN 1
                    ELSE 2
                END ASC,
                tindak_lanjut.due_date ASC,
                temuan.created_at DESC
            ")
            ->paginate(10);

        return view('livewire.daftar-temuan-pic', [
            'temuans'    => $temuans,
            'badgeCount' => $badgeCount,
            'totalAktif' => $totalAktif,
            'metrics'    => $metrics,
            'today'      => $today,
        ]);
    }
}
