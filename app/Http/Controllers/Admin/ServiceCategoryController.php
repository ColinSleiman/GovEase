<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ServiceCategoryController extends Controller
{
    // Admin service category controller currently serves JSON API responses.
    // Display all service categories
    public function index()
    {
        $data = ServiceCategory::all();

        return response()->json($data, Response::HTTP_OK);
    }

    // Show the form for creating a new service category
    public function create()
    {
        //
    }

    // Store a newly created service category
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:service_categories,name'],
        ]);

        $category = ServiceCategory::create($validated);

        return response()->json($category, Response::HTTP_CREATED);
    }

    // Display a specific service category
    public function show(ServiceCategory $serviceCategory)
    {
        return response()->json($serviceCategory, Response::HTTP_OK);
    }

    // Show the form for editing a service category
    public function edit(ServiceCategory $serviceCategory)
    {
        //
    }

    // Update a service category
    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:service_categories,name,' . $serviceCategory->id],
        ]);

        $serviceCategory->update($validated);

        return response()->json($serviceCategory, Response::HTTP_OK);
    }

    // Delete a service category
    public function destroy(ServiceCategory $serviceCategory)
    {
        $serviceCategory->delete();

        return response()->json(['message' => 'Service Category deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
