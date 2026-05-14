<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use Illuminate\Http\Request;

class MunicipalityController extends Controller
{
    public function index()
    {
        $data = Municipality::all();
        return view('admin.municipalities.index', compact('data'));
    }

    public function create()
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $municipalities = Municipality::query()
            ->select('id', 'name', 'region', 'latitude', 'longitude')
            ->get();
        $timeOptions = $this->timeOptions();

        return view('admin.municipalities.create', compact('apiKey', 'municipalities', 'timeOptions'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateMunicipality($request);

        Municipality::create($this->payloadFromValidated($validated));

        return redirect()->route('admin.municipalities.index');
    }

    public function show($id)
    {
        $municipality = Municipality::findOrFail($id);
        return view('admin.municipalities.show', compact('municipality'));
    }

    public function edit($id)
    {
        $municipality = Municipality::findOrFail($id);
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $municipalities = Municipality::query()
            ->select('id', 'name', 'region', 'latitude', 'longitude')
            ->where('id', '!=', $municipality->id)
            ->get();
        $timeOptions = $this->timeOptions();
        [$openingTime, $closingTime] = $this->splitWorkingHours($municipality->working_hours);

        return view('admin.municipalities.edit', compact('municipality', 'apiKey', 'municipalities', 'timeOptions', 'openingTime', 'closingTime'));
    }

    public function update(Request $request, $id)
    {
        $municipality = Municipality::findOrFail($id);
        $validated = $this->validateMunicipality($request);
        $municipality->update($this->payloadFromValidated($validated));
        return redirect()->route('admin.municipalities.index');
    }

    public function destroy($id)
    {
        $municipality = Municipality::findOrFail($id);
        $municipality->delete();
        return redirect()->route('admin.municipalities.index');
    }

    private function validateMunicipality(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'region' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'google_maps_location' => ['nullable', 'string', 'max:255'],
            'opening_time' => ['required', 'date_format:h:i A'],
            'closing_time' => ['required', 'date_format:h:i A', 'different:opening_time'],
            'contact_info' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function payloadFromValidated(array $validated): array
    {
        return [
            'name' => $validated['name'],
            'region' => $validated['region'],
            'address' => $validated['address'] ?? null,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'google_maps_location' => $validated['google_maps_location']
                ?: $validated['latitude'] . ',' . $validated['longitude'],
            'working_hours' => $validated['opening_time'] . ' - ' . $validated['closing_time'],
            'contact_info' => $validated['contact_info'] ?? null,
        ];
    }

    private function splitWorkingHours(?string $workingHours): array
    {
        if (!$workingHours || !str_contains($workingHours, ' - ')) {
            return ['08:00 AM', '05:00 PM'];
        }

        [$openingTime, $closingTime] = explode(' - ', $workingHours, 2);

        return [$openingTime ?: '08:00 AM', $closingTime ?: '05:00 PM'];
    }

    private function timeOptions(): array
    {
        $options = [];
        $time = strtotime('12:00 AM');

        for ($index = 0; $index < 48; $index++) {
            $options[] = date('h:i A', $time);
            $time = strtotime('+30 minutes', $time);
        }

        return $options;
    }
}
