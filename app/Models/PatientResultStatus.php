<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientResultStatus extends Model
{
    protected $fillable = [
        'name',
        'type'
    ];
}
