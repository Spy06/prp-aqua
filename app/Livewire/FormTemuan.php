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
    public $detail_sub_area;

    // For PIC Search
    public $picSearch = '';
    public $pic_id;
    public $picResults = [];

    protected $rules = [
        'tanggal_temuan' => 'required|date',
        'departemen_id' => 'required|exists:departemen,id',
        'sub_area' => 'required|string|max:255',
        'klausul_id' => 'required|exists:klausul_prp,id',
        'detail_sub_area' => 'required_if:sub_area,Others|nullable|string|max:255',
        'foto_temuan' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072', // max 3MB (opsional)
        'deskripsi' => 'required|string',
        'saran' => 'nullable|string',
        'pic_id' => 'required|exists:users,id',
    ];

    protected $messages = [
        'foto_temuan.image' => 'Format file tidak sesuai. Upload gambar berformat JPG, PNG, atau WebP.',
        'foto_temuan.mimes' => 'Format file tidak sesuai. Upload gambar berformat JPG, PNG, atau WebP.',
        'foto_temuan.max' => 'Ukuran foto terlalu besar. Maksimal ukuran file adalah 3MB.',
        'foto_temuan.uploaded' => 'Gagal mengupload foto. Pastikan ukuran file maksimal 3MB dan formatnya sesuai (JPG, PNG, WebP).',
    ];

    public function mount()
    {
        $this->tanggal_temuan = Carbon::now()->format('Y-m-d');
    }

    public function updatedDepartemenId()
    {
        $this->sub_area = null;
        $this->detail_sub_area = null;
    }

    public function updatedSubArea($value)
    {
        if (strtolower(trim($value ?? '')) !== 'others') {
            $this->detail_sub_area = null;
        }
    }

    public function updatedPicSearch()
    {
        $this->pic_id = null; // Reset selection saat mengetik
        $query = trim($this->picSearch);

        if (strlen($query) >= 1) {
            $this->picResults = User::whereHas('karyawan', function ($q) {
                    $q->where('status_aktif', true);
                })
                ->where('role', '!=', 'superadmin')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%')
                        ->orWhere('nik', 'like', '%' . $query . '%');
                })
                ->orderBy('name', 'asc')
                ->take(15)
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
        $this->picResults = [];
    }

    public function submit()
    {
        $this->validate();

        $pic = User::find($this->pic_id);
        if (!$pic) {
            $this->addError('pic_id', 'PIC yang dipilih tidak ditemukan.');
            return;
        }

        $user = auth()->user();

        // 1. Simpan foto jika diupload
        $fotoPath = $this->foto_temuan ? $this->foto_temuan->store('temuan', 'public') : null;

        DB::beginTransaction();
        try {
            // 2. Buat record Temuan
            $temuan = Temuan::create([
                'tanggal_temuan' => $this->tanggal_temuan,
                'pelapor_id' => $user->id,
                'pic_id' => $this->pic_id,
                'departemen_id' => $this->departemen_id,
                'sub_area' => $this->sub_area,
                'detail_sub_area' => $this->sub_area === 'Others' ? $this->detail_sub_area : null,
                'klausul_id' => $this->klausul_id,
                'foto_temuan_path' => $fotoPath,
                'deskripsi' => $this->deskripsi,
                'saran' => $this->saran,
                'status' => 'open',
            ]);

            // 3. Buat record Tindak Lanjut awal
            TindakLanjut::create([
                'temuan_id' => $temuan->id,
                'status' => 'open',
                'acc_qa' => false,
            ]);

            DB::commit();

            // 4. Kirim Email Notifikasi SIVERA ke PIC Ditunjuk & QA Auditors
            $emailService = app(\App\Services\EmailNotificationService::class);
            $emailService->sendSiveraNotification($temuan, 'baru');

            $qaUsers = User::where('role', 'qa')->get();
            foreach ($qaUsers as $qa) {
                if ($qa->email) {
                    $emailService->sendSiveraNotification($temuan, 'baru', $qa->email);
                }
            }

            // 5. Bersihkan form
            $this->reset(['sub_area', 'detail_sub_area', 'klausul_id', 'foto_temuan', 'deskripsi', 'saran', 'pic_id', 'picSearch']);
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
            $subAreas = \App\Models\SubArea::where('departemen_id', $this->departemen_id)
                ->orderByRaw("CASE WHEN nama_sub_area = 'Others' THEN 1 ELSE 0 END")
                ->orderBy('nama_sub_area')
                ->get();
        }

        return view('livewire.form-temuan', [
            'departemens' => Departemen::orderBy('nama_departemen')->get(),
            'klausuls' => \App\Models\KlausulPrp::orderBy('id')->get(),
            'subAreas' => $subAreas,
        ]);
    }
}
