<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return Appointment::with(['user', 'status', 'office', 'service'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status_id' => 'required|exists:statuses,id',
            'user_id' => 'required|exists:users,id',
            'office_id' => 'required|exists:offices,id',
            'service_id' => 'required|exists:services,id',
        ]);

        $appointment = Appointment::create($validated);

        return response()->json([
            'message' => 'Appointment created successfully',
            'data' => $appointment
        ], Response::HTTP_OK);
    }

    public function show(Appointment $appointment)
    {
        return $appointment->load(['user', 'status', 'office', 'service']);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'appointment_date' => 'sometimes|date',
            'appointment_time' => 'sometimes',
            'status_id' => 'sometimes|exists:statuses,id',
            'user_id' => 'sometimes|exists:users,id',
            'office_id' => 'sometimes|exists:offices,id',
            'service_id' => 'sometimes|exists:services,id',
        ]);

        $appointment->update($validated);

        return response()->json([
            'message' => 'Appointment updated successfully',
            'data' => $appointment
        ]);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json([
            'message' => 'Appointment deleted successfully'
        ]);
    }
}
