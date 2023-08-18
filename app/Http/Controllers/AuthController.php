<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $facebookUser = Socialite::driver('facebook')->stateless()->user();

        $user = User::firstOrCreate(
            [
                'provider_id' => $facebookUser->getId(),
            ],
            [
                'email' => $facebookUser->getEmail(),
                'name' => $facebookUser->getName(),
                'profile_photo_path' => $facebookUser->getAvatar(),
                'ip' => request()->ip(),
                'state_id' => (new UserService())->getStateViaIp(),
                'role' => 'user',
                'oauth_provider' => 'facebook'
            ]
        );

        auth()->login($user, true);

        // Redirect to dashboard
        return redirect('home');

    }

    public function redirectToTwitter()
    {
        return Socialite::driver('twitter-oauth-2')->redirect();
    }


    /**
     * Twitter is not returning user email. Please see
     * Request email from users
    To request email from users, you are required to provide URLs to your App’s privacy policy and terms of service.
     * https://developer.twitter.com/en/portal/projects/1681077227436359681/apps/27641356/auth-settings
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleTwitterCallback()
    {
        $twitterUser = Socialite::driver('twitter-oauth-2')->stateless()->user();

        $user = User::firstOrCreate(
            [
                'provider_id' => $twitterUser->getId(),
            ],
            [
                'email' => $twitterUser->getEmail() ?? $twitterUser->getNickname(), // Twitter is not returning user email as part of their response
                'name' => $twitterUser->getName(),
                'profile_photo_path' => $twitterUser->getAvatar(),
                'ip' => request()->ip(),
                'state_id' => (new UserService())->getStateViaIp(),
                'role' => 'user',
                'oauth_provider' => 'twitter'
            ]
        );

        auth()->login($user, true);

        // Redirect to dashboard
        return redirect('home');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::firstOrCreate(
            [
                'provider_id' => $googleUser->getId(),
            ],
            [
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'profile_photo_path' => $googleUser->getAvatar(),
                'ip' => request()->ip(),
                'state_id' => (new UserService())->getStateViaIp(),
                'role' => 'user',
                'oauth_provider' => 'google'
            ]
        );

        auth()->login($user, true);

        // Redirect to dashboard
        return redirect('home');
    }
}
