<?php

namespace App\Dev;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Scraper
{
    public function scrapUsers()
    {
        //dd('scrapUsers');
        $posts = json_decode(Storage::get('dev/posts.json'), 1);
        $usersIds = array_filter(array_column($posts, 'user_id'));

        $comments = json_decode(Storage::get('dev/comments.json'), 1);
        $comments = Arr::flatten($comments, 1);
        $usersIds = [...$usersIds, ...array_filter(array_column($comments, 'user_id'))];
        $usersIds = array_unique($usersIds);

        $existed = array_filter(array_map(fn($v) => Str::before($v, '.') ,scandir(storage_path('app/dev/users_htmls'))));
        $usersIds = array_diff($usersIds,$existed);

        $htmls = [];

        $responses = Http::pool(function (Pool $pool) use($usersIds){
            foreach ($usersIds as $userId) {
                $pool->as($userId)->timeout(60)->get("https://orlov-dtp.ru/users/$userId");
            }
        });

        $failed = [];

        foreach ($responses as $userId => $response) {
            if (! is_a($response, 'Illuminate\Http\Client\Response')) {
                $failed[] = $response;
                continue;
            }

            Storage::put("dev/users_htmls/$userId.html", $response->body());
        }

        dd(tmr(),$failed);
    }

    public function getPosts()
    {
        dd('getPosts');

        $responses = Http::pool(function (Pool $pool) {
            foreach (range(296, 333) as $postId) {
                $pool->as($postId)->timeout(60)->get("https://orlov-dtp.ru/posts/$postId");
            }
        });

        $failed = [];

        foreach ($responses as $postId => $response) {
            if (!is_a($response, 'Illuminate\Http\Client\Response')) {
                $failed[] = $response;
                continue;
            }

            Storage::put("dev/post_htmls/$postId.html", $response->body());
        }

        dd(tmr(), $failed);
    }

    public function getComments()
    {
        dd(tmr(),'getComments');

        $responses = Http::pool(function (Pool $pool){
            foreach (range(312,333) as $postId) {
                $pool->as($postId)->timeout(60)->get("https://orlov-dtp.ru/posts/$postId/comments");
            }
        });

        $failed = [];

        foreach ($responses as $postId => $response) {
            if (! is_a($response, 'Illuminate\Http\Client\Response')) {
                $failed[] = $response;
                continue;
            }

            if(str_contains($response->body(), 'Пока нет комментриев')){
                continue;
            }

            Storage::put("dev/сomments_htmls/$postId.html", $response->body());
        }

        dd(tmr(),$failed);
    }
    public function getPreviews()
    {
        // dd(tmr(),'getPreviews');
        //$res = Http::get("https://orlov-dtp.ru/posts?page=9")->body();
        //dd(tmr(),$res);

        $responses = Http::pool(function (Pool $pool){
            foreach (range(1,11) as $page) {
                $pool->as($page)->timeout(60)->get("https://orlov-dtp.ru/posts?page=$page");
            }
        });

        $failed = [];

        foreach ($responses as $page => $response) {
            if (! is_a($response, 'Illuminate\Http\Client\Response')) {
                $failed[] = $response;
                continue;
            }

            if(str_contains($response->body(), 'Пока нет комментриев')){
                continue;
            }

            Storage::put("dev/pages_htmls/$page.html", $response->body());
        }

        dd(tmr(),$failed);
    }
}
