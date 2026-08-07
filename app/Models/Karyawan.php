<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Karyawan extends Model
{
    use HasFactory;
    /**
     * Primary key berupa string (NIK).
     */
    protected $table = 'karyawan';

    protected $primaryKey = 'nik';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nik',
        'nama',
        'departemen_id',
        'status_aktif',
        'is_pic',
        'is_anggota_divisi_manajemen',
    ];

    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
            'is_pic' => 'boolean',
            'is_anggota_divisi_manajemen' => 'boolean',
        ];
    }

    /**
     * Departemen tempat karyawan ini bekerja.
     */
    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'departemen_id');
    }

    /**
     * Akun user yang dimiliki karyawan ini (jika sudah punya akun sistem).
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'nik', 'nik');
    }
}
