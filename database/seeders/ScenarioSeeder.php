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
            [ 'name' => '1. Прогрессирующая стенокардия', 'score' => 1.0, 'color' => 'red' ],
            [ 'name' => '2. ИМ без подъёма сегмента ST', 'score' => 3.0, 'color' => 'orange' ],

            [ 'name' => '3. ИМ с подъёмом ST давностью до 12 ч. без ТЛТ', 'score' => 6.0, 'color' => 'yellow' ],
            [ 'name' => '4. ИМ с подъёмом ST давностью до 12 ч. после ТЛТ', 'score' => 4.0, 'color' => 'green' ],

            [ 'name' => '5. ИМ с подъёмом ST давностью >12<48 ч.', 'score' => 5.0, 'color' => 'sky' ],
            [ 'name' => '6. ИМ с подъёмом ST давностью >48 ч.', 'score' => 2.0, 'color' => 'indigo' ],
        ];

        Scenario::insert($scenarios);
    }
}
