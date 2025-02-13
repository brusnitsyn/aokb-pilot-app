<?php

namespace Database\Seeders;

use App\Models\DepartmentParam;
use App\Models\Param;
use App\Models\ParamValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentParamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DepartmentParam::truncate();
        ParamValue::truncate();
        Param::truncate();

        $params = [
            [ 'name' => 'Уровень МО' ], // 1
            [ 'name' => 'Труднодоступная местность' ], // 2
            [ 'name' => 'Подразделение МО' ], // 3
            [ 'name' => 'Наличие круглосуточных коек' ], // 4
        ];

        $paramValues = [
            // Уровень МО
            [ 'param_id' => 1, 'value_name' => '0', 'score' => 3 ], // 1
            [ 'param_id' => 1, 'value_name' => '1', 'score' => 2 ], // 2
            [ 'param_id' => 1, 'value_name' => '2', 'score' => 1 ], // 3
            [ 'param_id' => 1, 'value_name' => '3', 'score' => 0 ], // 4
            // Труднодоступная местность
            [ 'param_id' => 2, 'value_name' => 'Да', 'score' => 1 ], // 5
            [ 'param_id' => 2, 'value_name' => 'Нет', 'score' => 0 ], // 6
            // Подразделение МО
            [ 'param_id' => 3, 'value_name' => 'ФАП или координаты', 'score' => 1 ], // 7
            [ 'param_id' => 3, 'value_name' => 'Амбулатория', 'score' => 1 ], // 8
            [ 'param_id' => 3, 'value_name' => 'Участковая больница', 'score' => 0.5 ], // 9
            [ 'param_id' => 3, 'value_name' => 'Районная/городская больница', 'score' => 0 ], // 10
            // Наличие круглосуточных коек
            [ 'param_id' => 4, 'value_name' => 'Да', 'score' => 0 ], // 11
            [ 'param_id' => 4, 'value_name' => 'Нет', 'score' => 1 ], // 12
        ];

        $departmentParams = [
            [ 'department_id' => 1, 'param_id' => 1, 'param_value_id' => 3 ],
            [ 'department_id' => 1, 'param_id' => 2, 'param_value_id' => 5 ],
            [ 'department_id' => 1, 'param_id' => 3, 'param_value_id' => 10 ],
            [ 'department_id' => 1, 'param_id' => 4, 'param_value_id' => 11 ],
        ];

        Param::insert($params);
        ParamValue::insert($paramValues);
        DepartmentParam::insert($departmentParams);
    }
}
