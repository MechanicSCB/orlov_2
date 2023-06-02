<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class UserController extends Controller
{
    public function show(User $user): Response|ResponseFactory
    {
        $user->loadSum('ratings as rating', 'value')
            ->load('latestComments.post:id,slug,title');

        foreach ($user->latestComments as $comment){
            $comment->loadSum('votes', 'value');
        }

        // dd(tmr(),$user);
        return inertia('Users/Show', ['member' => $user]);
    }

}
