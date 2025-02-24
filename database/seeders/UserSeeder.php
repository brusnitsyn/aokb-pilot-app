<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use App\Models\UserDepartment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            [ 'name' => 'Администратор', 'email' => 'asu@amurokb.ru', 'login' => 'admin', 'password' => Hash::make('asu') ],
            [ 'name' => 'Каргополов А.И.', 'email' => 'kargopolov@amurokb.ru', 'login' => 'демоврач', 'password' => Hash::make('asu') ],
        ];

        $departments = Department::all();
        $userDepartments = [];
        foreach ($departments as $department) {
            $userDepartments[] = [ 'user_id' => 1, 'department_id' => $department->id ];
        }

        $userDepartments[] = [ 'user_id' => 2, 'department_id' => 1 ];

        foreach ($users as $user) {
            User::create($user);
        }
        UserDepartment::insert($userDepartments);
    }
}
