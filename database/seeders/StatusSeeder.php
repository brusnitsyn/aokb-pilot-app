<?php

namespace Database\Seeders;

use App\Models\PatientResultStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PatientResultStatus::truncate();

        $statuses = [
            [ 'name' => 'Создан', 'type' => null ],
            [ 'name' => 'Отправлен', 'type' => 'success' ],
            [ 'name' => 'Закрыт', 'type' => 'success' ],
        ];

        PatientResultStatus::insert($statuses);
    }
}
