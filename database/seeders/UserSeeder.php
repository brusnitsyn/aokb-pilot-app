<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use App\Models\UserDepartment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserDepartment::truncate();
        User::truncate();

        $users = [
            [ 'name' => 'Администратор', 'email' => 'asu@amurokb.ru', 'login' => 'admin', 'password' => bcrypt('asu') ],
        ];

        $departments = Department::all();
        $userDepartments = [];
        foreach ($departments as $department) {
            $userDepartments[] = [ 'user_id' => 1, 'department_id' => $department->id ];
        }

        User::insert($users);
        UserDepartment::insert($userDepartments);
    }
}
