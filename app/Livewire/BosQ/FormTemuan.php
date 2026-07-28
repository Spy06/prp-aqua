<?php

namespace App\Livewire\BosQ;

use App\Jobs\SendWhatsApp;
use App\Models\BosqElemenQfs;
use App\Models\BosqSubArea;
use App\Models\BosqTemuan;
use App\Models\BosqTindakLanjut;
use App\Models\Departemen;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FormTemuan extends Component
{
    public string $tanggal_temuan  = '';
    public ?int   $departemen_id   = null;
    public ?int   $sub_area_id     = null;
    public string $detail_sub_area = '';
    public ?int   $elemen_qfs_id   = null;
    public string $temuan_bqa      = '';
    public string $tingkat_resiko  = 'minor_quality_risk';
    public string $dampak_temuan    = 'negatif';
    public string $action_negatif   = '';
    public string $due_date_action  = '';

    // Auditee searchable
    public string $auditeeSearch  = '';
    public ?int   $auditee_id     = null;
    public array  $auditeeResults = [];

    public function mount(): void
    {
        $this->tanggal_temuan  = Carbon::now()->format('Y-m-d');
        $this->due_date_action = Carbon::now()->addDays(7)->format('Y-m-d');

        // Default departemen dari karyawan yang login
        $karyawan = auth()->user()->karyawan;
        if ($karyawan) {
            $this->departemen_id = $karyawan->departemen_id;
        }
    }

    public function getIsSubAreaOthersProperty(): bool
    {
        if (!$this->sub_area_id) {
            return false;
        }
        $sa = BosqSubArea::find($this->sub_area_id);
        return $sa && strtolower(trim($sa->nama_sub_area)) === 'others';
    }

    public function updatedDepartemenId(): void
    {
        $this->sub_area_id    = null;
        $this->detail_sub_area = '';
    }

    public function updatedSubAreaId($value): void
    {
        if ($value) {
            $sa = BosqSubArea::find($value);
            if (!$sa || strtolower(trim($sa->nama_sub_area)) !== 'others') {
                $this->detail_sub_area = '';
            }
        } else {
            $this->detail_sub_area = '';
        }
    }

    public function updatedDampakTemuan($value): void
    {
        if ($value === 'positif') {
            $this->action_negatif  = '';
            $this->due_date_action = '';
        } else {
            if (empty($this->due_date_action)) {
                $this->due_date_action = Carbon::now()->addDays(7)->format('Y-m-d');
            }
        }
    }

    public function updatedAuditeeSearch(): void
    {
        $this->auditee_id = null; // reset saat user mengetik
        if (strlen($this->auditeeSearch) >= 2) {
            $this->auditeeResults = User::where(function ($q) {
                    $q->where('name', 'like', '%' . $this->auditeeSearch . '%')
                      ->orWhere('nik', 'like', '%' . $this->auditeeSearch . '%');
                })
                ->take(7)
                ->get(['id', 'name', 'nik', 'no_whatsapp'])
                ->toArray();
        } else {
            $this->auditeeResults = [];
        }
    }

    public function selectAuditee(int $userId, string $userName): void
    {
        $this->auditee_id     = $userId;
        $this->auditeeSearch  = $userName;
        $this->auditeeResults = [];
    }

    public function clearAuditee(): void
    {
        $this->auditee_id     = null;
        $this->auditeeSearch  = '';
        $this->auditeeResults = [];
    }

    public function submit(): void
    {
        $this->validate([
            'tanggal_temuan'  => 'required|date',
            'departemen_id'   => 'required|exists:departemen,id',
            'sub_area_id'     => 'required|exists:bosq_sub_area,id',
            'detail_sub_area' => $this->isSubAreaOthers ? 'required|string|max:255' : 'nullable|string|max:255',
            'elemen_qfs_id'   => 'required|exists:bosq_elemen_qfs,id',
            'temuan_bqa'      => 'required|string',
            'tingkat_resiko'  => 'required|in:food_safety_risk,major_quality_risk,minor_quality_risk',
            'dampak_temuan'   => 'required|in:negatif,positif',
            'action_negatif'  => $this->dampak_temuan === 'negatif' ? 'required|string' : 'nullable|string',
            'due_date_action' => $this->dampak_temuan === 'negatif' ? 'required|date' : 'nullable|date',
            'auditee_id'      => 'required|exists:users,id',
        ], [
            'auditee_id.required'      => 'Auditee wajib dipilih.',
            'detail_sub_area.required' => 'Detail Sub Area wajib diisi jika memilih Others.',
            'action_negatif.required'  => 'Action wajib diisi jika dampak observasi negatif.',
            'due_date_action.required' => 'Due Date Action wajib diisi jika dampak observasi negatif.',
        ]);

        $user      = auth()->user();
        $isNegatif = $this->dampak_temuan === 'negatif';
        $status    = $isNegatif ? 'open' : 'closed';

        DB::beginTransaction();
        try {
            $temuan = BosqTemuan::create([
                'tanggal_temuan'  => $this->tanggal_temuan,
                'pelapor_id'      => $user->id,
                'auditee_id'      => $this->auditee_id,
                'departemen_id'   => $this->departemen_id,
                'line_id'         => null,
                'sub_area_id'     => $this->sub_area_id,
                'detail_sub_area' => $this->isSubAreaOthers ? ($this->detail_sub_area ?: null) : null,
                'elemen_qfs_id'   => $this->elemen_qfs_id,
                'temuan_bqa'      => $this->temuan_bqa,
                'tingkat_resiko'  => $this->tingkat_resiko,
                'dampak_temuan'   => $this->dampak_temuan,
                'status'          => $status,
            ]);

            if ($isNegatif) {
                BosqTindakLanjut::create([
                    'bosq_temuan_id' => $temuan->id,
                    'action'         => $this->action_negatif,
                    'due_date'       => $this->due_date_action ?: null,
                    'status'         => 'open',
                    'acc_qa'         => false,
                ]);
            }

            DB::commit();

            // Dispatch WA untuk temuan negatif -> dikirim ke tim QA
            if ($isNegatif) {
                $auditeeObj = User::find($this->auditee_id);
                $link = route('bosq.temuan.detail', $temuan->id);
                $msg  = "*[BOS'Q] Observasi Baru Perlu Verifikasi QA*\n\n"
                      . "Observer: " . $user->name . "\n"
                      . "Auditee: " . ($auditeeObj?->name ?? '-') . "\n"
                      . "📍 *Elemen*: " . (BosqElemenQfs::find($this->elemen_qfs_id)?->nama_elemen ?? '-') . "\n"
                      . "⚠️ *Tingkat Risiko*: " . $this->tingkatResikoLabel($this->tingkat_resiko) . "\n"
                      . "📌 *Action*: " . $this->action_negatif . "\n"
                      . "📅 *Due Date*: " . Carbon::parse($this->due_date_action)->format('d F Y') . "\n\n"
                      . "Buka dan verifikasi di:\n{$link}";

                $qaUsers = User::where('role', 'qa')->whereNotNull('no_whatsapp')->get();
                foreach ($qaUsers as $qa) {
                    SendWhatsApp::dispatch($qa->no_whatsapp, $msg);
                }
            }

            // Reset form
            $this->reset(['sub_area_id', 'detail_sub_area', 'elemen_qfs_id', 'temuan_bqa',
                'auditee_id', 'auditeeSearch', 'auditeeResults', 'action_negatif', 'due_date_action']);
            $this->tingkat_resiko  = 'minor_quality_risk';
            $this->dampak_temuan   = 'negatif';
            $this->tanggal_temuan  = Carbon::now()->format('Y-m-d');
            $this->due_date_action = Carbon::now()->addDays(7)->format('Y-m-d');

            session()->flash('success', "Observasi BOS'Q berhasil dilaporkan!" .
                ($isNegatif ? ' Laporan telah diteruskan ke tim QA untuk verifikasi.' : ' Status langsung Closed (Positif).'));

            $this->dispatch('temuanAdded');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function tingkatResikoLabel(string $tingkat): string
    {
        return match ($tingkat) {
            'food_safety_risk'    => 'Food Safety Risk',
            'major_quality_risk'  => 'Major Quality Risk',
            'minor_quality_risk'  => 'Minor Quality Risk',
            default               => $tingkat,
        };
    }

    public function render()
    {
        $subAreas = $this->departemen_id
            ? BosqSubArea::where(function ($q) {
                $q->whereNull('departemen_id')
                  ->orWhere('departemen_id', $this->departemen_id);
              })
              ->orderByRaw("CASE WHEN nama_sub_area = 'Others' THEN 1 ELSE 0 END")
              ->orderBy('nama_sub_area')
              ->get()
            : collect();

        return view('livewire.bosq.form-temuan', [
            'departemens' => Departemen::orderBy('nama_departemen')->get(),
            'subAreas'    => $subAreas,
            'elemenList'  => BosqElemenQfs::orderBy('nama_elemen')->get(),
        ]);
    }
}
