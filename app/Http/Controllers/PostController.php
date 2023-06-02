<?php

namespace App\Http\Controllers;

use App\Classes\VideoHandler;
use App\Http\Requests\PostRequest;
use App\Models\Comment;
use App\Models\Photo;
use App\Models\Post;
use App\Models\User;
use App\Models\Video;
use Database\Seeders\PostSeeder;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;


class PostController extends Controller
{
    public function home(Request $request): Response|ResponseFactory
    {
        // (new PostSeeder())->run();
        // dd(tmr(),77);

        $comments = Comment::with('user:id,name,profile_photo_path', 'post:id,title,slug')->latest()->take(5)->get();
        $posts = Post::with('user:id,name,profile_photo_path')->withCount('allComments')->latest('published_at')->take(3)->get();

        // TODO try to avoid code duplicating here
        $users = User::query()
            ->select(['id', 'name', 'profile_photo_path'])
            ->whereHas('ratings')
            ->withSum('ratings as rating', 'value')
            ->withSum('monthRatings as monthRating', 'value')
            ->withSum('weekRatings as weekRating', 'value')
            ->get();

        $weekBestUsers = $users->sortByDesc('weekRating')->where('weekRating', '>', 0)->take(5)->values();
        $monthBestUsers = $users->sortByDesc('monthRating')->where('monthRating', '>', 0)->take(5)->values();
        $bestUsers = $users->sortByDesc('rating')->take(5)->values();

        return inertia('Home', compact('comments', 'posts', 'weekBestUsers', 'monthBestUsers', 'bestUsers'));
    }

    public function index(Request $request): Response|ResponseFactory
    {
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
        $post->load(['user', 'comments', 'videos', 'photos'])
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

        return inertia('Posts/Show', [
            'post' => $post,
        ]);
    }

    public function create(): Response|ResponseFactory
    {
        return inertia('Posts/Create');
    }

    public function edit(Post $post): Response|ResponseFactory
    {
        return inertia('Posts/Edit', [
            'post' => $post,
        ]);
    }

    public function store(PostRequest $request)
    {
        $validated = $request->validated();
        $validated['published_at'] = now();
        $validated['user_id'] = auth()->id();
        $validated['is_users_post'] = true;

        $videoId = ($videoHandler = (new VideoHandler()))->getVideoIdFromUrl(@$validated['videos'][0]);
        $validated['preview'] = $videoHandler->savePreviewFromVideoId($videoId);

        $videos = [];

        foreach ($validated['videos'] ?? [] as $videoUrl) {
            if (! $videoUrl) {
                continue;
            }

            $video['url'] = $videoUrl;
            $params = $videoHandler->getVideoParamsFromUrl($videoUrl);

            if(@$params['start_time']){
                $video['start_time'] = $params['start_time'];
                unset($params['start_time']);
            }

            $video['params'] = json_encode($params);

            $videos[] = Video::query()->create($video)->id;
        }

        $photos = $validated['photos'] ?? [];
        unset($validated['videos'], $validated['photos']);

        $post = Post::query()->create($validated);

        foreach ($photos as $photo) {
            // Save uploaded photos
            $photoPath = $photo->storePublicly('post-photos', ['disk' => 'public']);

            Photo::query()->create([
                'path' => '/storage/' . $photoPath,
                'post_id' => $post->id,
                'user_id' => auth()->id(),
            ]);
        }

        $post->videos()->sync($videos);

        return redirect(route('posts.index'))->withSuccess('created!');
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|min:3',
            'body' => 'nullable|min:3',
        ]);

        if ($post['title'] !== $validated['title']) {
            $validated['slug'] = $validated['title'];
        }

        $post->update($validated);

        return redirect(route('posts.index'))->withSuccess('updated!');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect(route('posts.index'))->withSuccess('deleted!');
    }
}
