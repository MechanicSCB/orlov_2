<?php

namespace Database\Seeders;

use App\Models\Accident;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accidents = json_decode(file_get_contents(base_path('database/seeders/data/accidents.json')), 1);

        Accident::query()->upsert($accidents, ['id']);
    }
}
