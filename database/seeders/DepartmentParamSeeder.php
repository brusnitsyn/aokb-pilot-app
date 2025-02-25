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
            [ 'name' => 'Уровень медицинской организации' ], // 1
            [ 'name' => 'Труднодоступная местность' ], // 2
            [ 'name' => 'Подразделение медицинской организации' ], // 3
            [ 'name' => 'Наличие круглосуточных коек' ], // 4
        ];

        $paramValues = [
            // Уровень МО (все группы)
            [ 'param_id' => 1, 'value_name' => '0', 'score' => 3, 'depends_diagnosis_group_ids' => "[1,2]" ], // 1
            [ 'param_id' => 1, 'value_name' => '1', 'score' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ], // 2
            [ 'param_id' => 1, 'value_name' => '2', 'score' => 1, 'depends_diagnosis_group_ids' => "[1,2]" ], // 3
            [ 'param_id' => 1, 'value_name' => '3', 'score' => 0, 'depends_diagnosis_group_ids' => "[1,2]" ], // 4
            // Труднодоступные местность (все группы)
            [ 'param_id' => 2, 'value_name' => 'Да', 'score' => 1, 'depends_diagnosis_group_ids' => "[1,2]" ], // 5
            [ 'param_id' => 2, 'value_name' => 'Нет', 'score' => 0, 'depends_diagnosis_group_ids' => "[1,2]" ], // 6
            // Подразделение МО (ОКС группа)
            [ 'param_id' => 3, 'value_name' => 'ФАП или координаты', 'score' => 2, 'depends_diagnosis_group_ids' => "[1]" ], // 7
            [ 'param_id' => 3, 'value_name' => 'Амбулатория', 'score' => 1, 'depends_diagnosis_group_ids' => "[1]" ], // 8
            [ 'param_id' => 3, 'value_name' => 'Участковая больница', 'score' => 1, 'depends_diagnosis_group_ids' => "[1,2]" ], // 9
            [ 'param_id' => 3, 'value_name' => 'Районная/городская больница', 'score' => 0, 'depends_diagnosis_group_ids' => "[1,2]" ], // 10
            // Наличие круглосуточных коек (все группы)
            [ 'param_id' => 4, 'value_name' => 'Да', 'score' => 0, 'depends_diagnosis_group_ids' => "[1,2]" ], // 11
            [ 'param_id' => 4, 'value_name' => 'Нет', 'score' => 1, 'depends_diagnosis_group_ids' => "[1,2]" ], // 12

            // Подразделение МО (хирургическая группа)
            [ 'param_id' => 3, 'value_name' => 'ФАП или координаты', 'score' => 3, 'depends_diagnosis_group_ids' => "[2]" ], // 13
            [ 'param_id' => 3, 'value_name' => 'Амбулатория', 'score' => 2, 'depends_diagnosis_group_ids' => "[2]" ], // 14
        ];

        Param::insert($params);
        ParamValue::insert($paramValues);

        // Группа ОКС
        $departmentParams = [
            // Зейская больница
            [ 'department_id' => 1, 'param_id' => 1, 'param_value_id' => 3, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 1, 'param_id' => 2, 'param_value_id' => 6, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 1, 'param_id' => 3, 'param_value_id' => 10, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 1, 'param_id' => 4, 'param_value_id' => 11, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // Участковая больница с. Октябрьский
            [ 'department_id' => 2, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 2, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 2, 'param_id' => 3, 'param_value_id' => 9, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 2, 'param_id' => 4, 'param_value_id' => 11, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // Участковая больница с. Бомнак
            [ 'department_id' => 3, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 3, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 3, 'param_id' => 3, 'param_value_id' => 9, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 3, 'param_id' => 4, 'param_value_id' => 11, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // Участковая больница с. Верхнезейский
            [ 'department_id' => 4, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 4, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 4, 'param_id' => 3, 'param_value_id' => 9, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 4, 'param_id' => 4, 'param_value_id' => 11, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // Участковая больница с. Береговой
            [ 'department_id' => 5, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 5, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 5, 'param_id' => 3, 'param_value_id' => 9, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 5, 'param_id' => 4, 'param_value_id' => 11, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // Амбулатория с. Дугда
            [ 'department_id' => 6, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 6, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 6, 'param_id' => 3, 'param_value_id' => 8, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 6, 'param_id' => 4, 'param_value_id' => 10, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // ФАП с. Снежногорский
            [ 'department_id' => 7, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 7, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 7, 'param_id' => 3, 'param_value_id' => 7, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 7, 'param_id' => 4, 'param_value_id' => 10, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // ФАП с. Хвойный
            [ 'department_id' => 8, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 8, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 8, 'param_id' => 3, 'param_value_id' => 7, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 8, 'param_id' => 4, 'param_value_id' => 10, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // ФАП п. Горный
            [ 'department_id' => 9, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 9, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 9, 'param_id' => 3, 'param_value_id' => 7, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 9, 'param_id' => 4, 'param_value_id' => 10, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // ФАП п. Юбилейный
            [ 'department_id' => 10, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 10, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 10, 'param_id' => 3, 'param_value_id' => 7, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 10, 'param_id' => 4, 'param_value_id' => 10, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // ФАП п. Поляковский
            [ 'department_id' => 11, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 11, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 11, 'param_id' => 3, 'param_value_id' => 7, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 11, 'param_id' => 4, 'param_value_id' => 10, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // ФАП п. Огорон
            [ 'department_id' => 12, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 12, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 12, 'param_id' => 3, 'param_value_id' => 7, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 12, 'param_id' => 4, 'param_value_id' => 10, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // ФАП п. Тунгала
            [ 'department_id' => 13, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 13, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 13, 'param_id' => 3, 'param_value_id' => 7, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 13, 'param_id' => 4, 'param_value_id' => 10, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // Февральская больница
            [ 'department_id' => 14, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 14, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 14, 'param_id' => 3, 'param_value_id' => 10, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 14, 'param_id' => 4, 'param_value_id' => 11, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // Магдагачинская больница
            [ 'department_id' => 15, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 15, 'param_id' => 2, 'param_value_id' => 6, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 15, 'param_id' => 3, 'param_value_id' => 10, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 15, 'param_id' => 4, 'param_value_id' => 11, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // Селемджинская больница
            [ 'department_id' => 16, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 16, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 16, 'param_id' => 3, 'param_value_id' => 10, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 16, 'param_id' => 4, 'param_value_id' => 11, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // Сковородинская больница
            [ 'department_id' => 17, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 17, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 17, 'param_id' => 3, 'param_value_id' => 10, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 17, 'param_id' => 4, 'param_value_id' => 11, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // Тындинская больница
            [ 'department_id' => 18, 'param_id' => 1, 'param_value_id' => 3, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 18, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 18, 'param_id' => 3, 'param_value_id' => 10, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 18, 'param_id' => 4, 'param_value_id' => 11, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // Участковая больница пос. Юктали
            [ 'department_id' => 19, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 19, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 19, 'param_id' => 3, 'param_value_id' => 9, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 19, 'param_id' => 4, 'param_value_id' => 11, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // Амбулатория п. Олекма
            [ 'department_id' => 20, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 20, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 20, 'param_id' => 3, 'param_value_id' => 8, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 20, 'param_id' => 4, 'param_value_id' => 12, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // Амбулатория п. Дипкун
            [ 'department_id' => 21, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 21, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 21, 'param_id' => 3, 'param_value_id' => 8, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 21, 'param_id' => 4, 'param_value_id' => 12, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // ФАП с. Муртыгит
            [ 'department_id' => 22, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 22, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 22, 'param_id' => 3, 'param_value_id' => 7, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 22, 'param_id' => 4, 'param_value_id' => 12, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // ФАП п. Аносовский
            [ 'department_id' => 23, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 23, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 23, 'param_id' => 3, 'param_value_id' => 7, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 23, 'param_id' => 4, 'param_value_id' => 12, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // ФАП с. Усть-Нюкжа
            [ 'department_id' => 24, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 24, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 24, 'param_id' => 3, 'param_value_id' => 7, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 24, 'param_id' => 4, 'param_value_id' => 12, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // ФАП с. Чильчи
            [ 'department_id' => 25, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 25, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 25, 'param_id' => 3, 'param_value_id' => 7, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 25, 'param_id' => 4, 'param_value_id' => 12, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // ФАП с. Лопча
            [ 'department_id' => 26, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 26, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 26, 'param_id' => 3, 'param_value_id' => 7, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 26, 'param_id' => 4, 'param_value_id' => 12, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // ФАП с. Усть-Уркима
            [ 'department_id' => 27, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 27, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 27, 'param_id' => 3, 'param_value_id' => 7, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 27, 'param_id' => 4, 'param_value_id' => 12, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // ФАП с. Тутаул
            [ 'department_id' => 28, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 28, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 28, 'param_id' => 3, 'param_value_id' => 7, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 28, 'param_id' => 4, 'param_value_id' => 12, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // ФАП с. Маревый
            [ 'department_id' => 29, 'param_id' => 1, 'param_value_id' => 2, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 29, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 29, 'param_id' => 3, 'param_value_id' => 7, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 29, 'param_id' => 4, 'param_value_id' => 12, 'depends_diagnosis_group_ids' => "[1,2]" ],
            // Вылет по координатам
            [ 'department_id' => 30, 'param_id' => 1, 'param_value_id' => 1, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 30, 'param_id' => 2, 'param_value_id' => 5, 'depends_diagnosis_group_ids' => "[1,2]" ],
            [ 'department_id' => 30, 'param_id' => 3, 'param_value_id' => 7, 'depends_diagnosis_group_ids' => "[1]" ],
            [ 'department_id' => 30, 'param_id' => 4, 'param_value_id' => 12, 'depends_diagnosis_group_ids' => "[1,2]" ],
        ];
        DepartmentParam::insert($departmentParams);

        // Группа Хирургия
        $departmentParams = [
            // Амбулатория с. Дугда
            [ 'department_id' => 6, 'param_id' => 3, 'param_value_id' => 14, 'depends_diagnosis_group_ids' => "[2]" ],
            // ФАП с. Снежногорский
            [ 'department_id' => 7, 'param_id' => 3, 'param_value_id' => 13, 'depends_diagnosis_group_ids' => "[2]" ],
            // ФАП с. Хвойный
            [ 'department_id' => 8, 'param_id' => 3, 'param_value_id' => 13, 'depends_diagnosis_group_ids' => "[2]" ],
            // ФАП п. Горный
            [ 'department_id' => 9, 'param_id' => 3, 'param_value_id' => 13, 'depends_diagnosis_group_ids' => "[2]" ],
            // ФАП п. Юбилейный
            [ 'department_id' => 10, 'param_id' => 3, 'param_value_id' => 13, 'depends_diagnosis_group_ids' => "[2]" ],
            // ФАП п. Поляковский
            [ 'department_id' => 11, 'param_id' => 3, 'param_value_id' => 13, 'depends_diagnosis_group_ids' => "[2]" ],
            // ФАП п. Огорон
            [ 'department_id' => 12, 'param_id' => 3, 'param_value_id' => 13, 'depends_diagnosis_group_ids' => "[2]" ],
            // ФАП п. Тунгала
            [ 'department_id' => 13, 'param_id' => 3, 'param_value_id' => 13, 'depends_diagnosis_group_ids' => "[2]" ],
            // Амбулатория п. Олекма
            [ 'department_id' => 20, 'param_id' => 3, 'param_value_id' => 14, 'depends_diagnosis_group_ids' => "[2]" ],
            // Амбулатория п. Дипкун
            [ 'department_id' => 21, 'param_id' => 3, 'param_value_id' => 14, 'depends_diagnosis_group_ids' => "[2]" ],
            // ФАП с. Муртыгит
            [ 'department_id' => 22, 'param_id' => 3, 'param_value_id' => 13, 'depends_diagnosis_group_ids' => "[2]" ],
            // ФАП п. Аносовский
            [ 'department_id' => 23, 'param_id' => 3, 'param_value_id' => 13, 'depends_diagnosis_group_ids' => "[2]" ],
            // ФАП с. Усть-Нюкжа
            [ 'department_id' => 24, 'param_id' => 3, 'param_value_id' => 13, 'depends_diagnosis_group_ids' => "[2]" ],
            // ФАП с. Чильчи
            [ 'department_id' => 25, 'param_id' => 3, 'param_value_id' => 13, 'depends_diagnosis_group_ids' => "[2]" ],
            // ФАП с. Лопча
            [ 'department_id' => 26, 'param_id' => 3, 'param_value_id' => 13, 'depends_diagnosis_group_ids' => "[2]" ],
            // ФАП с. Усть-Уркима
            [ 'department_id' => 27, 'param_id' => 3, 'param_value_id' => 13, 'depends_diagnosis_group_ids' => "[2]" ],
            // ФАП с. Тутаул
            [ 'department_id' => 28, 'param_id' => 3, 'param_value_id' => 13, 'depends_diagnosis_group_ids' => "[2]" ],
            // ФАП с. Маревый
            [ 'department_id' => 29, 'param_id' => 3, 'param_value_id' => 13, 'depends_diagnosis_group_ids' => "[2]" ],
            // Вылет по координатам
            [ 'department_id' => 30, 'param_id' => 3, 'param_value_id' => 13, 'depends_diagnosis_group_ids' => "[2]" ],
        ];
        DepartmentParam::insert($departmentParams);
    }
}
