<?php

namespace Database\Seeders;

use App\Models\Diagnosis;
use App\Models\DiagnosisGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiagnosisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DiagnosisGroup::truncate();
        Diagnosis::truncate();

        $groups = [
            [ 'name' => 'Острый коронарный синдром', 'shortname' => 'ОКС' ],
            [ 'name' => 'Хирургия', 'shortname' => 'ХИР' ]
        ];

        $diagnosis = [
            [ 'code' => 'I20.0', 'name' => 'Нестабильная стенокардия (впервые возникшая, прогрессирующая)', 'diagnosis_group_id' => 1 ],
            [ 'code' => 'I21.0', 'name' => 'Острый трансмуральный инфаркт передней стенки миокарда', 'diagnosis_group_id' => 1 ],
            [ 'code' => 'I21.1', 'name' => 'Острый трансмуральный инфаркт нижней стенки миокарда', 'diagnosis_group_id' => 1 ],
            [ 'code' => 'I21.2', 'name' => 'Острый трансмуральный инфаркт миокарда других уточненных локализаций', 'diagnosis_group_id' => 1 ],
            [ 'code' => 'I21.3', 'name' => 'Острый трансмуральный инфаркт миокарда других неуточненной локализации', 'diagnosis_group_id' => 1 ],
            [ 'code' => 'I21.4', 'name' => 'Острый субэндокардиальный инфаркт миокарда', 'diagnosis_group_id' => 1 ],
            [ 'code' => 'I22.0', 'name' => 'Повторный инфаркт передней стенки миокарда', 'diagnosis_group_id' => 1 ],
            [ 'code' => 'I22.1', 'name' => 'Повторный инфаркт нижней стенки миокарда', 'diagnosis_group_id' => 1 ],
            [ 'code' => 'I22.8', 'name' => 'Повторный инфаркт миокарда другой уточненной локализации', 'diagnosis_group_id' => 1 ],

            [ 'code' => 'R10.0', 'name' => 'Острый живот', 'diagnosis_group_id' => 2 ],
            [ 'code' => 'K35', 'name' => 'Острый аппендицит', 'diagnosis_group_id' => 2 ],
        ];

        DiagnosisGroup::insert($groups);
        Diagnosis::insert($diagnosis);
    }
}
