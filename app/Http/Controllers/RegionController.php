<?php

namespace App\Http\Controllers;

use App\Models\Accident;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;

class RegionController extends Controller
{
    public function chooseFavorite(Request $request): RedirectResponse
    {
        $regionsIds = Arr::pluck($request['favoriteRegions'], 'id');
        $user = auth()->user();
        $user->favoriteRegions()->sync($regionsIds);

        // cancel this user accident's reserve
        Accident::where('reserved_by', $user->id)->get()->each->update(['reserved_by' => null]);

        $message = count($regionsIds) ? 'Город установлен' : 'Выбраны все города';

        return redirect(route('location.create'))->with('success', $message);
    }

}
