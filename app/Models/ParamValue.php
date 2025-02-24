<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;

class ParamValue extends Model
{
    protected $fillable = [
        'param_id',
        'value_name',
        'score',
        'depends_diagnosis_group_ids'
    ];

    public function param(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Param::class, 'param_id');
    }

    protected function casts(): array
    {
        return [
            'depends_diagnosis_group_ids' => AsArrayObject::class,
        ];
    }
}
