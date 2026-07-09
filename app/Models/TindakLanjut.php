<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TindakLanjut extends Model
{
    protected $table = 'tindak_lanjut';

    protected $fillable = [
        'temuan_id',
        'klausul_id',
        'action',
        'due_date',
        'foto_bukti_path',
        'status',
        'acc_qa',
        'tanggal_acc',
        'catatan_qa',
    ];

    /**
     * Cast field.
     * catatan_qa di-encrypt sesuai implementation plan §10.3.
     */
    protected function casts(): array
    {
        return [
            'due_date'    => 'date',
            'tanggal_acc' => 'date',
            'acc_qa'      => 'boolean',
            'catatan_qa'  => 'encrypted',
        ];
    }

    /**
     * Temuan yang ditindaklanjuti.
     */
    public function temuan(): BelongsTo
    {
        return $this->belongsTo(Temuan::class, 'temuan_id');
    }

    /**
     * Klausul PRP yang relevan dengan tindak lanjut ini.
     */
    public function klausul(): BelongsTo
    {
        return $this->belongsTo(KlausulPrp::class, 'klausul_id');
    }
}
