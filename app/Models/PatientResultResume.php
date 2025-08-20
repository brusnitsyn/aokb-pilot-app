<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientResultResume extends Model
{
    protected $fillable = [
        'patient_result_id',
        'content',
    ];

    public function patientResult(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PatientResult::class);
    }
}
