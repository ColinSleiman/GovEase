<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Validation\Rule;

class StatusController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Status::query()->latest('id')->get());
    }

    public function store(HttpRequest $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::in(Status::NAMES), 'unique:status,name'],
        ]);

        $status = Status::create($validated);

        return response()->json($status, 201);
    }

    public function show(Status $status): JsonResponse
    {
        return response()->json($status);
    }

    public function update(HttpRequest $request, Status $status): JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::in(Status::NAMES),
                Rule::unique('status', 'name')->ignore($status->id),
            ],
        ]);

        $status->update($validated);

        return response()->json($status);
    }

    public function destroy(Status $status): JsonResponse
    {
        $status->delete();

        return response()->json(status: 204);
    }
}
