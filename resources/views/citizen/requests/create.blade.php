@extends('layouts.citizen')

@section('title', ($title ?? 'Submit Request') . ' | GovEase')

@section('content')
<div class="citizen-page">
    <section class="card-padded">
        <div class="card-header">
            <div>
                <h1 class="citizen-page-title">Submit a Service Request</h1>
                <p class="citizen-page-subtitle">Fill in request details and upload your required documents.</p>
            </div>
            <a href="{{ route('citizen.requests.index') }}" class="btn-base btn-variant-white">My Requests</a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('citizen.requests.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="municipality_id" class="mb-1 block text-sm font-medium text-slate-700">Municipality</label>
                <select class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" id="municipality_id" name="municipality_id">
                    <option value="">Select municipality from map or dropdown</option>
                    @foreach ($municipalities as $municipality)
                        <option value="{{ $municipality->id }}">
                            {{ $municipality->name }}{{ $municipality->region ? ' (' . $municipality->region . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Municipality Map</label>
                @if ($apiKey)
                    <div id="municipality-map" class="h-80 w-full rounded-lg border border-slate-300"></div>
                    <p class="mt-1 text-xs text-slate-500">Click a marker to select a municipality. The office dropdown will update automatically.</p>
                @else
                    <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                        Google Maps is unavailable because `GOOGLE_MAPS_API_KEY` is not configured.
                    </div>
                @endif
            </div>

            <div>
                <label for="office_id" class="mb-1 block text-sm font-medium text-slate-700">Office</label>
                <select class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" id="office_id" name="office_id" required>
                    <option value="">Select office</option>
                </select>
            </div>

            <div>
                <label for="service_category_id" class="mb-1 block text-sm font-medium text-slate-700">Service Category</label>
                <select class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" id="service_category_id" name="service_category_id" required>
                    <option value="">Select category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('service_category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="service_id" class="mb-1 block text-sm font-medium text-slate-700">Service</label>
                <select class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" id="service_id" name="service_id" required></select>
            </div>

            <div>
                <label for="document_type" class="mb-1 block text-sm font-medium text-slate-700">Document Type</label>
                <input type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" id="document_type" name="document_type" value="{{ old('document_type', 'Request Attachment') }}">
            </div>

            <div>
                <label for="documents" class="mb-1 block text-sm font-medium text-slate-700">Upload Documents</label>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" type="file" id="documents" name="documents[]" multiple required>
                <p class="mt-1 text-xs text-slate-500">Allowed: pdf, jpg, jpeg, png. Max 5MB each.</p>
            </div>

            <div>
                <label for="status_note" class="mb-1 block text-sm font-medium text-slate-700">Initial Note (Optional)</label>
                <textarea class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" id="status_note" name="status_note" rows="3">{{ old('status_note') }}</textarea>
            </div>

            <button type="submit" class="btn-base btn-variant-blue">Submit Request</button>
        </form>
    </section>
</div>

<script>
    const municipalities = @json($municipalitiesForJs);
    const offices = @json($officesForJs);
    const categories = @json($categoriesForJs);
    const services = @json($servicesForJs);

    const oldOfficeId = "{{ old('office_id') }}";
    const oldCategoryId = "{{ old('service_category_id') }}";
    const oldServiceId = "{{ old('service_id') }}";
    const municipalityInput = document.getElementById('municipality_id');
    const officeInput = document.getElementById('office_id');
    const categoryInput = document.getElementById('service_category_id');
    const serviceInput = document.getElementById('service_id');
    const inferredMunicipalityId = (() => {
        const selectedOffice = offices.find(item => String(item.id) === String(oldOfficeId));
        return selectedOffice ? String(selectedOffice.municipality_id) : '';
    })();

    function refreshOffices() {
        const municipalityId = municipalityInput.value;
        let filtered = offices;

        if (municipalityId) {
            filtered = filtered.filter(item => String(item.municipality_id) === String(municipalityId));
        }

        officeInput.innerHTML = '';
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = filtered.length ? 'Select office' : 'No office available';
        officeInput.appendChild(defaultOption);

        filtered.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;
            if (String(item.id) === String(oldOfficeId)) {
                option.selected = true;
            }
            officeInput.appendChild(option);
        });
    }

    function refreshCategories() {
        const officeId = officeInput.value;
        let filtered = categories;

        if (officeId) {
            filtered = filtered.filter(item => String(item.office_id) === String(officeId));
        }

        categoryInput.innerHTML = '';
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = filtered.length ? 'Select category' : 'No category available';
        categoryInput.appendChild(defaultOption);

        filtered.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;
            if (String(item.id) === String(oldCategoryId)) {
                option.selected = true;
            }
            categoryInput.appendChild(option);
        });
    }

    function refreshServices() {
        const officeId = officeInput.value;
        const categoryId = categoryInput.value;

        let filtered = services;
        if (officeId) {
            filtered = filtered.filter(item => String(item.office_id) === String(officeId));
        }
        if (categoryId) {
            filtered = filtered.filter(item => String(item.service_category_id) === String(categoryId));
        }

        serviceInput.innerHTML = '';
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = filtered.length ? 'Select service' : 'No service available';
        serviceInput.appendChild(defaultOption);

        filtered.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name + ' (' + (item.office_name || '-') + ' / ' + (item.category_name || '-') + ')';
            if (String(item.id) === String(oldServiceId)) {
                option.selected = true;
            }
            serviceInput.appendChild(option);
        });
    }

    function selectMunicipality(municipalityId) {
        municipalityInput.value = municipalityId ? String(municipalityId) : '';
        refreshOffices();
        refreshCategories();
        refreshServices();
    }

    if (inferredMunicipalityId) {
        municipalityInput.value = inferredMunicipalityId;
    }

    refreshOffices();
    officeInput.addEventListener('change', () => {
        refreshCategories();
        refreshServices();
    });
    municipalityInput.addEventListener('change', () => {
        refreshOffices();
        refreshCategories();
        refreshServices();
    });
    categoryInput.addEventListener('change', refreshServices);
    refreshCategories();
    refreshServices();

    @if ($apiKey)
        function initCitizenMunicipalityMap() {
            const defaultLocation = { lat: 33.8938, lng: 35.5018 };
            const map = new google.maps.Map(document.getElementById('municipality-map'), {
                center: defaultLocation,
                zoom: 10,
            });
            const infoWindow = new google.maps.InfoWindow();
            const bounds = new google.maps.LatLngBounds();

            municipalities.forEach((municipality) => {
                const position = {
                    lat: Number(municipality.latitude),
                    lng: Number(municipality.longitude),
                };

                if (Number.isNaN(position.lat) || Number.isNaN(position.lng)) {
                    return;
                }

                const marker = new google.maps.Marker({
                    position,
                    map,
                    title: municipality.name,
                });

                marker.addListener('click', () => {
                    selectMunicipality(municipality.id);
                    infoWindow.setContent(
                        `<div class="text-sm"><strong>${municipality.name}</strong><br>${municipality.region ?? ''}<br>${municipality.address ?? ''}</div>`
                    );
                    infoWindow.open({
                        anchor: marker,
                        map,
                    });
                    map.panTo(position);
                });

                bounds.extend(position);
            });

            if (!bounds.isEmpty()) {
                map.fitBounds(bounds);
            }
        }

        window.initCitizenMunicipalityMap = initCitizenMunicipalityMap;
    @endif
</script>
@if ($apiKey)
    <script src="https://maps.googleapis.com/maps/api/js?key={{ $apiKey }}&callback=initCitizenMunicipalityMap" async defer></script>
@endif
@endsection
