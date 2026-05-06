<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfficeServiceController extends Controller
{
    // Office service CRUD controller (office-owned records only).
    public function index()
    {
        $data = Service::query()
            ->with('serviceCategory')
            ->where('office_id', $this->officeId())
            ->latest()
            ->paginate(10);

        return view('office.services.index', compact('data'));
    }

    public function create()
    {
        return view('office.services.create', [
            'categories' => $this->categoriesForOffice(),
        ]);
    }

    public function store(Request $request)
    {
        $officeId = $this->officeId();
        $validated = $this->validateService($request, $officeId);

        Service::create(array_merge($validated, [
            'office_id' => $officeId,
        ]));

        return redirect()
            ->route('office.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function show(Service $service)
    {
        $this->authorizeOfficeService($service);

        return view('office.services.show', [
            'row' => $service->load('serviceCategory'),
        ]);
    }

    public function edit(Service $service)
    {
        $this->authorizeOfficeService($service);

        return view('office.services.edit', [
            'row' => $service,
            'categories' => $this->categoriesForOffice(),
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $this->authorizeOfficeService($service);
        $officeId = $this->officeId();
        $validated = $this->validateService($request, $officeId);

        $service->update($validated);

        return redirect()
            ->route('office.services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $this->authorizeOfficeService($service);
        $service->delete();

        return redirect()
            ->route('office.services.index')
            ->with('success', 'Service deleted successfully.');
    }

    private function validateService(Request $request, int $officeId): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'integer', 'min:1'],
            'service_category_id' => [
                'required',
                'exists:service_categories,id',
                function ($attribute, $value, $fail) use ($officeId) {
                    $belongsToOffice = ServiceCategory::query()
                        ->where('id', $value)
                        ->where('office_id', $officeId)
                        ->exists();

                    if (!$belongsToOffice) {
                        $fail('Selected category is not available for your office.');
                    }
                },
            ],
        ]);
    }

    private function categoriesForOffice()
    {
        return ServiceCategory::query()
            ->where('office_id', $this->officeId())
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    private function officeId(): int
    {
        $officeId = (int) Auth::user()?->office_id;
        abort_if(!$officeId, 403);

        return $officeId;
    }

    private function authorizeOfficeService(Service $service): void
    {
        abort_if((int) $service->office_id !== $this->officeId(), 403);
    }
}
