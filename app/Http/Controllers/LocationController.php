<?php

namespace App\Http\Controllers;

use App\Classes\AccidentHandler;
use App\Classes\LocationHandler;
use App\Models\Accident;
use App\Models\Location;
use App\Models\Rating;
use App\Models\Region;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class LocationController extends Controller
{
    public function create(string $complicated = null): Response|ResponseFactory
    {
        $user = auth()->user()
            ->loadSum('ratings as rating', 'value')
            ->load('latestLocations.accident.videos', 'latestLocations.rating', 'favoriteRegions')
        ;

        // if ($complicated === 'complicated' && $user->rating < Rating::ComplicatedAllowedRating) {
        //     return inertia('MessagePage', [
        //         'title' => 'Проставление сложных геолокаций',
        //         'message' => "Сложные геолокации могут проставлять только пользователи с рейтингом " . Rating::ComplicatedAllowedRating . " и выше. Ваш текущий рейтинг: <b>$user->rating</b>",
        //     ]);
        // }

        $accident = (new AccidentHandler())->getAccidentToDefineLocation($complicated);
        $accident->load('videos', 'region');

        $regions = Region::get(['id', 'name']);
        $complicatedAllowedRating = Rating::ComplicatedAllowedRating;

        $locations = Location::whereUserId(auth()->id())
            ->whereNull('note')
            ->with('accident.videos', 'rating')
            ->latest()
            ->take(10)
            ->get();

        return inertia('Locations/Create', compact('accident', 'regions', 'complicatedAllowedRating', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
            'accident_id' => 'required|exists:accidents,id',
            'note' => 'nullable',
            'comment' => 'nullable',
        ]);

        $data = $validated;

        if ($request['approximately']) {
            $data['note'] ??= 'approximately';
        }

        $data['user_id'] = auth()->id();

        $location = Location::create($data);

        $accident = Accident::find($location['accident_id']);
        $accident['reserved_by'] = null;
        $accident->save();

        $is_verified = (new LocationHandler())->verificationCheck($location);

        return redirect(route('location.create'))->withSuccess($is_verified ? 'verified' : 'needs verification');
    }
}
