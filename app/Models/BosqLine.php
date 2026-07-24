<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BosqLine extends Model
{
    use HasFactory;

    protected $table = 'bosq_line';

    protected $fillable = [
        'nama_line',
        'default_auditee_id',
    ];

    public function defaultAuditee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'default_auditee_id');
    }

    public function temuans(): HasMany
    {
        return $this->hasMany(BosqTemuan::class, 'line_id');
    }
}
