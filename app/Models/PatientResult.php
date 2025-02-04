<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientResult extends Model
{
    protected $fillable = [
        'patient_id',
        'department_id',
        'patient_score',
        'department_score',
        'total_score',
    ];
}
