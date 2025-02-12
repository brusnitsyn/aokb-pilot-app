<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;

class PatientResult extends Model
{
    protected $fillable = [
        'patient_id',
        'department_id',
        'patient_score',
        'department_score',
        'total_score',
        'patient_responses',
        'department_responses'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    protected function casts(): array
    {
        return [
            'patient_responses' => AsArrayObject::class,
            'department_responses' => AsArrayObject::class,
        ];
    }
}
