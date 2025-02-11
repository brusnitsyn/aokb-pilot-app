<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'text',
        'depends_on_answer_id',
        'type',
        'requires_confirmation',
        'requires',
        'default_answers',
        'default_answer'
    ];

    public function answers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function dependsOnAnswer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Answer::class, 'depends_on_answer_id');
    }

    public function diagnoses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Diagnosis::class, 'diagnosis_questions');
    }
}
