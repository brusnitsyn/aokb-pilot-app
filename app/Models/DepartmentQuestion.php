<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentQuestion extends Model
{
    protected $fillable = [
        'text',
        'depends_on_answer_id',
        'type'
    ];

    public function answers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DepartmentAnswer::class);
    }

    public function dependsOnAnswer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DepartmentAnswer::class, 'depends_on_answer_id');
    }
}
