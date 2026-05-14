@extends('layouts.office')

@section('title', 'Edit Appointment | GovEase Office')

@section('content')
    <div class="admin-page">
        <section class="admin-page-header">
            <div>
                <h1 class="admin-page-title">Edit Appointment</h1>
                <p class="admin-page-subtitle">Adjust the visit date, time slot, service, or appointment status.</p>
            </div>
            <x-office.actions.button :href="route('office.appointments.index')" variant="white">Back to Appointments</x-office.actions.button>
        </section>

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="card-padded">
            <form action="{{ route('office.appointments.update', $row->id) }}" method="POST" class="grid gap-4 md:grid-cols-2">
                @csrf
                @method('PUT')

                <div>
                    <label class="form-label">Service</label>
                    <select name="service_id" class="form-control-base" required>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" @selected((string) old('service_id', $row->service_id) === (string) $service->id)>{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Status</label>
                    <select name="status_id" class="form-control-base" required>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}" @selected((string) old('status_id', $row->status_id) === (string) $status->id)>{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Appointment Date</label>
                    <input type="date" id="appointment_date" name="appointment_date" min="{{ now()->toDateString() }}" value="{{ old('appointment_date', $row->appointment_date) }}" class="form-control-base" required>
                </div>

                <div>
                    <label class="form-label">Appointment Time</label>
                    <select id="appointment_time" name="appointment_time" class="form-control-base" required>
                        @foreach ($slots as $slot)
                            <option value="{{ $slot['value'] }}" @selected((string) old('appointment_time', $row->appointment_time) === (string) $slot['value'])>{{ $slot['label'] }}</option>
                        @endforeach
                        @if (!collect($slots)->contains(fn ($slot) => (string) $slot['value'] === (string) old('appointment_time', $row->appointment_time)))
                            <option value="{{ old('appointment_time', $row->appointment_time) }}" selected>
                                {{ \Carbon\Carbon::parse(old('appointment_time', $row->appointment_time))->format('h:i A') }}
                            </option>
                        @endif
                    </select>
                </div>

                <div class="md:col-span-2">
                    <x-office.actions.button variant="blue" type="submit">Save Appointment</x-office.actions.button>
                </div>
            </form>
        </section>
    </div>

    <script>
        const appointmentDateInput = document.getElementById('appointment_date');
        const appointmentTimeInput = document.getElementById('appointment_time');

        async function refreshSlots() {
            if (!appointmentDateInput.value) {
                return;
            }

            const url = new URL(@json(route('office.appointments.slots')), window.location.origin);
            url.searchParams.set('appointment_date', appointmentDateInput.value);
            url.searchParams.set('ignore_appointment_id', @json($row->id));

            const response = await fetch(url, { headers: { Accept: 'application/json' } });
            if (!response.ok) {
                return;
            }

            const payload = await response.json();
            const currentValue = @json(old('appointment_time', $row->appointment_time));
            appointmentTimeInput.innerHTML = '';

            payload.slots.forEach(slot => {
                const option = document.createElement('option');
                option.value = slot.value;
                option.textContent = slot.label;
                if (String(slot.value) === String(currentValue)) {
                    option.selected = true;
                }
                appointmentTimeInput.appendChild(option);
            });
        }

        appointmentDateInput.addEventListener('change', refreshSlots);
    </script>
@endsection
