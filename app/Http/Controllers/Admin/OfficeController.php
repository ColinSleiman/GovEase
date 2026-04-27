<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\Models\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index()
    {
        $data = Office::with('municipality')->get();
        return view('admin.offices.index', compact('data'));
    }

    public function create()
    {
        $data = Municipality::all();

        return view('admin.offices.create', compact('data'));
    }

    public function store(Request $request)
    {
        Office::create($this->validatedOfficeData($request));
        return redirect()->route('admin.offices.index');
    }

    public function show($id)
    {
        $row = Office::with('municipality')->findOrFail($id);
        return view('admin.offices.show', compact('row'));
    }

    public function edit($id)
    {
        $office = Office::findOrFail($id);
        $municipalities = Municipality::all();
        return view('admin.offices.edit', compact('office', 'municipalities'));
    }

    public function update(Request $request, $id)
    {
        $row = Office::findOrFail($id);
        $row->update($this->validatedOfficeData($request));
        return redirect()->route('admin.offices.index');
    }

    public function destroy($id)
    {
        $row = Office::findOrFail($id);
        $row->delete();
        return redirect()->route('admin.offices.index');
    }

    private function validatedOfficeData(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'working_hours' => ['required', 'string', 'max:255'],
            'contact_info' => ['required', 'string', 'max:255'],
            'municipality_id' => ['required', 'exists:municipalities,id'],
        ]);

        $municipality = Municipality::findOrFail($validated['municipality_id']);

        return [
            ...$validated,
            'google_maps_location' => $municipality->google_maps_location,
            'latitude' => $municipality->latitude,
            'longitude' => $municipality->longitude,
        ];
    }
}
