<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use Illuminate\Http\Request;

class MunicipalityController extends Controller
{
    public function index()
    {
        $data = Municipality::all();
        return view('admin.municipalities.index', compact('data'));
    }

    public function create()
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $municipalities = Municipality::query()
            ->select('id', 'name', 'region', 'latitude', 'longitude')
            ->get();

        return view('admin.municipalities.create', compact('apiKey', 'municipalities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'region' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'google_maps_location' => ['nullable', 'string', 'max:255'],
            'working_hours' => ['nullable', 'string', 'max:255'],
            'contact_info' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['google_maps_location'] = $validated['google_maps_location']
            ?: $validated['latitude'] . ',' . $validated['longitude'];

        Municipality::create($validated);
        return redirect()->route('admin.municipalities.index');
    }

    public function show($id)
    {
        $municipality = Municipality::findOrFail($id);
        return view('admin.municipalities.show', compact('municipality'));
    }

    public function edit($id)
    {
        $municipality = Municipality::findOrFail($id);
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $municipalities = Municipality::query()
            ->select('id', 'name', 'region', 'latitude', 'longitude')
            ->where('id', '!=', $municipality->id)
            ->get();

        return view('admin.municipalities.edit', compact('municipality', 'apiKey', 'municipalities'));
    }

    public function update(Request $request, $id)
    {
        $municipality = Municipality::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'region' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'google_maps_location' => ['nullable', 'string', 'max:255'],
            'working_hours' => ['nullable', 'string', 'max:255'],
            'contact_info' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['google_maps_location'] = $validated['google_maps_location']
            ?: $validated['latitude'] . ',' . $validated['longitude'];

        $municipality->update($validated);
        return redirect()->route('admin.municipalities.index');
    }

    public function destroy($id)
    {
        $municipality = Municipality::findOrFail($id);
        $municipality->delete();
        return redirect()->route('admin.municipalities.index');
    }
}
