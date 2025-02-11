<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosisGroup extends Model
{
    protected $fillable = [
        'name',
        'shortname'
    ];

    public function diagnoses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Diagnosis::class);
    }
}
