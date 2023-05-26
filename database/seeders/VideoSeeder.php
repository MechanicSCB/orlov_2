<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $videos = json_decode(file_get_contents(base_path('database/seeders/data/videos.json')), 1);

        foreach (array_chunk($videos, 1000) as $chunk){
            Video::query()->upsert($chunk, ['id']);
        }
    }
}
