<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;

class DepartmentParam extends Model
{
    protected $fillable = [
        'department_id',
        'param_id',
        'param_value_id',
        'depends_diagnosis_group_ids'
    ];

    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function param(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Param::class, 'param_id');
    }

    public function paramValue(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ParamValue::class, 'param_value_id');
    }

    protected function casts(): array
    {
        return [
            'depends_diagnosis_group_ids' => AsArrayObject::class,
        ];
    }
}
