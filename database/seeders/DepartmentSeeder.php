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
            [ 'name' => 'Зейская больница', 'regionId' => 1 ],
            [ 'name' => 'Участковая больница с. Октябрьский', 'regionId' => 1 ],
            [ 'name' => 'Участковая больница с. Бомнак', 'regionId' => 1 ],
            [ 'name' => 'Участковая больница с. Верхнезейский', 'regionId' => 1 ],
            [ 'name' => 'Участковая больница с. Береговой', 'regionId' => 1 ],
            [ 'name' => 'Амбулатория с. Дугда', 'regionId' => 1 ],
            [ 'name' => 'ФАП с. Снежногорский', 'regionId' => 1 ],
            [ 'name' => 'ФАП с. Хвойный', 'regionId' => 1 ],
            [ 'name' => 'ФАП п. Горный', 'regionId' => 1 ],
            [ 'name' => 'ФАП п. Юбилейный', 'regionId' => 1 ],
            [ 'name' => 'ФАП п. Поляковский', 'regionId' => 1 ],
            [ 'name' => 'ФАП п. Огорон', 'regionId' => 1 ],
            [ 'name' => 'ФАП п. Тунгала', 'regionId' => 1 ],

            [ 'name' => 'Февральская больница', 'regionId' => 2 ],

            [ 'name' => 'Магдагачинская больница', 'regionId' => 3 ],

            [ 'name' => 'Селемджинская больница', 'regionId' => 4 ],

            [ 'name' => 'Сковородинская больница', 'regionId' => 5 ],

            [ 'name' => 'Тындинская больница', 'regionId' => 6 ],
            [ 'name' => 'Участковая больница п. Юктали', 'regionId' => 6 ],
            [ 'name' => 'Амбулатория п. Олекма', 'regionId' => 6 ],
            [ 'name' => 'Амбулатория п. Дипкун', 'regionId' => 6 ],
            [ 'name' => 'ФАП с. Муртыгит', 'regionId' => 6 ],
            [ 'name' => 'ФАП п. Аносовский', 'regionId' => 6 ],
            [ 'name' => 'ФАП с. Усть-Нюкжа', 'regionId' => 6 ],
            [ 'name' => 'ФАП с. Чильчи', 'regionId' => 6 ],
            [ 'name' => 'ФАП с. Лопча', 'regionId' => 6 ],
            [ 'name' => 'ФАП с. Усть-Уркима', 'regionId' => 6 ],
            [ 'name' => 'ФАП с. Тутаул', 'regionId' => 6 ],
            [ 'name' => 'ФАП с. Маревый', 'regionId' => 6 ],

            [ 'name' => 'Вылет по координатам', 'regionId' => 7 ],
            [ 'name' => 'Вылет по координатам (нет медработника)', 'regionId' => 7 ],
        ];

        Department::insert($departments);
    }
}
