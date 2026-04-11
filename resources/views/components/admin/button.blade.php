@props([
    'href' => null,
    'variant' => 'slate',
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-offset-2';
    $variantClasses = match ($variant) {
        'green' => 'bg-emerald-600 text-white hover:bg-emerald-700 focus:ring-emerald-500',
        'blue' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
        'red' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        'white' => 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 focus:ring-slate-400',
        default => 'bg-slate-800 text-white hover:bg-slate-900 focus:ring-slate-500',
    };
@endphp

@if ($href)
    <a {{ $attributes->merge(['class' => "{$baseClasses} {$variantClasses}"]) }} href="{{ $href }}">
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => "{$baseClasses} {$variantClasses}", 'type' => $type]) }}>
        {{ $slot }}
    </button>
@endif
