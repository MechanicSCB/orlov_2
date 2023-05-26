<?php

namespace App\Dev;

use DiDom\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Parser
{
    public function loadAvatars()
    {
        dd(tmr(), 'loadAvatars');
        $users = json_decode(Storage::get("dev/users.json"), 1);
        $urls = array_column($users,'img');

        foreach ($urls as $url){
            if(! str_starts_with($url, 'https:/')){
                $url = 'https://orlov-dtp.ru/' . $url;
            }

            $filename = Str::afterLast($url, '/');
            $img = file_get_contents($url);
            file_put_contents(storage_path("app/public/profile-photos/$filename"), $img);
        }

        dd(tmr(),$urls);
    }

    public function parseUsers()
    {
        dd(tmr(), 'parseUsers');

        $files = array_filter(scandir(storage_path('app/dev/users_htmls')), fn($v) => str_contains($v, '.html'));
        //dd(tmr(),$files);
        $users = [];

        foreach ($files as $file) {
            $html = file_get_contents(storage_path("app/dev/users_htmls/$file"));
            $userId = Str::before($file, '.');

            if (str_contains($html, 'Server')) {
                continue;
            }

            $document = new Document($html);
            $el = $document->first('div.container-body');

            $user['id'] = $userId;
            $user['img'] = $el->first('img.img-thumbnail')?->getAttribute('src');
            $user['name'] = $el->first('h2')?->text();
            $user['rating'] = $el->first('div>b.bd-highlight')?->text();
            $user['about'] = $el->first('div.mt-2')?->text();
            $users[$userId] = $user;
        }

        // dd(tmr(),$users);
        Storage::put("dev/users.json", json_encode($users));
    }

    public function parsePosts(): array
    {
        dd(tmr(), 'parsePosts');
        $htmls = [];
        $posts = [];

        foreach (range(215, 333) as $id) {
            $htmls[$id] = Storage::get("dev/post_htmls/$id.html");
        }

        foreach ($htmls as $postId => $html) {
            $document = new Document($html);

            $post['title'] = $document->first('h1.publication-name')?->innerHtml();
            $post['user'] = $document->first('a.text-body[href^=/users/]')?->html();
            $post['user_id'] = Str::afterLast($document->first('a.text-body[href^=/users/]')?->getAttribute('href'), '/');
            $post['published_at'] = $document->first('div.publication-date')?->getAttribute('data-time');
            $post['video'] = $document->first('iframe.publication-video')?->getAttribute('src');
            $post['body'] = $document->first('div.publication-text')?->innerHtml();
            $post['images'] = array_map(fn($v) => $v?->getAttribute('src'), $document->find('img.publication-image'));
            $posts[$postId] = $post;
        }

        Storage::put("dev/posts.json", json_encode($posts));
        return $posts;
    }

    public function parseComments(): array
    {
        //dd(tmr(), 'parseComments');
        $files = array_filter(scandir(storage_path('app/dev/comments_htmls')), fn($v) => str_contains($v, '.html'));
        $comments = [];

        foreach ($files as $file) {
            $html = file_get_contents(storage_path("app/dev/comments_htmls/$file"));
            $postId = Str::before($file, '.');

            if (str_contains($html, 'Server')) {
                continue;
            }

            $document = new Document($html);

            foreach ($document->find('div.comment-item') as $item){
                $comment['id'] = Str::afterLast($item->getAttribute('id'), '-');
                $comment['user_id'] = Str::afterLast($item->first('div.comment-data a.text-body')?->getAttribute('href'),'/');
                $comment['datetime'] = $item->first('div.time')?->getAttribute('data-time');
                $comment['body'] = $item->first('div.text font')?->innerHtml();
                $comments[$postId][] = $comment;
            }
        }

        Storage::put("dev/comments.json", json_encode($comments));
        dd($comments);
    }

    public function parsePreviews(): array
    {
        // dd(tmr(), 'parsePreviews');
        $htmls = [];
        $prevs = [];

        foreach (range(1, 9) as $page) {
            $htmls[$page] = Storage::get("dev/pages_htmls/$page.html");
        }

        foreach ($htmls as $page => $html) {
            $document = new Document($html);

            $pagePosts = $document->find('a.publication-item');

            foreach ($pagePosts as $post) {
                $id = Str::afterLast($post->getAttribute('href'), '/');
                $prevs[$id] = $post->first('img.img-fluid')?->getAttribute('src');
            }
        }

        Storage::put("dev/prevs.json", json_encode($prevs));
        return $prevs;
    }

    public function savePreviews(): array
    {
        dd(tmr(), 'savePreviews');
        $prevs = array_filter(json_decode(file_get_contents(storage_path('app/dev/prevs.json')), 1));

        foreach ($prevs as $postId => $url) {
            $img = file_get_contents($url);
            file_put_contents(storage_path("app/dev/prevs/$postId.jpg"), $img);
        }

        return $prevs;
    }
}
