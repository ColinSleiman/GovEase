<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            if (! Auth::user()->is_active) {
                Auth::logout();

                return back()
                    ->with('error', 'Your account is currently deactivated. Please contact an administrator.')
                    ->onlyInput('email');
            }

            $request->session()->regenerate();

            if (Auth::user()->role?->name === 'Administrator') {
                return redirect()
                    ->route('admin.index')
                    ->with('success', 'Logged In');
            }

            if (Auth::user()->role?->name === 'Citizen') {
                return redirect()
                    ->route('citizen.dashboard')
                    ->with('success', 'Logged In');
            }

            if (Auth::user()->role?->name === 'OfficeStaff') {
                return redirect()
                    ->route('office.dashboard')
                    ->with('success', 'Logged In');
            }
            
            return redirect()
                ->route('home')
                ->with('success','Logged In');
        }

        return back()
        ->with('error','Invalid credentials')
        ->onlyInput('email');
    }
}
