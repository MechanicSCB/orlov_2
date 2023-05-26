<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [];
        $data = json_decode(file_get_contents(database_path('seeders/data/posts.json')), 1);
        // $previews = array_filter(array_map(fn($v) => Str::before($v, '.') , scandir(storage_path('app/public/posts/previews'))));
        $previews = array_filter(scandir(storage_path('app/public/posts/previews')));

        foreach ($data as $postId => $post) {
            if (!$post['title'] || strlen($post['body']) > 20000) {
                continue;
            }

            $posts[] = [
                'id' => $postId,
                'title' => $post['title'],
                'slug' => Str::slug($post['title'], '-', 'ru'),
                'user_id' => $post['user_id'],
                'published_at' => $post['published_at'],
                'body' => $post['body'],
                'is_users_post' => 1,
                'preview' => in_array("$postId.jpg", $previews) ? "posts/previews/$postId.jpg" : null,
            ];
        }

        Post::query()->upsert($posts, ['id']);

        Comment::factory(30)->create();
    }
}
