<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::truncate();

        $departments = [
            [ 'name' => 'Зейская больница', 'regionId' => 1 ], // 1
            [ 'name' => 'Участковая больница с. Октябрьский', 'regionId' => 1 ], // 2
            [ 'name' => 'Участковая больница с. Бомнак', 'regionId' => 1 ], // 3
            [ 'name' => 'Участковая больница с. Верхнезейский', 'regionId' => 1 ], // 4
            [ 'name' => 'Участковая больница с. Береговой', 'regionId' => 1 ], // 5
            [ 'name' => 'Амбулатория с. Дугда', 'regionId' => 1 ], // 6
            [ 'name' => 'ФАП с. Снежногорский', 'regionId' => 1 ], // 7
            [ 'name' => 'ФАП с. Хвойный', 'regionId' => 1 ], // 8
            [ 'name' => 'ФАП п. Горный', 'regionId' => 1 ], // 9
            [ 'name' => 'ФАП п. Юбилейный', 'regionId' => 1 ], // 10
            [ 'name' => 'ФАП п. Поляковский', 'regionId' => 1 ], // 11
            [ 'name' => 'ФАП п. Огорон', 'regionId' => 1 ], // 12
            [ 'name' => 'ФАП п. Тунгала', 'regionId' => 1 ], // 13

            [ 'name' => 'Февральская больница', 'regionId' => 2 ], // 14

            [ 'name' => 'Магдагачинская больница', 'regionId' => 3 ], // 15

            [ 'name' => 'Селемджинская больница', 'regionId' => 4 ], // 16

            [ 'name' => 'Сковородинская больница', 'regionId' => 5 ], // 17

            [ 'name' => 'Тындинская больница', 'regionId' => 6 ], // 18
            [ 'name' => 'Участковая больница п. Юктали', 'regionId' => 6 ], // 19
            [ 'name' => 'Амбулатория п. Олекма', 'regionId' => 6 ], // 20
            [ 'name' => 'Амбулатория п. Дипкун', 'regionId' => 6 ], // 21
            [ 'name' => 'ФАП с. Муртыгит', 'regionId' => 6 ], // 22
            [ 'name' => 'ФАП п. Аносовский', 'regionId' => 6 ], // 23
            [ 'name' => 'ФАП с. Усть-Нюкжа', 'regionId' => 6 ], // 24
            [ 'name' => 'ФАП с. Чильчи', 'regionId' => 6 ], // 25
            [ 'name' => 'ФАП с. Лопча', 'regionId' => 6 ], // 26
            [ 'name' => 'ФАП с. Усть-Уркима', 'regionId' => 6 ], // 27
            [ 'name' => 'ФАП с. Тутаул', 'regionId' => 6 ], // 28
            [ 'name' => 'ФАП с. Маревый', 'regionId' => 6 ], // 29

            [ 'name' => 'Вылет по координатам', 'regionId' => 7 ], // 30
        ];

        Department::insert($departments);

        $departmentsReceive = [
            [ 'name' => 'ГАУЗ АО "Амурская областная клиническая больница"', 'is_receive' => true, 'shortname' => 'ГАУЗ АО "АОКБ"' ],
            [ 'name' => 'ГАУЗ АО "Амурская областная детская клиническая больница"', 'is_receive' => true, 'shortname' => 'ГАУЗ АО "АОДКБ"' ],
            [ 'name' => 'ГАУЗ АО "Амурская областная инфекционная больница"', 'is_receive' => true, 'shortname' => 'ГАУЗ АО "АОИБ"' ],
            [ 'name' => 'ГБУЗ АО "Амурская областная психиатрическая больница"', 'is_receive' => true, 'shortname' => 'ГАУЗ АО "АОПБ"' ],
            [ 'name' => 'Кардиохирургический центр АГМА', 'is_receive' => true, 'shortname' => 'КЦ "АГМА"' ],
            [ 'name' => 'ДНЦ Физиологии и патологии дыхания', 'is_receive' => true, 'shortname' => 'ДНЦ ФПД' ],
        ];

        Department::insert($departmentsReceive);
    }
}
