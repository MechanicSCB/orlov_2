<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate(['comment' => 'required']);

        Comment::query()->create([
            'body' => $request['comment'],
            'published_at' => now(),
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'parent_id' => $request['parent_id'],
        ]);

        return back()->withSuccess('comment created!');
    }
}
