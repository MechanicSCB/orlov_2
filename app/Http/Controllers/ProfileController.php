<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Location;
use Inertia\Response;
use Inertia\ResponseFactory;

class ProfileController extends Controller
{
    public function locations(): Response|ResponseFactory
    {
        $locations = Location::whereUserId(auth()->id())
            ->whereNull('note')
            ->with('accident.videos', 'rating')
            ->latest()
            ->paginate(10);

        return inertia('Profile/Locations', compact('locations'));
    }

    public function comments(): Response|ResponseFactory
    {
        $comments = Comment::whereUserId(auth()->id())
            ->latest('published_at')
            ->paginate(5);

        return inertia('Profile/Comments', compact('comments'));
    }
}
