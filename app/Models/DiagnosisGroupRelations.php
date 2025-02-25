<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosisGroupRelations extends Model
{
    protected $fillable = [
        'diagnosable_id',
        'diagnosable_type',
        'diagnosis_group_id'
    ];

    public function diagnosable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('diagnosable');
    }

    public function diagnosis_group(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Models\DiagnosisGroup::class, 'id', 'diagnosis_group_id');
    }
}
