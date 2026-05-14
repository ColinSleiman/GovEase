<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Status;
use App\Services\AppointmentSlotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfficeAppointmentController extends Controller
{
    public function __construct(private readonly AppointmentSlotService $slotService)
    {
    }

    public function index(Request $request)
    {
        $officeId = (int) Auth::user()?->office_id;
        abort_if(!$officeId, 403);

        $query = Appointment::query()
            ->with(['user', 'service.serviceCategory', 'status'])
            ->where('office_id', $officeId)
            ->latest('appointment_date')
            ->latest('appointment_time');

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->integer('service_id'));
        }

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->integer('status_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->input('date_to'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($searchQuery) use ($search) {
                $searchQuery
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery
                            ->whereRaw("CONCAT(firstName, ' ', lastName) like ?", ['%' . $search . '%'])
                            ->orWhere('email', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('service', fn ($serviceQuery) => $serviceQuery->where('name', 'like', '%' . $search . '%'));
            });
        }

        $rows = $query->paginate(10)->withQueryString();

        return view('office.appointments.index', [
            'rows' => $rows,
            'statuses' => Status::query()->orderBy('name')->get(['id', 'name']),
            'services' => Service::query()->where('office_id', $officeId)->orderBy('name')->get(['id', 'name']),
            'filters' => [
                'service_id' => $request->input('service_id'),
                'status_id' => $request->input('status_id'),
                'date_from' => $request->input('date_from'),
                'date_to' => $request->input('date_to'),
                'search' => $request->input('search'),
            ],
        ]);
    }

    public function show(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);
        $appointment->load(['user', 'service.serviceCategory', 'status', 'office.municipality']);

        return view('office.appointments.show', [
            'row' => $appointment,
        ]);
    }

    public function edit(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);
        $appointment->load(['user', 'service.serviceCategory', 'status', 'office']);

        return view('office.appointments.edit', [
            'row' => $appointment,
            'statuses' => Status::query()->orderBy('name')->get(['id', 'name']),
            'services' => Service::query()->where('office_id', $appointment->office_id)->orderBy('name')->get(['id', 'name']),
            'slots' => $this->slotService->availableSlots($appointment->office, $appointment->appointment_date, $appointment->id),
        ]);
    }

    public function slots(Request $request)
    {
        $officeId = (int) Auth::user()?->office_id;
        abort_if(!$officeId, 403);

        $validated = $request->validate([
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'ignore_appointment_id' => ['nullable', 'integer', 'exists:appointments,id'],
        ]);

        $office = Auth::user()->office;

        return response()->json([
            'slots' => $this->slotService->availableSlots(
                $office,
                $validated['appointment_date'],
                $validated['ignore_appointment_id'] ?? null
            ),
        ]);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);

        $validated = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'status_id' => ['required', 'exists:statuses,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'date_format:H:i:s'],
        ]);

        $service = Service::query()
            ->where('id', $validated['service_id'])
            ->where('office_id', $appointment->office_id)
            ->firstOrFail();

        if (!$this->slotService->isWithinWorkingHours($appointment->office, $validated['appointment_time'])) {
            return back()
                ->withErrors(['appointment_time' => 'The selected time is outside office working hours.'])
                ->withInput();
        }

        $availableSlotValues = collect($this->slotService->availableSlots($appointment->office, $validated['appointment_date'], $appointment->id))
            ->pluck('value')
            ->push($appointment->appointment_time)
            ->unique()
            ->all();

        if (!in_array($validated['appointment_time'], $availableSlotValues, true)) {
            return back()
                ->withErrors(['appointment_time' => 'The selected time slot is no longer available.'])
                ->withInput();
        }

        $appointment->update([
            'service_id' => $service->id,
            'status_id' => $validated['status_id'],
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
        ]);

        return redirect()
            ->route('office.appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);
        $appointment->delete();

        return redirect()
            ->route('office.appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    private function authorizeAppointment(Appointment $appointment): void
    {
        abort_if((int) $appointment->office_id !== (int) Auth::user()?->office_id, 403);
    }
}
