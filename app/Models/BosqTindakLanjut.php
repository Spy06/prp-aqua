<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BosqTindakLanjut extends Model
{
    use HasFactory;

    protected $table = 'bosq_tindak_lanjut';

    protected $fillable = [
        'bosq_temuan_id',
        'action',
        'due_date',
        'foto_bukti_path',
        'status',
        'acc_qa',
        'tanggal_acc',
        'catatan_qa',
    ];

    protected function casts(): array
    {
        return [
            'due_date'    => 'date',
            'tanggal_acc' => 'date',
            'acc_qa'      => 'boolean',
            'catatan_qa'  => 'encrypted',
        ];
    }

    public function temuan(): BelongsTo
    {
        return $this->belongsTo(BosqTemuan::class, 'bosq_temuan_id');
    }

    public function getBuktiPathsAttribute(): array
    {
        if (empty($this->foto_bukti_path)) {
            return [];
        }
        $decoded = json_decode($this->foto_bukti_path, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        return [$this->foto_bukti_path];
    }
}
