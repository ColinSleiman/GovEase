<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Throwable;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to Google’s OAuth page.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google.
     */
    public function callback()
    {
        try {
            // Get the user information from Google
            $user = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            return redirect('/')->with('error', 'Google authentication failed.');
        }

        // Check if the user already exists in the database
        $existingUser = User::where('email', $user->email)->first();

        if ($existingUser) {
            // Log the user in if they already exist
            Auth::login($existingUser);
        } else {
            $nameParts = preg_split('/\s+/', trim((string) $user->name), 2);

            // Otherwise, create a new user and log them in
            $newUser = User::updateOrCreate([
                'email' => $user->email
            ], [
                'firstName' => $user->user['given_name'] ?? $nameParts[0] ?? '',
                'lastName' => $user->user['family_name'] ?? $nameParts[1] ?? '',
                'password' => bcrypt(Str::random(16)), // Set a random password
                'email_verified_at' => now(),
                'verified' => false // Google users also need verification
            ]);
            Auth::login($newUser);
        }

        return redirect()->route('home');
    }
}
