<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OfficeController extends Controller
{
    // Display all offices
    public function index()
    {
        $data = Office::all();

        return response()->json($data, Response::HTTP_OK);
    }

    // Show the form for creating a new office
    public function create()
    {
        //
    }

    // Store a newly created office
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name',
            'address',
            'google_maps_location',
            'latitude',
            'longitude',
            'working_hours',
            'contact_info',
            'municipality_id' => 'required|exists:municipalities,id',
        ]);

        $office = Office::create($validated);

        return response()->json($office, Response::HTTP_CREATED);
    }

    // Display a specific office
    public function show(Office $office)
    {
        $data = Office::find($office);

        return response()->json($data, Response::HTTP_OK);
    }

    // Show the form for editing an office
    public function edit(Office $office)
    {
        //
    }

    // Update an office
    public function update(Request $request, Office $office)
    {
        $validated = $request->validate([
            'name',
            'address',
            'google_maps_location',
            'latitude',
            'longitude',
            'working_hours',
            'contact_info',
            'municipality_id' => 'sometimes|required|exists:municipalities,id',
        ]);

        $office->update($validated);

        return response()->json($office, Response::HTTP_OK);
    }

    // Delete an office
    public function destroy(Office $office)
    {
        $office->delete();

        return response()->json(['message' => 'Office deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
