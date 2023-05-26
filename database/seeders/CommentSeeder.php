<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = json_decode(file_get_contents(storage_path('app/dev/comments.json')), 1);
        $postsIds = Post::query()->pluck('id')->toArray();
        $comments = [];

        foreach ($data as $postId => $postComments){
            if(! in_array($postId, $postsIds)){
                continue;
            }

            foreach ($postComments as $item){
                $comments[] = [
                    'id' => $item['id'],
                    'body' => $item['body'],
                    'published_at' => $item['datetime'],
                    'post_id' => $postId,
                    'user_id' => $item['user_id'],
                    'parent_id' => null,
                ];
            }
        }

        Comment::query()->upsert($comments, ['id']);
    }
}
