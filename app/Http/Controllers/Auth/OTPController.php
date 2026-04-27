<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPMail;

class OTPController extends Controller
{
    /**
     * Show the OTP verification page.
     */
    public function show()
    {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Please login first');
        }

        $user = Auth::user();
        
        if ($user->verified) {
            return redirect()->route('home')->with('info', 'Your account is already verified');
        }

        return view('auth.verify-otp', ['title' => 'Verify Account']);
    }

    /**
     * Send OTP to the user's email.
     */
    public function send(Request $request)
    {
        $user = Auth::user();
        
        if ($user->verified) {
            return response()->json(['message' => 'User already verified'], 400);
        }

        // Generate OTP
        $oneTimePassword = $user->createOneTimePassword();

        // Get the actual OTP string from the object (stored in 'password' field)
        $otpCode = $oneTimePassword->password;

        // Send OTP via email
        try {
            $otpMail = (new OTPMail);
            $otpMail->otp = $otpCode;
            Mail::to($user->email)->send($otpMail);
            return response()->json(['message' => 'OTP sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send OTP: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Verify the OTP.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = Auth::user();
        
        if ($user->verified) {
            return redirect()->route('home')->with('success', 'Account already verified');
        }

        // Use the correct Spatie OTP validation method
        $result = $user->consumeOneTimePassword($request->otp);

        if ($result->isOk()) {
            // Regenerate session id after successful verification
            $request->session()->regenerate();
            
            // Mark user as verified
            $user->update(['verified' => true]);
            
            return redirect()->route('home')->with('success', 'Account verified successfully!');
        }

        return back()->withErrors([
            'otp' => $result->validationMessage(),
        ])->onlyInput('otp');
    }
}
