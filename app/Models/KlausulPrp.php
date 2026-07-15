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
    public function temuans(): HasMany
    {
        return $this->hasMany(Temuan::class, 'klausul_id');
    }
}
