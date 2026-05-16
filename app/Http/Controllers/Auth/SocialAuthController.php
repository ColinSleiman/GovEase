<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        if (!$this->isAllowedProvider($provider)) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        if (!$this->isAllowedProvider($provider)) {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (Throwable $e) {
            return redirect()
                ->route('portal.access')
                ->with('error', ucfirst($provider) . ' authentication failed.');
        }

        $email = $socialUser->getEmail();

        if (empty($email)) {
            $email = $provider . '_' . $socialUser->getId() . '@' . $provider . '.local';
        }

        $name = $socialUser->getName();

        if (empty($name)) {
            $name = $socialUser->getNickname();
        }

        if (empty($name)) {
            $name = ucfirst($provider) . ' User';
        }

        $nameParts = preg_split('/\s+/', trim($name), 2);

        $firstName = $nameParts[0] ?? ucfirst($provider);
        $lastName = $nameParts[1] ?? 'User';

        $citizenRole = Role::where('name', 'Citizen')->first();

        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'password' => Hash::make(Str::random(32)),
                'email_verified_at' => now(),
                'verified' => true,
                'is_active' => true,
                'office_id' => null,
                'role_id' => $citizenRole ? $citizenRole->id : null,
            ]);
        }

        if (!$user->is_active) {
            return redirect()
                ->route('portal.access')
                ->with('error', 'Your account is currently deactivated. Please contact an administrator.');
        }

        if (!$user->role_id && $citizenRole) {
            $user->update([
                'role_id' => $citizenRole->id,
            ]);
        }

        Auth::login($user);
        request()->session()->regenerate();

        if ($user->role?->name === 'Administrator') {
            return redirect()->route('admin.index')->with('success', 'Logged In');
        }

        if ($user->role?->name === 'Citizen') {
            return redirect()->route('citizen.dashboard')->with('success', 'Logged In');
        }

        if ($user->role?->name === 'OfficeStaff') {
            return redirect()->route('office.dashboard')->with('success', 'Logged In');
        }

        return redirect()->route('home')->with('success', 'Logged In');
    }

    private function isAllowedProvider($provider)
    {
        return in_array($provider, [
            'github',
            'facebook',
            'instagram',
        ]);
    }
}
