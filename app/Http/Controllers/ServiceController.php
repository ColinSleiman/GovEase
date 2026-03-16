<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ServiceController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $data = Service::all();
        return response()->json($data, Response::HTTP_OK);
    }

    // Show the form for creating a new resource.
    public function create()
    {
        //
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name',
            'description',
            'price',
            'duration',
            'office_id' => 'required|exists:offices,id',
            'service_category_id' => 'required|exists:service_categories,id',
        ]);

        // Create service
        $service = Service::create($validated);

        return response()->json($service, Response::HTTP_CREATED);
    }

    // Display the specified resource.
    public function show(Service $service)
    {
        return response()->json($service, Response::HTTP_OK);
    }

    // Show the form for editing the specified resource.
    public function edit(Service $service)
    {
        //
    }

    // Update the specified resource in storage.
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name',
            'description',
            'price',
            'duration',
            'office_id' => 'sometimes|required|exists:offices,id',
            'service_category_id' => 'sometimes|required|exists:service_categories,id',
        ]);

        $service->update($validated);

        return response()->json($service, Response::HTTP_OK);
    }

    // Remove the specified resource from storage.
    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json(['message' => 'Service deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
