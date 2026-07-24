<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BosqSubArea extends Model
{
    use HasFactory;

    protected $table = 'bosq_sub_area';

    protected $fillable = [
        'departemen_id',
        'nama_sub_area',
    ];

    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'departemen_id');
    }
}
