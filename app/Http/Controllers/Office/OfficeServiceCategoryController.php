<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OfficeServiceCategoryController extends Controller
{
    // Office service category CRUD controller (office-owned records only).
    public function index()
    {
        $officeId = $this->officeId();
        $data = ServiceCategory::query()
            ->where('office_id', $officeId)
            ->withCount('services')
            ->latest()
            ->paginate(10);

        return view('office.service-categories.index', compact('data'));
    }

    public function create()
    {
        return view('office.service-categories.create');
    }

    public function store(Request $request)
    {
        $officeId = $this->officeId();
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('service_categories', 'name')->where(fn ($query) => $query->where('office_id', $officeId)),
            ],
        ]);

        ServiceCategory::create([
            'name' => $validated['name'],
            'office_id' => $officeId,
        ]);

        return redirect()
            ->route('office.service-categories.index')
            ->with('success', 'Service category created successfully.');
    }

    public function show(ServiceCategory $service_category)
    {
        $this->authorizeOfficeCategory($service_category);

        return view('office.service-categories.show', [
            'row' => $service_category->loadCount('services'),
        ]);
    }

    public function edit(ServiceCategory $service_category)
    {
        $this->authorizeOfficeCategory($service_category);

        return view('office.service-categories.edit', [
            'row' => $service_category,
        ]);
    }

    public function update(Request $request, ServiceCategory $service_category)
    {
        $this->authorizeOfficeCategory($service_category);
        $officeId = $this->officeId();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('service_categories', 'name')
                    ->ignore($service_category->id)
                    ->where(fn ($query) => $query->where('office_id', $officeId)),
            ],
        ]);

        $service_category->update($validated);

        return redirect()
            ->route('office.service-categories.index')
            ->with('success', 'Service category updated successfully.');
    }

    public function destroy(ServiceCategory $service_category)
    {
        $this->authorizeOfficeCategory($service_category);

        if ($service_category->services()->exists()) {
            return back()->with('error', 'Cannot delete a category that still has services.');
        }

        $service_category->delete();

        return redirect()
            ->route('office.service-categories.index')
            ->with('success', 'Service category deleted successfully.');
    }

    private function officeId(): int
    {
        $officeId = (int) Auth::user()?->office_id;
        abort_if(!$officeId, 403);

        return $officeId;
    }

    private function authorizeOfficeCategory(ServiceCategory $serviceCategory): void
    {
        abort_if((int) $serviceCategory->office_id !== $this->officeId(), 403);
    }
}
