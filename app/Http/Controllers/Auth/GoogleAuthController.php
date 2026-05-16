<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use GuzzleHttp\Client as GuzzleClient;
use Laravel\Socialite\Contracts\Provider as SocialiteProvider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Throwable;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to Google’s OAuth page.
     */
    public function redirect(): RedirectResponse
    {
        return $this->googleDriver()->redirect();
    }

    /**
     * Handle the callback from Google.
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = $this->googleDriver()->user();
        } catch (InvalidStateException $e) {
            Log::warning('Google OAuth state mismatch', ['message' => $e->getMessage()]);

            return redirect()
                ->route('portal.access')
                ->with(
                    'error',
                    'Google sign-in could not be completed. Open the portal using the same address as in your browser bar (for example http://127.0.0.1:8000, not localhost), then try again.'
                );
        } catch (Throwable $e) {
            Log::error('Google OAuth callback failed', [
                'message' => $e->getMessage(),
                'exception' => $e::class,
            ]);

            return redirect()
                ->route('portal.access')
                ->with('error', $this->oauthErrorMessage($e));
        }

        if (empty($googleUser->getEmail())) {
            return redirect()
                ->route('portal.access')
                ->with('error', 'Google did not provide an email address for this account.');
        }

        $existingUser = User::where('email', $googleUser->getEmail())->first();

        if ($existingUser) {
            if (! $existingUser->is_active) {
                return redirect()
                    ->route('portal.access')
                    ->with('error', 'Your account is currently deactivated. Please contact an administrator.');
            }

            Auth::login($existingUser);
            request()->session()->regenerate();

            return $this->redirectAfterLogin();
        }

        $nameParts = preg_split('/\s+/', trim((string) $googleUser->getName()), 2);
        $citizenRole = Role::where('name', 'Citizen')->first();

        $newUser = User::create([
            'firstName' => $googleUser->user['given_name'] ?? $nameParts[0] ?? 'Google',
            'lastName' => $googleUser->user['family_name'] ?? $nameParts[1] ?? 'User',
            'email' => $googleUser->getEmail(),
            'password' => Hash::make(Str::random(32)),
            'email_verified_at' => now(),
            'office_id' => null,
            'role_id' => $citizenRole?->id,
            'verified' => false,
            'is_active' => true,
        ]);

        Auth::login($newUser);
        request()->session()->regenerate();

        return $this->redirectAfterLogin('Account created with Google. Please verify your account to continue.');
    }

    private function googleDriver(): SocialiteProvider
    {
        $driver = Socialite::driver('google')->redirectUrl($this->callbackUrl());

        $httpOptions = ['timeout' => 30];
        $caBundle = config('services.google.ca_bundle');

        if ($caBundle && is_file($caBundle)) {
            $httpOptions['verify'] = $caBundle;
        }

        $driver->setHttpClient(new GuzzleClient($httpOptions));

        return $driver;
    }

    private function callbackUrl(): string
    {
        return url('/api/auth/google/callback');
    }

    private function oauthErrorMessage(Throwable $e): string
    {
        $message = $e->getMessage();

        if (str_contains($message, 'SSL certificate') || str_contains($message, 'cURL error 60')) {
            return 'Google sign-in failed because PHP cannot verify SSL certificates on this machine. '
                .'Ensure storage/cacert.pem exists or set curl.cainfo in php.ini, then try again.';
        }

        return 'Google authentication failed. Please try again or use email sign-up.';
    }

    private function redirectAfterLogin(?string $successMessage = null): RedirectResponse
    {
        $user = Auth::user();
        $redirect = match ($user?->role?->name) {
            'Administrator' => redirect()->route('admin.index'),
            'Citizen' => redirect()->route('citizen.dashboard'),
            'OfficeStaff' => redirect()->route('office.dashboard'),
            default => redirect()->route('home'),
        };

        if ($successMessage) {
            $redirect->with('success', $successMessage);
        } else {
            $redirect->with('success', 'Logged in with Google.');
        }

        return $redirect;
    }
}
