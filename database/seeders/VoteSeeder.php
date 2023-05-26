<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $votes = [];

        for($i=0;$i<300;$i++){
            if(rand(0,5)===0){
                $votableType = 'Post';
                $votableId = Post::query()->inRandomOrder()->first()->id;
            }else{
                $votableType = 'Comment';
                $votableId = Comment::query()->inRandomOrder()->first()->id;
            }

            $votes[] = [
                'value' => [-1,1][rand(0,1)],
                'user_id' => User::query()->inRandomOrder()->first()->id,
                'votable_id' => $votableId,
                'votable_type' => "App\Models\\$votableType",
            ];
        }

        Vote::query()->upsert($votes, ['user_id','votable_id','votable_type']);
    }
}
