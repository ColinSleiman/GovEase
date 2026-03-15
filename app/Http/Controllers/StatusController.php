<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class StatusController extends Controller
{
    public function index()
    {
        try {
            $data = Status::all();

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', Rule::in(Status::NAMES), 'unique:status,name'],
        ]);

        $status = Status::create($validated);

        return response()->json($status, Response::HTTP_CREATED);
    }

    public function show(Status $status)
    {
        return response()->json($status, Response::HTTP_OK);
    }

    public function edit(Status $status)
    {
        //
    }

    public function update(Request $request, Status $status)
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

        return response()->json($status, Response::HTTP_OK);
    }

    public function destroy(Status $status)
    {
        $status->delete();

        return response()->json(['message' => 'Status deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
