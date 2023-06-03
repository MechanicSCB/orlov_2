<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Redirector;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialiteController extends Controller
{
    public function redirectToProvider(string $providerName): RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        return Socialite::driver($providerName)->redirect();
    }

    public function handleProviderCallback(string $providerName): Application|Redirector|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $providerUser = Socialite::driver($providerName)->user();
        }catch (\Exception $exception){
            // log
            return redirect('login');
        }

        $email = $providerUser->getEmail() ?? "{$providerUser->getId()}@$providerName.example";

        $user = User::query()->firstOrCreate([
            'email' => $email,
        ], [
            'name' => $providerUser->name ?? $providerUser->nickname,
            'provider_id' => $providerUser->getId(),
            'password' => bcrypt($email),
        ]);

        Team::query()->firstOrCreate(
            [
                'user_id' => $user->id,
                'name' => "$user->name's Team",
            ],
            [
                'personal_team' => 1,
            ],
        );

        // Log the user in
        auth()->login($user, true);

        // Redirect to dashboard
        return redirect('/user/profile');
    }
}
