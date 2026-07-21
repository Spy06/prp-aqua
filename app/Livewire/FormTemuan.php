<?php

namespace App\Livewire;

use App\Jobs\SendWhatsApp;
use App\Models\Departemen;
use App\Models\Temuan;
use App\Models\TindakLanjut;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class FormTemuan extends Component
{
    use WithFileUploads;

    public $tanggal_temuan;
    public $departemen_id;
    public $sub_area;
    public $klausul_id;
    public $foto_temuan;
    public $deskripsi;
    public $saran = '';
    
    // For PIC Search
    public $picSearch = '';
    public $pic_id;
    public $picResults = [];

    protected $rules = [
        'tanggal_temuan' => 'required|date',
        'departemen_id'  => 'required|exists:departemen,id',
        'sub_area'       => 'required|string|max:255',
        'klausul_id'     => 'required|exists:klausul_prp,id',
        'foto_temuan'    => 'required|image|max:5120', // max 5MB
        'deskripsi'      => 'required|string',
        'saran'          => 'nullable|string',
        'pic_id'         => 'required|exists:users,id',
    ];

    public function mount()
    {
        $this->tanggal_temuan = Carbon::now()->format('Y-m-d');
    }

    public function updatedDepartemenId()
    {
        $this->sub_area = null;
    }

    public function updatedPicSearch()
    {
        if (strlen($this->picSearch) >= 2) {
            $this->picResults = User::where('role', 'karyawan')
                ->where(function($q) {
                    $q->where('name', 'like', '%' . $this->picSearch . '%')
                      ->orWhere('nik', 'like', '%' . $this->picSearch . '%');
                })
                ->take(5)
                ->get();
        } else {
            $this->picResults = [];
        }
    }

    public function selectPic($userId, $userName)
    {
        $this->pic_id = $userId;
        $this->picSearch = $userName;
        $this->picResults = [];
    }

    public function clearPic()
    {
        $this->pic_id = null;
        $this->picSearch = '';
    }

    public function submit()
    {
        $this->validate();

        $pic = User::find($this->pic_id);
        if (!$pic || $pic->role !== 'karyawan') {
            $this->addError('pic_id', 'PIC yang dipilih harus karyawan, bukan QA.');
            return;
        }

        $user = auth()->user();

        // 1. Simpan foto
        $fotoPath = $this->foto_temuan->store('temuan', 'public');

        DB::beginTransaction();
        try {
            // 2. Buat record Temuan
            $temuan = Temuan::create([
                'tanggal_temuan'   => $this->tanggal_temuan,
                'pelapor_id'       => $user->id,
                'pic_id'           => $this->pic_id,
                'departemen_id'    => $this->departemen_id,
                'sub_area'         => $this->sub_area,
                'klausul_id'       => $this->klausul_id,
                'foto_temuan_path' => $fotoPath,
                'deskripsi'        => $this->deskripsi,
                'saran'            => $this->saran,
                'status'           => 'open',
            ]);

            // 3. Buat record Tindak Lanjut awal
            TindakLanjut::create([
                'temuan_id'       => $temuan->id,
                'status'          => 'open',
                'acc_qa'          => false,
            ]);

            DB::commit();

            // 4. Dispatch WA Job ke PIC
            $pic = User::find($this->pic_id);
            if ($pic && $pic->no_whatsapp) {
                $message = "Halo {$pic->name}, Anda ditunjuk sebagai PIC untuk temuan PRP baru. "
                         . "Segera tindak lanjuti di sini: " . route('temuan.detail', ['temuan' => $temuan->id]);
                
                SendWhatsApp::dispatch($pic->no_whatsapp, $message);
            }

            // 5. Kirim notifikasi WA ke QA untuk setiap temuan baru
            $qaUsers = User::where('role', 'qa')->get();
            foreach ($qaUsers as $qa) {
                if ($qa->no_whatsapp) {
                    $messageQA = "Halo QA ({$qa->name}), ada laporan temuan PRP baru (#{$temuan->id}) diajukan oleh " . auth()->user()->name . ".\n"
                               . "Sub Area: {$temuan->sub_area}\n"
                               . (!empty($this->saran) ? "Saran: \"{$this->saran}\"\n" : "")
                               . "Detail temuan dapat dilihat di: " . route('temuan.detail', ['temuan' => $temuan->id]);
                    SendWhatsApp::dispatch($qa->no_whatsapp, $messageQA);
                }
            }

            // Bersihkan form
            $this->reset(['sub_area', 'klausul_id', 'foto_temuan', 'deskripsi', 'saran', 'pic_id', 'picSearch']);
            $this->tanggal_temuan = Carbon::now()->format('Y-m-d');
            
            session()->flash('success', 'Laporan temuan berhasil dikirim!');
            
            // Refresh parent view data (DaftarTemuanPelapor)
            $this->dispatch('temuanAdded');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $subAreas = [];
        if ($this->departemen_id) {
            $subAreas = \App\Models\SubArea::where('departemen_id', $this->departemen_id)->orderBy('nama_sub_area')->get();
        }

        return view('livewire.form-temuan', [
            'departemens' => Departemen::orderBy('nama_departemen')->get(),
            'klausuls'    => \App\Models\KlausulPrp::orderBy('id')->get(),
            'subAreas'    => $subAreas,
        ]);
    }
}
