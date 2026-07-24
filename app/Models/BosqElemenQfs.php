<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BosqElemenQfs extends Model
{
    use HasFactory;

    protected $table = 'bosq_elemen_qfs';

    protected $fillable = [
        'nama_elemen',
        'deskripsi',
    ];

    public function temuans(): HasMany
    {
        return $this->hasMany(BosqTemuan::class, 'elemen_qfs_id');
    }
}
