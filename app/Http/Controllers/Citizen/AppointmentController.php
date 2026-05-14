<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Municipality;
use App\Models\Office;
use App\Models\Service;
use App\Models\Status;
use App\Services\AppointmentSlotService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function __construct(private readonly AppointmentSlotService $slotService)
    {
    }

    public function index()
    {
        $appointments = Appointment::query()
            ->with(['office.municipality', 'service.serviceCategory', 'status'])
            ->where('user_id', Auth::id())
            ->latest('appointment_date')
            ->latest('appointment_time')
            ->paginate(10);

        return view('citizen.appointments.index', [
            'appointments' => $appointments,
        ]);
    }

    public function create()
    {
        $municipalities = Municipality::query()->orderBy('name')->get(['id', 'name']);
        $offices = Office::query()->orderBy('name')->get(['id', 'name', 'municipality_id']);
        $services = Service::query()
            ->with(['office:id,name', 'serviceCategory:id,name'])
            ->orderBy('name')
            ->get(['id', 'name', 'office_id', 'service_category_id']);

        return view('citizen.appointments.create', [
            'municipalities' => $municipalities,
            'offices' => $offices,
            'services' => $services,
            'officesForJs' => $offices->map(fn ($office) => [
                'id' => $office->id,
                'name' => $office->name,
                'municipality_id' => $office->municipality_id,
            ])->values(),
            'servicesForJs' => $services->map(fn ($service) => [
                'id' => $service->id,
                'name' => $service->name,
                'office_id' => $service->office_id,
                'category_name' => $service->serviceCategory?->name,
            ])->values(),
        ]);
    }

    public function slots(Request $request)
    {
        $validated = $request->validate([
            'office_id' => ['required', 'exists:offices,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $office = Office::findOrFail($validated['office_id']);

        return response()->json([
            'slots' => $this->slotService->availableSlots($office, $validated['appointment_date']),
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'office_id' => ['required', 'exists:offices,id'],
            'service_id' => ['required', 'exists:services,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'date_format:H:i:s'],
        ]);

        $office = Office::findOrFail($validated['office_id']);
        $service = Service::query()
            ->where('id', $validated['service_id'])
            ->where('office_id', $office->id)
            ->firstOrFail();

        if (!$this->slotService->isWithinWorkingHours($office, $validated['appointment_time'])) {
            return back()
                ->withErrors(['appointment_time' => 'The selected time is outside office working hours.'])
                ->withInput();
        }

        $availableSlotValues = collect($this->slotService->availableSlots($office, $validated['appointment_date']))
            ->pluck('value')
            ->all();

        if (!in_array($validated['appointment_time'], $availableSlotValues, true)) {
            return back()
                ->withErrors(['appointment_time' => 'The selected time slot is no longer available.'])
                ->withInput();
        }

        $pendingStatus = Status::query()->where('name', 'Pending')->firstOrFail();

        Appointment::create([
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'status_id' => $pendingStatus->id,
            'user_id' => Auth::id(),
            'office_id' => $office->id,
            'service_id' => $service->id,
        ]);

        return redirect()
            ->route('citizen.appointments.index')
            ->with('success', 'Physical visit appointment booked successfully.');
    }
}
