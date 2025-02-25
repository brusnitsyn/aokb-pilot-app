<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleScope extends Model
{
    protected $fillable = [
        'role_id',
        'scope_id',
    ];
}
