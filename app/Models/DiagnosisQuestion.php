<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosisQuestion extends Model
{
    protected $fillable = [
        'diagnosis_id',
        'question_id',
    ];
}
