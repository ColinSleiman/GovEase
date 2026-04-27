<?php

namespace App\Http\Controllers;

use App\Models\Request as RequestModel;
use App\Models\User;
use App\Models\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserRequestController extends Controller
{
    public function index()
    {
        $data = UserRequest::with(['user', 'request'])->get();
        return response()->json($data, Response::HTTP_OK);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'request_id' => 'required|exists:requests,id',
        ]);

        $userRequest = UserRequest::create($validated);

        return response()->json(
            $userRequest->load(['user', 'request']),
            Response::HTTP_CREATED
        );
    }

    public function show(User $user, RequestModel $requestModel)
    {
        $userRequest = UserRequest::where('user_id', $user->id)
            ->where('request_id', $requestModel->id)
            ->firstOrFail();

        return response()->json(
            $userRequest->load(['user', 'request']),
            Response::HTTP_OK
        );
    }

    public function edit(UserRequest $userRequest)
    {
        //
    }

    public function update(Request $request, User $user, RequestModel $requestModel)
    {
        $validated = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'request_id' => 'sometimes|required|exists:requests,id',
        ]);

        $existing = UserRequest::where('user_id', $user->id)
            ->where('request_id', $requestModel->id)
            ->firstOrFail();

        $newUserId = $validated['user_id'] ?? $user->id;
        $newRequestId = $validated['request_id'] ?? $requestModel->id;

        if ($newUserId !== $user->id || $newRequestId !== $requestModel->id) {
            UserRequest::where('user_id', $user->id)
                ->where('request_id', $requestModel->id)
                ->delete();
            $existing = UserRequest::firstOrCreate([
                'user_id' => $newUserId,
                'request_id' => $newRequestId,
            ]);
        }

        return response()->json(
            $existing->load(['user', 'request']),
            Response::HTTP_OK
        );
    }

    public function destroy(User $user, RequestModel $requestModel)
    {
        UserRequest::where('user_id', $user->id)
            ->where('request_id', $requestModel->id)
            ->delete();

        return response()->json(['message' => 'UserRequest deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
