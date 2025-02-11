<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'full_name',
        'total_score',
        'diagnosis_id'
    ];

    public function diagnosis()
    {
        return $this->belongsTo(Diagnosis::class);
    }
}
