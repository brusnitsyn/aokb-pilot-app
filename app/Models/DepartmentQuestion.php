<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;

class DepartmentQuestion extends Model
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
        return $this->hasMany(DepartmentAnswer::class);
    }

    public function dependsOnAnswer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DepartmentAnswer::class, 'depends_on_answer_id');
    }

    protected function casts(): array
    {
        return [
            'default_answers' => AsArrayObject::class,
        ];
    }
}
