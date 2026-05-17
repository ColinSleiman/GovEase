@extends('layouts.admin')

@section('title', 'Document Reader | GovEase Admin')

@section('content')
    @php
        $typeBadgeClasses = [
            'passport' => 'bg-blue-100 text-blue-700 border-blue-200',
            'national_id' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            'driver_license' => 'bg-purple-100 text-purple-700 border-purple-200',
            'residence_card' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
            'birth_certificate' => 'bg-pink-100 text-pink-700 border-pink-200',
            'civil_document' => 'bg-slate-100 text-slate-700 border-slate-200',
            'unknown' => 'bg-amber-100 text-amber-700 border-amber-200',
        ];
    @endphp

    <div class="admin-page">
        <section class="admin-page-header">
            <div>
                <h1 class="admin-page-title">Document Reader</h1>
                <p class="admin-page-subtitle">
                    Review uploaded PDF and image documents, preview files, and check AI-extracted information.
                </p>
            </div>

        </section>

        <section class="admin-table-wrap">
            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead class="admin-table-head">
                    <tr>
                        <th class="admin-table-th">File Name</th>
                        <th class="admin-table-th">Uploaded By</th>
                        <th class="admin-table-th">Email</th>
                        <th class="admin-table-th">Upload Date</th>
                        <th class="admin-table-th">File Type</th>
                        <th class="admin-table-th">Size</th>
                        <th class="admin-table-th">AI Type</th>
                        <th class="admin-table-th">Confidence</th>
                        <th class="admin-table-th-right">Actions</th>
                    </tr>
                    </thead>

                    <tbody class="admin-table-body">
                    @forelse ($documents as $document)
                        @php
                            $analysis = $document['analysis'] ?? null;
                            $uploader = $document['uploader'] ?? null;
                            $documentType = $analysis['document_type'] ?? 'Not analyzed';
                            $confidence = $analysis['confidence'] ?? 0;
                            $badgeKey = strtolower((string) $documentType);
                            $badgeClass = $typeBadgeClasses[$badgeKey] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                        @endphp

                        <tr class="admin-table-row">
                            <td class="admin-table-td">
                                <div class="font-medium text-slate-900">{{ $document['name'] }}</div>
                            </td>

                            <td class="admin-table-td">
                                <div class="font-medium text-slate-900">
                                    {{ $uploader['full_name'] ?? 'Not recorded' }}
                                </div>

                                @if (!empty($uploader['role']))
                                    <div class="text-xs text-slate-500">
                                        {{ $uploader['role'] }}
                                    </div>
                                @endif
                            </td>

                            <td class="admin-table-td">
                                {{ $uploader['email'] ?? '-' }}
                            </td>

                            <td class="admin-table-td">
                                {{ $uploader['uploaded_at'] ?? '-' }}
                            </td>

                            <td class="admin-table-td">
                                {{ $document['type'] }}
                            </td>

                            <td class="admin-table-td">
                                {{ $document['size'] }}
                            </td>

                            <td class="admin-table-td">
                                    <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold {{ $badgeClass }}">
                                        {{ str_replace('_', ' ', $documentType) }}
                                    </span>
                            </td>

                            <td class="admin-table-td">
                                {{ $confidence }}%
                            </td>

                            <td class="admin-table-actions-cell">
                                <div class="admin-actions">
                                    @if ($document['canPreview'] == true)
                                        @if (!empty($previewDocument) && $previewDocument['name'] == $document['name'])
                                            <x-admin.actions.button :href="route('admin.document.reader')" variant="white" class="btn-xs">
                                                Close Preview
                                            </x-admin.actions.button>
                                        @else
                                            <x-admin.actions.button :href="route('admin.document.reader', ['preview' => $document['name']])" variant="blue" class="btn-xs">
                                                Preview
                                            </x-admin.actions.button>
                                        @endif
                                    @endif

                                    <x-admin.actions.button :href="route('admin.document.reader.download', $document['name'])" variant="white" class="btn-xs">
                                        Download
                                    </x-admin.actions.button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="admin-empty">
                                No documents uploaded yet.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card-padded">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Document Preview</h2>
                    <p class="card-subtitle">
                        Select a document from the table to preview it and view extracted information.
                    </p>
                </div>
            </div>

            @if (!empty($previewDocument))
                @php
                    $analysis = $previewDocument['analysis'] ?? null;
                    $fields = $analysis['fields'] ?? [];
                    $notes = $analysis['notes'] ?? [];
                    $uploader = $previewDocument['uploader'] ?? null;

                    if (!is_array($notes)) {
                        $notes = [$notes];
                    }
                @endphp

                <div class="grid gap-6 xl:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <div class="mb-4">
                            <h3 class="text-base font-semibold text-slate-900">
                                {{ $previewDocument['name'] }}
                            </h3>
                            <p class="mt-1 text-sm text-slate-600">
                                {{ $previewDocument['type'] }} • {{ $previewDocument['size'] }}
                            </p>
                        </div>

                        @if ($previewDocument['extension'] == 'pdf')
                            <iframe
                                src="{{ route('admin.document.reader.preview', $previewDocument['name']) }}"
                                width="100%"
                                height="700"
                                class="rounded-lg border border-slate-300 bg-white">
                            </iframe>
                        @else
                            <div class="flex justify-center">
                                <img
                                    src="{{ route('admin.document.reader.preview', $previewDocument['name']) }}"
                                    style="max-width: 650px; width: 100%; height: auto;"
                                    class="rounded-lg border border-slate-300 bg-white shadow-sm">
                            </div>
                        @endif
                    </div>

                    <div class="space-y-5">
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <h3 class="text-base font-semibold text-slate-900">
                                Uploaded By
                            </h3>

                            @if (!empty($uploader))
                                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Name</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-900">
                                            {{ $uploader['full_name'] ?? 'Not recorded' }}
                                        </p>
                                    </div>

                                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-900">
                                            {{ $uploader['email'] ?? '-' }}
                                        </p>
                                    </div>

                                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Role</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-900">
                                            {{ $uploader['role'] ?? '-' }}
                                        </p>
                                    </div>

                                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Uploaded At</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-900">
                                            {{ $uploader['uploaded_at'] ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            @else
                                <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                                    No uploader information found for this document. Upload the file again to link it to a user.
                                </div>
                            @endif
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <h3 class="text-base font-semibold text-slate-900">
                                AI Extracted Information
                            </h3>

                            @if (!empty($analysis))
                                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-900">
                                            {{ $analysis['status'] ?? 'unknown' }}
                                        </p>
                                    </div>

                                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Document Type</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-900">
                                            {{ str_replace('_', ' ', $analysis['document_type'] ?? 'unknown') }}
                                        </p>
                                    </div>

                                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Confidence</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-900">
                                            {{ $analysis['confidence'] ?? 0 }}%
                                        </p>
                                    </div>
                                </div>

                                @if (!empty($analysis['message']))
                                    <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                        {{ $analysis['message'] }}
                                    </div>
                                @endif

                                <div class="mt-5 overflow-hidden rounded-xl border border-slate-200">
                                    <table class="admin-table">
                                        <thead class="admin-table-head">
                                        <tr>
                                            <th class="admin-table-th">Field</th>
                                            <th class="admin-table-th">Value</th>
                                        </tr>
                                        </thead>

                                        <tbody class="admin-table-body">
                                        @forelse ($fields as $key => $value)
                                            <tr class="admin-table-row">
                                                <td class="admin-table-td font-medium text-slate-900">
                                                    {{ ucwords(str_replace('_', ' ', $key)) }}
                                                </td>
                                                <td class="admin-table-td">
                                                    @if (is_array($value))
                                                        {{ json_encode($value) }}
                                                    @else
                                                        {{ $value ?? 'Not found' }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="admin-empty">
                                                    No fields detected.
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @if (!empty($notes))
                                    <div class="mt-5 rounded-xl border border-slate-200 bg-slate-50 p-4">
                                        <h4 class="text-sm font-semibold text-slate-900">Notes</h4>
                                        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-slate-600">
                                            @foreach ($notes as $note)
                                                @if (!empty($note))
                                                    <li>{{ $note }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @else
                                <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                                    No AI analysis found for this document. Upload the file again to generate analysis.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
                    No file selected for preview.
                </div>
            @endif
        </section>
    </div>
@endsection
