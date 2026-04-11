@extends('layouts.admin')

@section('title', 'Create Office | GovEase Admin')

@section('content')
<div class="space-y-6">

    <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-bold text-slate-900">Create Office</h1>
        <p class="mt-2 text-sm text-slate-600">Add a new office record.</p>
    </section>

    <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <form action="{{ route('admin.offices.store') }}" method="POST" class="grid gap-5 md:grid-cols-2">
            @csrf

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Address</label>
                <input type="text" name="address" value="{{ old('address') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Google Maps Location</label>
                <input type="text" name="google_maps_location" value="{{ old('google_maps_location') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Select Google Maps Location
                </label>

                <div id="map" class="w-full h-80 rounded-lg border"></div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Working Hours</label>
                <input type="text" name="working_hours" value="{{ old('working_hours') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Latitude</label>
                <input type="number" step="any" id="latitude" name="latitude"
                    value="{{ old('latitude') }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Longitude</label>
                <input type="number" step="any" id="longitude" name="longitude"
                    value="{{ old('longitude') }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Contact Info</label>
                <input type="text" name="contact_info" value="{{ old('contact_info') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Municipality</label>

                <select name="municipality_id" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm">

                    <option value="">Select Municipality</option>

                    @foreach($data as $municipality)
                        <option value="{{ $municipality->id }}"
                            {{ old('municipality_id') == $municipality->id ? 'selected' : '' }}>
                            {{ $municipality->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="md:col-span-2 flex flex-wrap gap-3">
                <x-admin.button variant="green" type="submit">Create Office</x-admin.button>
                <x-admin.button :href="route('admin.offices.index')" variant="white">Cancel</x-admin.button>
            </div>

        </form>

    </section>
    
    <script>
        function initMap() {

            const defaultLocation = { lat: 33.8938, lng: 35.5018 };
            const geocoder = new google.maps.Geocoder();

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12,
                center: defaultLocation,
            });

            const marker = new google.maps.Marker({
                position: defaultLocation,
                map: map,
                draggable: true,
            });

            function update(lat, lng) {
                document.getElementById("latitude").value = lat;
                document.getElementById("longitude").value = lng;
            }

            marker.addListener("dragend", () => {
                const pos = marker.getPosition();
                update(pos.lat(), pos.lng());
            });

            map.addListener("click", (e) => {

                const latLng = e.latLng;
                marker.setPosition(latLng);

                const lat = latLng.lat();
                const lng = latLng.lng();

                update(lat, lng);

                // Reverse geocode
                geocoder.geocode({ location: latLng }, (results, status) => {
                    if (status === "OK" && results[0]) {

                        const place = results[0];

                        // Fill address
                        const addressInput = document.querySelector('[name="address"]');
                        if (addressInput) {
                            addressInput.value = place.formatted_address;
                        }

                        // Fill Google Maps location (optional field)
                        const gmapsInput = document.querySelector('[name="google_maps_location"]');
                        if (gmapsInput) {
                            gmapsInput.value = place.place_id || place.formatted_address;
                        }
                    }
                });
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ $apiKey }}&callback=initMap" async defer></script>
</div>
@endsection
