<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departemen extends Model
{
    use HasFactory;
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
