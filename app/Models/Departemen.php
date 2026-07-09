<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departemen extends Model
{
    protected $table = 'departemen';

    protected $fillable = [
        'nama_departemen',
    ];

    /**
     * Karyawan yang berada di departemen ini.
     */
    public function karyawans(): HasMany
    {
        return $this->hasMany(Karyawan::class, 'departemen_id');
    }

    /**
     * Temuan yang terjadi di departemen ini.
     */
    public function temuans(): HasMany
    {
        return $this->hasMany(Temuan::class, 'departemen_id');
    }
}
