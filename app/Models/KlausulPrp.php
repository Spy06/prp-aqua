<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KlausulPrp extends Model
{
    protected $table = 'klausul_prp';

    protected $fillable = [
        'kode_klausul',
        'nama_klausul',
    ];

    /**
     * Tindak lanjut yang mengacu ke klausul ini.
     */
    public function tindakLanjuts(): HasMany
    {
        return $this->hasMany(TindakLanjut::class, 'klausul_id');
    }
}
