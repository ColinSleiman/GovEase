<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MunicipalityController extends Controller
{
    // Display all municipalities
    public function index()
    {
        try {
            $data = Municipality::all();
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Show the form for creating a new municipality
    public function create()
    {
        //
    }

    // Store a newly created municipality
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name',
            'region',
        ]);

        $municipality = Municipality::create($validated);

        return response()->json($municipality, Response::HTTP_CREATED);
    }

    // Display a specific municipality
    public function show(Municipality $municipality)
    {
        return response()->json($municipality, Response::HTTP_OK);
    }

    // Show the form for editing a municipality
    public function edit(Municipality $municipality)
    {
        //
    }

    // Update a municipality
    public function update(Request $request, Municipality $municipality)
    {
        $validated = $request->validate([
            'name',
            'region',
        ]);

        $municipality->update($validated);

        return response()->json($municipality, Response::HTTP_OK);
    }

    // Delete a municipality
    public function destroy(Municipality $municipality)
    {
        $municipality->delete();

        return response()->json(['message' => 'Municipality deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
