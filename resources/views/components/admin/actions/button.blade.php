@props([
    'href' => null,
    'variant' => 'slate',
    'type' => 'button',
])

@php
    $variantClasses = match ($variant) {
        'green' => 'btn-variant-green',
        'blue' => 'btn-variant-blue',
        'red' => 'btn-variant-red',
        'white' => 'btn-variant-white',
        default => 'btn-variant-slate',
    };
@endphp

@if ($href)
    <a {{ $attributes->merge(['class' => "btn-base {$variantClasses}"]) }} href="{{ $href }}">
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => "btn-base {$variantClasses}", 'type' => $type]) }}>
        {{ $slot }}
    </button>
@endif
