<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubArea extends Model
{
    protected $fillable = ['departemen_id', 'nama_sub_area'];

    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }
}
