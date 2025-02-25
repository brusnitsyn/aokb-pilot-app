<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RoleScope;
use App\Models\Scope;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RoleScope::truncate();
        Scope::truncate();
        Role::truncate();

        $scopes = [
            [ 'name' => 'LOGGED_IN', 'description' => 'Разрешение на вход' ], // 1

            [ 'name' => 'HAS_CHANGE_DEPARTMENT', 'description' => 'Разрешение на изменение организации' ], // 2
        ];

        $roles = [
            [ 'name' => 'Администратор', 'slug' => 'ROLE_ADMIN' ], // 1
            [ 'name' => 'Оператор', 'slug' => 'ROLE_OPERATOR' ], // 2
        ];

        $roleScopes = [
            [ 'role_id' => 1, 'scope_id' => 1 ], // 1
            [ 'role_id' => 1, 'scope_id' => 2 ], // 2

            [ 'role_id' => 2, 'scope_id' => 1 ], // 3
        ];

        Scope::insert($scopes);
        Role::insert($roles);
        RoleScope::insert($roleScopes);
    }
}
