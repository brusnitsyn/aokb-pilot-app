<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParamValue extends Model
{
    protected $fillable = [
        'param_id',
        'value_name',
        'score',
    ];

    public function param(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Param::class, 'param_id');
    }
}
