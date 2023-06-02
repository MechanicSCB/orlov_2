<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->select(['id','name', 'profile_photo_path'])
            ->whereHas('ratings')
            ->withSum('ratings as rating', 'value')
            ->withSum('monthRatings as monthRating', 'value')
            ->withSum('weekRatings as weekRating', 'value')
            ->get();

        $weekBestUsers = $users->sortByDesc('weekRating')->where('weekRating', '>', 0)->take(10)->values();
        $monthBestUsers = $users->sortByDesc('monthRating')->where('monthRating', '>', 0)->take(10)->values();
        $bestUsers = $users->sortByDesc('rating')->take(10)->values();

        return inertia('Rating/Rating', compact('weekBestUsers', 'monthBestUsers', 'bestUsers'));
    }
}
