<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'office_id' => 'required|exists:offices,id',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'office_id' => $validated['office_id'],
            'role_id' => $validated['role_id'],
            'password' => Hash::make($validated['password']),
            'office_id' => null,
            'role_id' => 1, // Default Citizen role
            'verified' => false, // New users need verification
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success','Account created successfully');
    }
}
