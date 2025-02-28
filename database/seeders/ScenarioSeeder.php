<?php

namespace Database\Seeders;

use App\Models\Scenario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScenarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Scenario::truncate();

        $scenarios = [
            [ 'name' => 'Прогрессирующая стенокардия', 'score' => 1.0, 'color' => 'red', 'code' => '1' ],
            [ 'name' => 'ИМ без подъёма сегмента ST', 'score' => 3.0, 'color' => 'orange', 'code' => '2' ],

            [ 'name' => 'ИМ с подъёмом ST давностью до 12 ч. без ТЛТ', 'score' => 6.0, 'color' => 'yellow', 'code' => '3' ],
            [ 'name' => 'ИМ с подъёмом ST давностью до 12 ч. после ТЛТ', 'score' => 4.0, 'color' => 'green', 'code' => '4' ],

            [ 'name' => 'ИМ с подъёмом ST давностью >12<48 ч.', 'score' => 5.0, 'color' => 'sky', 'code' => '5' ],
            [ 'name' => 'ИМ с подъёмом ST давностью >48 ч.', 'score' => 2.0, 'color' => 'indigo', 'code' => '6' ],
        ];

        Scenario::insert($scenarios);
    }
}
