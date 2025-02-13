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
        ];

        Scenario::insert($scenarios);
    }
}
