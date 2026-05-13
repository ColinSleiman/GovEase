<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\Models\Office;
use App\Models\User;

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
}
