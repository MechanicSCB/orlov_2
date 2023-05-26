<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = json_decode(file_get_contents(database_path('seeders/data/posts.json')), 1);

        $videos = [];
        $postsVideos = [];

        $id = 5539;
        foreach ($posts as $postId => $post){
            if(! $url = $post['video']){
                continue;
            }

            $videos[] = ['id' => $id,'url' => $url];

            if (in_array($postId, Post::query()->pluck('id')->toArray())){
                $postsVideos[] = ['post_id' => $postId,'video_id' => $id];
            }

            $id++;
        }

        Video::query()->upsert($videos, ['id']);
        DB::table('post_video')->upsert($postsVideos,['post_id','video_id']);
    }
}
