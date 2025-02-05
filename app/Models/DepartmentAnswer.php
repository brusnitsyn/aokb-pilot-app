<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentAnswer extends Model
{
    protected $fillable = [
        'department_question_id',
        'text',
        'score',
    ];

    public function question(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DepartmentQuestion::class, 'department_question_id');
    }

    public function departments(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_answer_departments')
            ->withPivot('score');
    }
}
