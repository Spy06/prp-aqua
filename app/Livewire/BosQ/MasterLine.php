<?php

namespace App\Livewire\BosQ;

use App\Models\BosqLine;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class MasterLine extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';
    public ?int $lineId = null;
    public string $nama_line = '';
    public ?int $default_auditee_id = null;
    public bool $isEditing = false;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->isEditing = false;
    }

    public function edit(int $id): void
    {
        $line = BosqLine::findOrFail($id);
        $this->lineId = $line->id;
        $this->nama_line = $line->nama_line;
        $this->default_auditee_id = $line->default_auditee_id;
        $this->isEditing = true;
    }

    public function save(): void
    {
        $this->validate([
            'nama_line'          => 'required|string|max:255|unique:bosq_line,nama_line,' . ($this->lineId ?: 'NULL'),
            'default_auditee_id' => 'nullable|exists:users,id',
        ], [
            'nama_line.required' => 'Nama Line wajib diisi.',
            'nama_line.unique'   => 'Nama Line sudah ada.',
        ]);

        if ($this->isEditing && $this->lineId) {
            $line = BosqLine::findOrFail($this->lineId);
            $line->update([
                'nama_line'          => $this->nama_line,
                'default_auditee_id' => $this->default_auditee_id ?: null,
            ]);
            session()->flash('success', 'Master Line berhasil diperbarui!');
        } else {
            BosqLine::create([
                'nama_line'          => $this->nama_line,
                'default_auditee_id' => $this->default_auditee_id ?: null,
            ]);
            session()->flash('success', 'Master Line baru berhasil ditambahkan!');
        }

        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $line = BosqLine::findOrFail($id);
        $line->delete();
        session()->flash('success', 'Master Line berhasil dihapus!');
    }

    public function resetForm(): void
    {
        $this->lineId = null;
        $this->nama_line = '';
        $this->default_auditee_id = null;
        $this->isEditing = false;
        $this->resetValidation();
    }

    public function render()
    {
        $lines = BosqLine::with('defaultAuditee')
            ->where('nama_line', 'like', '%' . $this->search . '%')
            ->orderBy('nama_line')
            ->paginate(10);

        $users = User::orderBy('name')->get();

        return view('livewire.bosq.master-line', [
            'lines' => $lines,
            'users' => $users,
        ])->layout('layouts.bosq', ['title' => 'Master Line — BOS\'Q']);
    }
}
