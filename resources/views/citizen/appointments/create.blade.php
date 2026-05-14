@extends('layouts.citizen')

@section('title', 'Book Appointment | GovEase')

@section('content')
    <div class="citizen-page">
        <section class="card-padded">
            <div class="card-header">
                <div>
                    <h1 class="citizen-page-title">Book In-Person Appointment</h1>
                    <p class="citizen-page-subtitle">Choose a municipality, office, service, and available time slot for your physical visit.</p>
                </div>
                <a href="{{ route('citizen.appointments.index') }}" class="btn-base btn-variant-white">My Appointments</a>
            </div>

            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('citizen.appointments.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="municipality_id" class="mb-1 block text-sm font-medium text-slate-700">Municipality</label>
                    <select id="municipality_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="">Select municipality</option>
                        @foreach ($municipalities as $municipality)
                            <option value="{{ $municipality->id }}">{{ $municipality->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="office_id" class="mb-1 block text-sm font-medium text-slate-700">Office</label>
                    <select id="office_id" name="office_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                        <option value="">Select office</option>
                    </select>
                </div>

                <div>
                    <label for="service_id" class="mb-1 block text-sm font-medium text-slate-700">Service</label>
                    <select id="service_id" name="service_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                        <option value="">Select service</option>
                    </select>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="appointment_date" class="mb-1 block text-sm font-medium text-slate-700">Appointment Date</label>
                        <input type="date" id="appointment_date" name="appointment_date" min="{{ now()->toDateString() }}" value="{{ old('appointment_date') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                    </div>

                    <div>
                        <label for="appointment_time" class="mb-1 block text-sm font-medium text-slate-700">Available Time Slot</label>
                        <select id="appointment_time" name="appointment_time" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                            <option value="">Select time slot</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-base btn-variant-blue">Book Appointment</button>
            </form>
        </section>
    </div>

    <script>
        const offices = @json($officesForJs);
        const services = @json($servicesForJs);
        const municipalityInput = document.getElementById('municipality_id');
        const officeInput = document.getElementById('office_id');
        const serviceInput = document.getElementById('service_id');
        const appointmentDateInput = document.getElementById('appointment_date');
        const appointmentTimeInput = document.getElementById('appointment_time');

        function refreshOffices() {
            const municipalityId = municipalityInput.value;
            const filtered = municipalityId
                ? offices.filter(item => String(item.municipality_id) === String(municipalityId))
                : [];

            officeInput.innerHTML = '<option value="">Select office</option>';
            filtered.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                officeInput.appendChild(option);
            });

            refreshServices();
            resetSlots();
        }

        function refreshServices() {
            const officeId = officeInput.value;
            const filtered = officeId
                ? services.filter(item => String(item.office_id) === String(officeId))
                : [];

            serviceInput.innerHTML = '<option value="">Select service</option>';
            filtered.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.category_name ? `${item.name} (${item.category_name})` : item.name;
                serviceInput.appendChild(option);
            });
        }

        function resetSlots() {
            appointmentTimeInput.innerHTML = '<option value="">Select time slot</option>';
        }

        async function refreshSlots() {
            const officeId = officeInput.value;
            const appointmentDate = appointmentDateInput.value;

            resetSlots();

            if (!officeId || !appointmentDate) {
                return;
            }

            const url = new URL(@json(route('citizen.appointments.slots')), window.location.origin);
            url.searchParams.set('office_id', officeId);
            url.searchParams.set('appointment_date', appointmentDate);

            const response = await fetch(url, { headers: { Accept: 'application/json' } });
            if (!response.ok) {
                return;
            }

            const payload = await response.json();
            if (!payload.slots.length) {
                appointmentTimeInput.innerHTML = '<option value="">No available slots</option>';
                return;
            }

            payload.slots.forEach(slot => {
                const option = document.createElement('option');
                option.value = slot.value;
                option.textContent = slot.label;
                appointmentTimeInput.appendChild(option);
            });
        }

        municipalityInput.addEventListener('change', refreshOffices);
        officeInput.addEventListener('change', () => {
            refreshServices();
            refreshSlots();
        });
        appointmentDateInput.addEventListener('change', refreshSlots);
    </script>
@endsection
