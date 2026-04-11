@extends('layouts.admin')

@section('title', 'Create Municipality | GovEase Admin')

@section('content')
    <div class="space-y-6">
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="text-2xl font-bold text-slate-900">Create Municipality</h1>
            <p class="mt-2 text-sm text-slate-600">Add a new municipality record.</p>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form action="{{ route('admin.municipalities.store') }}" method="POST" class="grid gap-5 md:grid-cols-2">
                @csrf

                @if ($errors->any())
                    <div class="md:col-span-2 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        <ul class="list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Name</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Region</label>
                    <input
                        type="text"
                        name="region"
                        value="{{ old('region') }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm"
                    >
                    @error('region')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Address</label>
                    <input
                        type="text"
                        id="address"
                        name="address"
                        value="{{ old('address') }}"
                        readonly
                        class="w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600"
                    >
                    <p class="mt-1 text-xs text-slate-500">Filled automatically from the selected map location when available.</p>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Google Maps Location</label>
                    <input
                        type="text"
                        id="google_maps_location"
                        name="google_maps_location"
                        value="{{ old('google_maps_location') }}"
                        readonly
                        class="w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600"
                    >
                    @error('google_maps_location')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-slate-700">Select Municipality Location</label>

                    @if ($apiKey)
                        <div id="municipality-map" class="h-80 w-full rounded-lg border border-slate-300"></div>
                        <p class="mt-2 text-xs text-slate-500">Click the map to place one marker, then drag it to fine-tune the coordinates.</p>
                    @else
                        <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                            Google Maps is unavailable because `GOOGLE_MAPS_API_KEY` is not configured.
                        </div>
                    @endif
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Latitude</label>
                    <input
                        type="number"
                        step="any"
                        id="latitude"
                        name="latitude"
                        value="{{ old('latitude') }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm"
                    >
                    @error('latitude')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Longitude</label>
                    <input
                        type="number"
                        step="any"
                        id="longitude"
                        name="longitude"
                        value="{{ old('longitude') }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm"
                    >
                    @error('longitude')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Working Hours</label>
                    <input
                        type="text"
                        name="working_hours"
                        value="{{ old('working_hours') }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm"
                    >
                    @error('working_hours')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Contact Info</label>
                    <input
                        type="text"
                        name="contact_info"
                        value="{{ old('contact_info') }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm"
                    >
                    @error('contact_info')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2 flex flex-wrap gap-3">
                    <x-admin.button variant="green" type="submit">Create Municipality</x-admin.button>
                    <x-admin.button :href="route('admin.municipalities.index')" variant="white">Cancel</x-admin.button>
                </div>
            </form>
        </section>
    </div>

    @if ($apiKey)
        <script>
            function initMap() {
                const defaultLocation = { lat: 33.8938, lng: 35.5018 };
                const latitudeInput = document.getElementById('latitude');
                const longitudeInput = document.getElementById('longitude');
                const addressInput = document.getElementById('address');
                const googleMapsLocationInput = document.getElementById('google_maps_location');
                const existingMunicipalities = @json($municipalities);
                const infoWindow = new google.maps.InfoWindow();
                const geocoder = new google.maps.Geocoder();

                const map = new google.maps.Map(document.getElementById('municipality-map'), {
                    center: defaultLocation,
                    zoom: 12,
                });

                const bounds = new google.maps.LatLngBounds();
                let marker = null;
                let geocodeRequestId = 0;

                function updateCoordinates(lat, lng) {
                    latitudeInput.value = lat;
                    longitudeInput.value = lng;
                }

                function updateLocationFields(position) {
                    const latitude = position.lat();
                    const longitude = position.lng();
                    const requestId = ++geocodeRequestId;

                    updateCoordinates(latitude, longitude);

                    if (googleMapsLocationInput) {
                        googleMapsLocationInput.value = `${latitude},${longitude}`;
                    }

                    geocoder.geocode({ location: position }, (results, status) => {
                        if (requestId !== geocodeRequestId) {
                            return;
                        }

                        if (status !== 'OK' || !results || !results.length) {
                            if (addressInput) {
                                addressInput.value = '';
                            }

                            if (googleMapsLocationInput) {
                                googleMapsLocationInput.value = `${latitude},${longitude}`;
                            }

                            return;
                        }

                        const bestMatch = results[0];

                        if (addressInput) {
                            addressInput.value = bestMatch.formatted_address ?? '';
                        }

                        if (googleMapsLocationInput) {
                            googleMapsLocationInput.value = bestMatch.place_id ?? bestMatch.formatted_address ?? `${latitude},${longitude}`;
                        }
                    });
                }

                function placeMarker(position) {
                    if (!marker) {
                        marker = new google.maps.Marker({
                            position,
                            map,
                            draggable: true,
                            icon: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                        });

                        marker.addListener('dragend', () => {
                            const draggedPosition = marker.getPosition();

                            if (!draggedPosition) {
                                return;
                            }

                            updateLocationFields(draggedPosition);
                        });
                    } else {
                        marker.setPosition(position);
                    }

                    updateLocationFields(position);
                }

                existingMunicipalities.forEach((municipality) => {
                    if (municipality.latitude === null || municipality.longitude === null) {
                        return;
                    }

                    const position = {
                        lat: Number(municipality.latitude),
                        lng: Number(municipality.longitude),
                    };

                    if (Number.isNaN(position.lat) || Number.isNaN(position.lng)) {
                        return;
                    }

                    const existingMarker = new google.maps.Marker({
                        position,
                        map,
                        draggable: false,
                        title: `${municipality.name} - ${municipality.region ?? ''}`,
                        icon: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
                    });

                    existingMarker.addListener('click', () => {
                        infoWindow.setContent(
                            `<div class="text-sm"><strong>${municipality.name}</strong><br>${municipality.region ?? ''}</div>`
                        );
                        infoWindow.open({
                            anchor: existingMarker,
                            map,
                        });
                    });

                    bounds.extend(position);
                });

                if (!Number.isNaN(Number(latitudeInput.value)) && !Number.isNaN(Number(longitudeInput.value))
                    && latitudeInput.value !== '' && longitudeInput.value !== '') {
                    const currentPosition = {
                        lat: Number(latitudeInput.value),
                        lng: Number(longitudeInput.value),
                    };

                    placeMarker(currentPosition);
                    bounds.extend(currentPosition);
                }

                if (!bounds.isEmpty()) {
                    map.fitBounds(bounds);
                }

                map.addListener('click', (event) => {
                    placeMarker(event.latLng);
                });
            }

            window.initMap = initMap;
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key={{ $apiKey }}&callback=initMap" async defer></script>
    @endif
@endsection
