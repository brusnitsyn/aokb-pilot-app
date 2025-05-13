<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel(config('reverb.name') . ".department.{departmentId}", function (User $user, int $departmentId) {
    if (is_null($user->myDepartment())) return false;

    return $user->myDepartment()->id === $departmentId;
});
