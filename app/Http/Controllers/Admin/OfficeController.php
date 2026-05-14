<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\Models\Office;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class OfficeController extends Controller
{
    public function index()
    {
        $data = Office::all();
        return view('admin.offices.index', compact('data'));
    }

    public function create()
    {
        $data = Municipality::all();

        return view('admin.offices.create', compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateOffice($request);
        $municipality = Municipality::findOrFail($validated['municipality_id']);

        $office = DB::transaction(function () use ($validated, $municipality) {
            $office = Office::create([
                'name' => $validated['name'],
                'address' => $validated['address'],
                'working_hours' => $validated['working_hours'],
                'contact_info' => $validated['contact_info'],
                'municipality_id' => $validated['municipality_id'],
                'google_maps_location' => $municipality->google_maps_location,
                'latitude' => $municipality->latitude,
                'longitude' => $municipality->longitude,
            ]);

            $this->createOfficeStaffIfRequested($validated, $office);

            return $office;
        });

        $message = 'Office created successfully.';
        if (!empty($validated['staff_email'])) {
            $message .= ' Office staff account created for ' . $validated['staff_email'] . '.';
        }

        return redirect()
            ->route('admin.offices.index')
            ->with('success', $message);
    }

    public function show($id)
    {
        $row = Office::findOrFail($id);
        return view('admin.offices.show', compact('row'));
    }

    public function edit($id)
    {
        $office= Office::findOrFail($id);
        $municipalities = Municipality::all();
        return view('admin.offices.edit', compact('office', 'municipalities'));
    }

    public function update(Request $request, $id)
    {
        $row = Office::findOrFail($id);
        $validated = $this->validateOffice($request, $row->id);
        $municipality = Municipality::findOrFail($validated['municipality_id']);

        DB::transaction(function () use ($row, $validated, $municipality) {
            $row->update([
                'name' => $validated['name'],
                'address' => $validated['address'],
                'working_hours' => $validated['working_hours'],
                'contact_info' => $validated['contact_info'],
                'municipality_id' => $validated['municipality_id'],
                'google_maps_location' => $municipality->google_maps_location,
                'latitude' => $municipality->latitude,
                'longitude' => $municipality->longitude,
            ]);

            $this->createOfficeStaffIfRequested($validated, $row);
        });

        $message = 'Office updated successfully.';
        if (!empty($validated['staff_email'])) {
            $message .= ' Office staff account created for ' . $validated['staff_email'] . '.';
        }

        return redirect()
            ->route('admin.offices.index')
            ->with('success', $message);
    }

    public function destroy($id)
    {
        $row = Office::findOrFail($id);
        $row->delete();
        return redirect()->route('admin.offices.index');
    }

    private function validateOffice(Request $request, ?int $officeId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'working_hours' => ['required', 'string', 'max:255'],
            'contact_info' => ['required', 'string', 'max:255'],
            'municipality_id' => ['required', 'exists:municipalities,id'],
            'staff_first_name' => ['nullable', 'string', 'max:255', 'required_with:staff_email,staff_password,staff_last_name'],
            'staff_last_name' => ['nullable', 'string', 'max:255', 'required_with:staff_email,staff_password,staff_first_name'],
            'staff_email' => [
                'nullable',
                'email',
                'max:255',
                'required_with:staff_first_name,staff_last_name,staff_password',
                Rule::unique('users', 'email'),
            ],
            'staff_password' => ['nullable', 'string', 'min:8', 'required_with:staff_first_name,staff_last_name,staff_email'],
        ]);
    }

    private function createOfficeStaffIfRequested(array $validated, Office $office): void
    {
        if (empty($validated['staff_email'])) {
            return;
        }

        $officeStaffRole = Role::query()->where('name', 'OfficeStaff')->firstOrFail();

        User::create([
            'firstName' => $validated['staff_first_name'],
            'lastName' => $validated['staff_last_name'],
            'email' => $validated['staff_email'],
            'password' => Hash::make($validated['staff_password']),
            'office_id' => $office->id,
            'role_id' => $officeStaffRole->id,
            'is_active' => true,
            'verified' => true,
            'email_verified_at' => now(),
            'two_factor_authentication' => false,
        ]);
    }
}
