<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function offices()
    {
        return view('admin.offices.index');
    }

    public function officesCreate()
    {
        return view('admin.offices.index');
    }

    public function municipalities()
    {
        return view('admin.municipalities.index');
    }

    public function municipalitiesCreate()
    {
        return view('admin.municipalities.index');
    }

    public function users()
    {
        return view('admin.users.index');
    }

    public function usersCreate()
    {
        return view('admin.users.index');
    }

    public function requests()
    {
        return view('admin.dashboard');
    }

    public function servicesMonitor()
    {
        return view('admin.dashboard');
    }

    public function reportsOfficeRequests()
    {
        return view('admin.dashboard');
    }

    public function reportsRevenue()
    {
        return view('admin.dashboard');
    }

    public function services()
    {
        return view('admin.dashboard');
    }

    public function settings()
    {
        return view('admin.dashboard');
    }

    public function reports()
    {
        return view('admin.dashboard');
    }

    public function notifications()
    {
        return view('admin.dashboard');
    }

    public function logs()
    {
        return view('admin.dashboard');
    }

    public function help()
    {
        return view('admin.dashboard');
    }
}
