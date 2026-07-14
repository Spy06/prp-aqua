<?php

namespace App\Livewire;

use App\Jobs\SendWhatsAppDummy;
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
        'pic_id'         => 'required|exists:users,id',
    ];

    public function mount()
    {
        $this->tanggal_temuan = Carbon::now()->format('Y-m-d');
    }

    public function updatedPicSearch()
    {
        if (strlen($this->picSearch) >= 2) {
            $this->picResults = User::where(function($q) {
                $q->where('name', 'like', '%' . $this->picSearch . '%')
                  ->orWhere('nik', 'like', '%' . $this->picSearch . '%');
            })
            // Hanya user dengan akun aktif (semua yg ada di users berarti punya akun)
            ->whereNotNull('id')
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
                
                // Gunakan dummy job dari Hari 2 dengan mengirimkan pesan yang sudah dirangkai
                // Karena SendWhatsAppDummy belum menerima parameter dari luar, kita perlu mengubah sedikit jobnya.
                // Atau, untuk mematuhi requirement, kita bisa update SendWhatsAppDummy untuk support parameter message & to.
                // Tapi sesuai arahan, yang penting logic jobnya tereksekusi. Saya akan passing parameter ke Job.
                SendWhatsAppDummy::dispatch($pic->no_whatsapp, $message);
            }

            // Bersihkan form
            $this->reset(['sub_area', 'klausul_id', 'foto_temuan', 'deskripsi', 'pic_id', 'picSearch']);
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
        return view('livewire.form-temuan', [
            'departemens' => Departemen::orderBy('nama_departemen')->get(),
            'klausuls'    => \App\Models\KlausulPrp::orderBy('id')->get(),
        ]);
    }
}
