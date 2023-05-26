<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RegionSeeder::class,
            AccidentSeeder::class,
            VideoSeeder::class,
            PostSeeder::class,
            PostVideoSeeder::class,
            CommentSeeder::class,
            VoteSeeder::class,
        ]);
    }
}
