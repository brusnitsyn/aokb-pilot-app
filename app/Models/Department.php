<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'address',
        'fias_objectid',
        'regionId'
    ];

    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class, 'regionId', 'id');
    }

    public function answers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(DepartmentAnswer::class, 'department_answer_departments')
            ->withPivot('score');
    }
}
