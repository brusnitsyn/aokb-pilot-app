<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentParam extends Model
{
    protected $fillable = [
        'department_id',
        'param_id',
        'param_value_id',
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
}
