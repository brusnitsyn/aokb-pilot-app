<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\DepartmentDiagnosisGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentDiagnosisGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DepartmentDiagnosisGroup::truncate();

        $departments = Department::all();

        $departmentsDiagnosisGroups = [];
        foreach ($departments as $department) {
            $departmentsDiagnosisGroups[] = [
                'diagnosis_group_id' => 1,
                'department_id' => $department->id,
            ];
        }

        DepartmentDiagnosisGroup::insert($departmentsDiagnosisGroups);
    }
}
