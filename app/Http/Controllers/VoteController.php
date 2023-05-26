<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
// use App\Models\Rating;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function vote(Request $request)
    {
        if(! auth()->user()){
            return back()->with('error', 'Только зарегистрированные пользователи могут ставить оценки.');
        }

        $request->validate(['value' => 'integer',]);

        $model = ("App\Models\\$request->modelType")::findOrFail($request['modelId']);

        // User cannot vote for their posts or comments
        if ($model->user->id === auth()->id()){
            return back()->withError('Нельзя голосовать за свои посты или комментарии.');
        }

        $vote = $model->vote($request['value']);

        // Increase/Decrease post/comment author's rating
        // Rating::create([
        //     'value' => $request['value'] * Rating::VoteAward,
        //     'user_id' => $model->user_id,
        //     'vote_id' => $vote->id,
        // ]);

        return back();
    }

    public function unvote(Request $request)
    {
        if ($request['modelType'] === 'Post') {
            $model = Post::findOrFail($request['modelId']);
        } elseif ($request['modelType'] === 'Comment') {
            $model = Comment::findOrFail($request['modelId']);
        } else {
            abort(404);
        }

        $model->unvote();

        return back();
    }
}
