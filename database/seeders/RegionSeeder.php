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
        $regions = json_decode(file_get_contents(base_path('database/seeders/data/regions.json')), 1);

        Region::query()->upsert($regions, ['id']);
    }
}
