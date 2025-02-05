<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentAnswerDepartment extends Model
{
    protected $fillable = [
        'department_answer_id',
        'department_id',
        'score',
    ];
}
