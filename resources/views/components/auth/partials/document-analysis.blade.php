@php
    $fields = $analysis['fields'] ?? [];
    $notes = $analysis['notes'] ?? [];

    if (! is_array($notes)) {
        $notes = [$notes];
    }
@endphp

<div class="mt-3 rounded border bg-light p-3">
    <h6 class="font-weight-bold mb-2">AI Detected Information</h6>

    @if (! empty($analysis['message']))
        <div class="alert alert-warning portal-alert py-2 small mb-2">
            {{ $analysis['message'] }}
        </div>
    @endif

    <div class="row mb-2">
        <div class="col-md-4 mb-2">
            <div class="rounded border bg-white p-2">
                <p class="mb-0 text-muted small text-uppercase">Status</p>
                <p class="mb-0 font-weight-bold">{{ $analysis['status'] ?? 'unknown' }}</p>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="rounded border bg-white p-2">
                <p class="mb-0 text-muted small text-uppercase">Document Type</p>
                <p class="mb-0 font-weight-bold">{{ str_replace('_', ' ', $analysis['document_type'] ?? 'unknown') }}</p>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="rounded border bg-white p-2">
                <p class="mb-0 text-muted small text-uppercase">Confidence</p>
                <p class="mb-0 font-weight-bold">{{ $analysis['confidence'] ?? 0 }}%</p>
            </div>
        </div>
    </div>

    <div class="table-responsive rounded border bg-white">
        <table class="table table-sm mb-0">
            <thead class="thead-light">
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($fields as $key => $value)
                <tr>
                    <td class="font-weight-bold">{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                    <td>
                        @if (is_array($value))
                            {{ json_encode($value) }}
                        @else
                            {{ $value !== '' ? $value : 'Not found' }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-muted text-center">No fields detected.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if (! empty($notes))
        <div class="mt-2 rounded border bg-white p-2">
            <p class="mb-1 font-weight-bold small">Notes</p>
            <ul class="mb-0 pl-3 text-muted small">
                @foreach ($notes as $note)
                    @if (! empty($note))
                        <li>{{ $note }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endif
</div>
