<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Temuan extends Model
{
    protected $table = 'temuan';

    protected $fillable = [
        'tanggal_temuan',
        'pelapor_id',
        'pic_id',
        'klausul_id',
        'departemen_id',
        'sub_area',
        'foto_temuan_path',
        'deskripsi',
        'status',
    ];

    /**
     * Cast field-field yang perlu enkripsi atau konversi tipe.
     * deskripsi di-encrypt sesuai implementation plan §10.3.
     */
    protected function casts(): array
    {
        return [
            'tanggal_temuan' => 'date',
            'deskripsi'      => 'encrypted',
        ];
    }

    /**
     * User yang membuat laporan temuan ini (Pelapor).
     */
    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    /**
     * User yang ditunjuk sebagai PIC untuk temuan ini.
     * pic_id ada di temuan, bukan di tindak_lanjut — sesuai §5.
     */
    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    /**
     * Departemen lokasi temuan.
     */
    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'departemen_id');
    }

    /**
     * Klausul PRP temuan ini.
     */
    public function klausul(): BelongsTo
    {
        return $this->belongsTo(KlausulPrp::class, 'klausul_id');
    }

    /**
     * Tindak lanjut dari temuan ini (1:1 sesuai ERD §5).
     */
    public function tindakLanjut(): HasOne
    {
        return $this->hasOne(TindakLanjut::class, 'temuan_id');
    }
}
