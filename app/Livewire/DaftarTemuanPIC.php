<?php

namespace App\Livewire;

use App\Models\Temuan;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Carbon;

class DaftarTemuanPIC extends Component
{
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

        // Ambil semua temuan di mana user login adalah PIC
        // Kecualikan yang sudah closed_acc (tidak perlu ditindaklanjuti lagi)
        $temuans = Temuan::with(['departemen', 'pelapor', 'klausul', 'tindakLanjut'])
            ->where('pic_id', auth()->id())
            ->whereNotIn('status', ['closed_acc'])
            ->orderByRaw("
                CASE 
                    WHEN status = 'closed_pending_qa' THEN 3
                    WHEN status = 'in_progress' THEN 2
                    WHEN status = 'open' THEN 1
                    ELSE 0
                END ASC,
                created_at ASC
            ")
            ->get();

        // Urutkan ulang dengan urgensi due date:
        // 1. Temuan open/in_progress yang due date-nya sudah lewat (overdue) → paling urgent
        // 2. Temuan open/in_progress yang due date mendekati (< 3 hari)
        // 3. Lainnya
        $temuans = $temuans->sortBy(function ($temuan) use ($today) {
            $tl = $temuan->tindakLanjut;
            if (!$tl || in_array($temuan->status, ['closed_pending_qa', 'closed_acc'])) {
                // Sudah pending/closed → taruh di belakang berdasarkan created_at
                return [3, $temuan->created_at->timestamp];
            }

            $dueDate = $tl->due_date;
            if (!$dueDate) {
                return [2, PHP_INT_MAX]; // Tidak ada due date → urutan tengah
            }

            if ($dueDate->lt($today)) {
                return [0, $dueDate->timestamp]; // Overdue → paling atas
            }

            $daysLeft = $today->diffInDays($dueDate, false);
            if ($daysLeft <= 3) {
                return [1, $dueDate->timestamp]; // Mendekati due date
            }

            return [2, $dueDate->timestamp]; // Normal
        })->values();

        // Hitung badge: temuan yang masih aktif (bukan closed_acc, bukan closed_pending_qa)
        $badgeCount = Temuan::where('pic_id', auth()->id())
            ->whereIn('status', ['open', 'in_progress'])
            ->count();

        // metrics calculation
        $metrics = [
            'open' => Temuan::where('pic_id', auth()->id())->where('status', 'open')->count(),
            'in_progress' => Temuan::where('pic_id', auth()->id())->where('status', 'in_progress')->count(),
            'pending_qa' => Temuan::where('pic_id', auth()->id())->where('status', 'closed_pending_qa')->count(),
            'closed' => Temuan::where('pic_id', auth()->id())->where('status', 'closed_acc')->count(),
        ];

        // Juga hitung total semua yang belum closed_acc (untuk info)
        $totalAktif = Temuan::where('pic_id', auth()->id())
            ->whereNotIn('status', ['closed_acc'])
            ->count();

        return view('livewire.daftar-temuan-pic', [
            'temuans'    => $temuans,
            'badgeCount' => $badgeCount,
            'totalAktif' => $totalAktif,
            'metrics'    => $metrics,
            'today'      => $today,
        ]);
    }
}
