<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentQuestion extends Model
{
    protected $fillable = [
        'text',
        'depends_on_answer_id',
        'department_id'
    ];

    public function answers()
    {
        return $this->hasMany(DepartmentAnswer::class);
    }

    public function dependsOnAnswer()
    {
        return $this->belongsTo(DepartmentAnswer::class, 'depends_on_answer_id');
    }
}
