<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.dashboard');
    }

    public function dashboard()
    {
        $offices = Office::latest()->take(5)->get();
        $municipalities = Municipality::latest()->take(5)->get();
        $users = User::latest()->take(5)->get();

        return view('admin.dashboard.index', compact('offices', 'municipalities', 'users'));
    }

    public function requests()
    {
        return redirect()->route('admin.dashboard');
    }

    public function servicesMonitor()
    {
        return redirect()->route('admin.dashboard');
    }

    public function reportsOfficeRequests()
    {
        return redirect()->route('admin.dashboard');
    }

    public function reportsRevenue()
    {
        return redirect()->route('admin.dashboard');
    }

    public function services()
    {
        return redirect()->route('admin.dashboard');
    }

    public function settings()
    {
        return redirect()->route('admin.dashboard');
    }

    public function reports()
    {
        return redirect()->route('admin.dashboard');
    }

    public function notifications()
    {
        return redirect()->route('admin.dashboard');
    }

    public function logs()
    {
        return redirect()->route('admin.dashboard');
    }

    public function help()
    {
        return redirect()->route('admin.dashboard');
    }

    public function stripeTest()
    {
        return view('admin.stripe.index', [
            'stripeKey' => env('STRIPE_KEY'),
            'paymentLink' => env('STRIPE_PAYMENT_LINK', 'https://buy.stripe.com/test_bJe4gy2DI3Go4JVd2T0VO00'),
        ]);
    }

    public function stripeSuccess()
    {
        return view('admin.stripe.success');
    }

    public function createIntent(Request $request)
    {
        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:50'],
        ]);

        if (! env('STRIPE_SECRET')) {
            return response()->json(['message' => 'STRIPE_SECRET is not configured.'], 422);
        }

        if (! class_exists(\Stripe\Stripe::class) || ! class_exists(\Stripe\PaymentIntent::class)) {
            return response()->json(['message' => 'stripe/stripe-php is not installed. Run composer install.'], 422);
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $intent = \Stripe\PaymentIntent::create([
            'amount' => $validated['amount'],
            'currency' => 'usd',
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

        return response()->json(['clientSecret' => $intent->client_secret]);
    }
}
