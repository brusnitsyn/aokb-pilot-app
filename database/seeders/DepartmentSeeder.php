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
            [ 'name' => 'Зейская больница', 'regionId' => 1, 'coords' => [127.261182, 53.741825] ], // 1
            [ 'name' => 'Участковая больница с. Октябрьский', 'regionId' => 1, 'coords' => [128.640689, 53.013214] ], // 2
            [ 'name' => 'Участковая больница с. Бомнак', 'regionId' => 1, 'coords' => [128.851012, 54.709918] ], // 3
            [ 'name' => 'Участковая больница с. Верхнезейский', 'regionId' => 1, 'coords' => [128.550487, 54.628949] ], // 4
            [ 'name' => 'Участковая больница с. Береговой', 'regionId' => 1, 'coords' => [127.483407, 54.366665] ], // 5
            [ 'name' => 'Амбулатория с. Дугда', 'regionId' => 1, 'coords' => [129.997028, 53.352278] ], // 6
            [ 'name' => 'ФАП с. Снежногорский', 'regionId' => 1, 'coords' => [127.969180, 54.196077] ], // 7
            [ 'name' => 'ФАП с. Хвойный', 'regionId' => 1, 'coords' => [127.835233, 54.597996] ], // 8
            [ 'name' => 'ФАП п. Горный', 'regionId' => 1, 'coords' => [128.420036, 54.650745] ], // 9
            [ 'name' => 'ФАП п. Юбилейный', 'regionId' => 1, 'coords' => [127.355541, 53.126652] ], // 10
            [ 'name' => 'ФАП п. Поляковский', 'regionId' => 1, 'coords' => [127.538825, 52.995174] ], // 11
            [ 'name' => 'ФАП п. Огорон', 'regionId' => 1, 'coords' => [129.143449, 53.961773] ], // 12
            [ 'name' => 'ФАП п. Тунгала', 'regionId' => 1, 'coords' => [129.447336, 53.548732] ], // 13

            [ 'name' => 'Февральская больница', 'regionId' => 2, 'coords' => [130.855396, 52.460617] ], // 14 (нет в базе мис)

            [ 'name' => 'Магдагачинская больница', 'regionId' => 3, 'coords' => [125.819728, 53.443220] ], // 15

            [ 'name' => 'Селемджинская больница', 'regionId' => 4, 'coords' => [132.935705, 53.067162] ], // 16

            [ 'name' => 'Сковородинская больница', 'regionId' => 5, 'coords' => [123.926825, 53.975290] ], // 17

            [ 'name' => 'Тындинская больница', 'regionId' => 6, 'coords' => [124.727327, 55.157181] ], // 18
            [ 'name' => 'Участковая больница п. Юктали', 'regionId' => 6, 'coords' => [121.649622, 56.592477] ], // 19
            [ 'name' => 'Амбулатория п. Олекма', 'regionId' => 6, 'coords' => [120.732492, 57.021457] ], // 20 (нет в базе мис)
            [ 'name' => 'Амбулатория п. Дипкун', 'regionId' => 6, 'coords' => [126.784553, 55.130379] ], // 21 (нет в базе мис)
            [ 'name' => 'ФАП с. Муртыгит', 'regionId' => 6, 'coords' => [123.865000, 54.428055] ], // 22 (нет в базе мис)
            [ 'name' => 'ФАП п. Аносовский', 'regionId' => 6, 'coords' => [123.972378, 54.624499] ], // 23 (нет в базе мис)
            [ 'name' => 'ФАП с. Усть-Нюкжа', 'regionId' => 6, 'coords' => [121.588891, 56.560240] ], // 24 (нет в базе мис)
            [ 'name' => 'ФАП с. Чильчи', 'regionId' => 6, 'coords' => [122.411284, 56.014183] ], // 25 (нет в базе мис)
            [ 'name' => 'ФАП с. Лопча', 'regionId' => 6, 'coords' => [122.772949, 55.769598] ], // 26 (нет в базе мис)
            [ 'name' => 'ФАП с. Усть-Уркима', 'regionId' => 6, 'coords' => [123.164030, 55.312956] ], // 27 (нет в базе мис)
            [ 'name' => 'ФАП с. Тутаул', 'regionId' => 6, 'coords' => [127.459440, 55.023305] ], // 28 (нет в базе мис)
            [ 'name' => 'ФАП с. Маревый', 'regionId' => 6, 'coords' => [125.900398, 55.312394] ], // 29 (нет в базе мис)

            [ 'name' => 'Вылет по координатам', 'regionId' => 7 ], // 30
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(['name' => $department['name']], $department);
        }

        $departmentsReceives = [
            [ 'name' => 'ГАУЗ АО "Амурская областная клиническая больница"', 'is_receive' => true, 'shortname' => 'ГАУЗ АО "АОКБ"' ], // 31
            [ 'name' => 'ГАУЗ АО "Амурская областная детская клиническая больница"', 'is_receive' => true, 'shortname' => 'ГАУЗ АО "АОДКБ"' ], // 32
            [ 'name' => 'ГАУЗ АО "Амурская областная инфекционная больница"', 'is_receive' => true, 'shortname' => 'ГАУЗ АО "АОИБ"' ], // 33
            [ 'name' => 'ГБУЗ АО "Амурская областная психиатрическая больница"', 'is_receive' => true, 'shortname' => 'ГАУЗ АО "АОПБ"' ], // 34
            [ 'name' => 'Кардиохирургический центр АГМА', 'is_receive' => true, 'shortname' => 'КЦ "АГМА"' ], // 35
            [ 'name' => 'ДНЦ Физиологии и патологии дыхания', 'is_receive' => true, 'shortname' => 'ДНЦ ФПД' ], // 36
        ];

        foreach ($departmentsReceives as $departmentsReceive) {
            Department::updateOrCreate(['name' => $departmentsReceive['name']], $departmentsReceive);
        }
    }
}
