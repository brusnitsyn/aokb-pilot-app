<?php

namespace Database\Seeders;

use App\Models\Diagnosis;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiagnosisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Diagnosis::truncate();

        $diagnosis = [
            [ 'code' => null, 'name' => 'Диагноз не определен' ],
            [ 'code' => 'I20.0', 'name' => 'Нестабильная стенокардия (впервые возникшая, прогрессирующая)' ],
            [ 'code' => 'I21.0', 'name' => 'Острый трансмуральный инфаркт передней стенки миокарда' ],
            [ 'code' => 'I21.1', 'name' => 'Острый трансмуральный инфаркт нижней стенки миокарда' ],
            [ 'code' => 'I21.2', 'name' => 'Острый трансмуральный инфаркт миокарда других уточненных локализаций' ],
            [ 'code' => 'I21.3', 'name' => 'Острый трансмуральный инфаркт миокарда других неуточненной локализации' ],
            [ 'code' => 'I21.4', 'name' => 'Острый субэндокардиальный инфаркт миокарда' ],
            [ 'code' => 'I22.0', 'name' => 'Повторный инфаркт передней стенки миокарда' ],
            [ 'code' => 'I22.1', 'name' => 'Повторный инфаркт нижней стенки миокарда' ],
            [ 'code' => 'I22.8', 'name' => 'Повторный инфаркт миокарда другой уточненной локализации' ],
        ];

        Diagnosis::insert($diagnosis);
    }
}
