<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;

class DepartmentQuestion extends Model
{
    protected $fillable = [
        'text',
        'depends_on_answer_id',
        'type',
        'requires_confirmation',
        'requires',
        'default_answers',
        'default_answer',
        'is_show',
    ];

    public function answers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DepartmentAnswer::class);
    }

    public function dependsOnAnswer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DepartmentAnswer::class, 'depends_on_answer_id');
    }

//    public function dependsOnDiagnosisGroup(): \Illuminate\Database\Eloquent\Relations\BelongsTo
//    {
//        return $this->belongsTo(DiagnosisGroup::class, 'depends_on_diagnosis_group_id');
//    }

    public function dependsDiagnosisGroup(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(DiagnosisGroupRelations::class, 'diagnosable');
    }

//    public function diagnosis_group(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
//    {
//        return $this->belongsToMany(DiagnosisGroup::class,
//            'department_question_diagnosis_groups',
//            'id',
//            'department_question_id'
//        )
//            ->withPivot('diagnosis_group_id');
//    }

    protected function casts(): array
    {
        return [
            'default_answers' => AsArrayObject::class,
        ];
    }
}
