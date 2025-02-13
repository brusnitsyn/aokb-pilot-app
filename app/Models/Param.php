<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Param extends Model
{
    protected $fillable = [
        'name'
    ];

    public function paramValue(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ParamValue::class);
    }
}
