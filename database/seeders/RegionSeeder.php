<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Region::truncate();

        $regions = [
            [ 'name' => 'Зейский район', 'shortName' => 'Зейский', 'position' => 1 ],
            [ 'name' => 'Февральский район', 'shortName' => 'Февральский', 'position' => 2 ],
            [ 'name' => 'Магдагачинский район', 'shortName' => 'Магдагачинский', 'position' => 3 ],
            [ 'name' => 'Селемджинский район', 'shortName' => 'Селемджинский', 'position' => 4 ],
            [ 'name' => 'Сковородинский район', 'shortName' => 'Сковородинский', 'position' => 5 ],
            [ 'name' => 'Тындинский район', 'shortName' => 'Тындинский', 'position' => 6 ],
            [ 'name' => 'Другое', 'shortName' => 'Другое', 'position' => 7 ],
        ];

        Region::insert($regions);
    }
}
