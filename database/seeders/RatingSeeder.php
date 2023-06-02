<?php

namespace Database\Seeders;

use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::query()->get();

        Rating::query()->create([
            'value' => Arr::random([100]),
            'user_id' => 1,
            'created_at' => now()->subMonths(rand(0,3))->subDays(rand(0,30)),
            'updated_at' => now()->subMonths(rand(0,3))->subDays(rand(0,30)),
        ]);

        foreach ($users as $user){
            if(rand(0,3) === 0){
                for($i=0; $i<=rand(0,5); $i++){
                    Rating::query()->create([
                        'value' => Arr::random([10,10,10,10,10,30,30,100]),
                        'user_id' => $user->id,
                        'created_at' => now()->subMonths(rand(0,3))->subDays(rand(0,30)),
                        'updated_at' => now()->subMonths(rand(0,3))->subDays(rand(0,30)),
                    ]);
                }
            }
        }
    }
}
