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
            [ 'name' => 'А. Прогрессирующая стенокардия', 'score' => 0 ],
            [ 'name' => 'Б. ИМ без подъема с ST', 'score' => 2 ],
            [ 'name' => 'В. ИМ с подъемом ST давностью >48', 'score' => 2 ],
            [ 'name' => 'Г. ИМ с подъемом ST давностью >12<48 ч.', 'score' => 2 ],
            [ 'name' => 'Д. ИМ с подъемом ST после ТЛТ', 'score' => 2 ],
            [ 'name' => 'Е. ИМ с подъемом ST давностью до 12 ч. без ТЛТ', 'score' => 2 ],
        ];

        Scenario::insert($scenarios);
    }
}
