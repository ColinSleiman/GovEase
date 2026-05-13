<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Municipality;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $municipalities = Municipality::query()
            ->select('id', 'name', 'region', 'address', 'latitude', 'longitude', 'working_hours', 'contact_info')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderBy('name')
            ->get();

        return view('citizen.dashboard', [
            'apiKey' => config('services.google.maps_api_key') ?: env('GOOGLE_MAPS_API_KEY'),
            'municipalities' => $municipalities,
        ]);
    }
}
