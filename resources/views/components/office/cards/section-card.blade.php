@props([
    'title',
    'description' => null,
])

<section class="card p-5">
    <div class="mb-4">
        <h3 class="text-base font-semibold text-slate-900">{{ $title }}</h3>
        @if ($description)
            <p class="mt-1 text-sm text-slate-600">{{ $description }}</p>
        @endif
    </div>

    <div class="space-y-2">
        {{ $slot }}
    </div>
</section>
