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
            [ 'name' => '1. Прогрессирующая стенокардия', 'score' => 1.0 ],
            [ 'name' => '2. ИМ без подъёма сегмента ST', 'score' => 3.0 ],

            [ 'name' => '3. ИМ с подъёмом ST давностью до 12 ч. без ТЛТ', 'score' => 6.0 ],
            [ 'name' => '4. ИМ с подъёмом ST давностью до 12 ч. после ТЛТ', 'score' => 4.0 ],

            [ 'name' => '5. ИМ с подъёмом ST давностью >12<48 ч.', 'score' => 5.0 ],
            [ 'name' => '6. ИМ с подъёмом ST давностью >48 ч.', 'score' => 2.0 ],


        ];

        Scenario::insert($scenarios);
    }
}
