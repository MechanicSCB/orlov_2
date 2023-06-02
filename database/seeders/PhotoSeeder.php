<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = json_decode(file_get_contents(database_path('seeders/data/posts.json')), 1);
        dd(tmr(),$posts);


    }
}
