<?php

namespace App\Http\Controllers;

use App\Classes\VideoHandler;
use App\Models\Post;
use Database\Seeders\PostSeeder;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;


class PostController extends Controller
{
    public function home(Request $request): Response|ResponseFactory
    {
        //(new PostSeeder())->run();

        $posts = Post::query()
            ->withCount('allComments')
            ->where('is_users_post', 1)
            ->with('user')
            ->latest('published_at')
            ->paginate(12)
        ;

        return inertia('Posts/Index', [
            'posts' => $posts,
        ]);
    }

    public function index(Request $request): Response|ResponseFactory
    {
        //(new PostSeeder())->run();

        $posts = Post::query()
            ->withCount('allComments')
            ->where('is_users_post', 1)
            ->with('user')
            ->latest('published_at')
            ->paginate(12)
        ;

        return inertia('Posts/Index', [
            'posts' => $posts,
        ]);
    }

    public function show(Post $post): Response|ResponseFactory
    {
        // $post->load(['user', 'comments', 'photos'])
        $post->load(['user', 'comments', 'videos'])
            ->loadCount('allComments')
            ->loadSum('votes', 'value')
        ;

        foreach ($post['comments'] as $comment) {
            $comment->loadVotesRecursively();
        }

        foreach ($post['videos'] as $video) {
            $video['params'] ??= (new VideoHandler())->getVideoParamsFromUrl($video['url']);
            $video['embedUrl'] = (new VideoHandler())->getVideoEmbedUrl($video['params']);
        }

        //dd(tmr(),$post);
        return inertia('Posts/Show', [
            'post' => $post,
        ]);
    }
}
