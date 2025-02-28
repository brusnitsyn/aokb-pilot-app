<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scenario extends Model
{
    protected $fillable = [
        'name',
        'score',
        'color',
        'code'
    ];

    public function answer(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Answer::class);
    }
}
