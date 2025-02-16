<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentDiagnosisGroup extends Model
{
    protected $fillable = [
        'diagnosis_group_id',
        'department_id',
    ];

    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function diagnosisGroup(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DiagnosisGroup::class);
    }
}
