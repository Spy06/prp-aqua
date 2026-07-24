<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BosqTemuan extends Model
{
    use HasFactory;

    protected $table = 'bosq_temuan';

    protected $fillable = [
        'tanggal_temuan',
        'pelapor_id',
        'auditee_id',
        'departemen_id',
        'line_id',
        'sub_area_id',
        'detail_sub_area',
        'elemen_qfs_id',
        'temuan_bqa',
        'tingkat_resiko',
        'dampak_temuan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_temuan' => 'date',
            'temuan_bqa'     => 'encrypted',
        ];
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    public function auditee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auditee_id');
    }

    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'departemen_id');
    }

    public function line(): BelongsTo
    {
        return $this->belongsTo(BosqLine::class, 'line_id');
    }

    public function subArea(): BelongsTo
    {
        return $this->belongsTo(BosqSubArea::class, 'sub_area_id');
    }

    public function elemenQfs(): BelongsTo
    {
        return $this->belongsTo(BosqElemenQfs::class, 'elemen_qfs_id');
    }

    public function tindakLanjut(): HasOne
    {
        return $this->hasOne(BosqTindakLanjut::class, 'bosq_temuan_id');
    }
}
