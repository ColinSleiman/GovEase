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
                <label for="office_id" class="mb-1 block text-sm font-medium text-slate-700">Office</label>
                <select class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" id="office_id" name="office_id" required>
                    <option value="">Select office</option>
                    @foreach ($offices as $office)
                        <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>
                            {{ $office->name }}
                        </option>
                    @endforeach
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
    const categories = @json($categoriesForJs);
    const services = @json($servicesForJs);

    const oldCategoryId = "{{ old('service_category_id') }}";
    const oldServiceId = "{{ old('service_id') }}";
    const officeInput = document.getElementById('office_id');
    const categoryInput = document.getElementById('service_category_id');
    const serviceInput = document.getElementById('service_id');

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

    officeInput.addEventListener('change', () => {
        refreshCategories();
        refreshServices();
    });
    categoryInput.addEventListener('change', refreshServices);
    refreshCategories();
    refreshServices();
</script>
@endsection
