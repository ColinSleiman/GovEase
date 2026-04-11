@props([
    'field',
    'value' => null,
])

@php
    $name = $field['name'];
    $id = 'field_' . $name;
    $label = $field['label'];
    $inputType = $field['input_type'];
    $placeholder = $field['placeholder'] ?? null;
    $help = $field['help'] ?? null;
    $options = $field['options'] ?? [];
    $columnSpan = $field['column_span'] ?? 'md:col-span-1';
    $isChecked = old($name, $value) ? true : false;
@endphp

<div class="{{ $columnSpan }}">
    @if ($inputType === 'checkbox')
        <label for="{{ $id }}" class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
            <input
                id="{{ $id }}"
                name="{{ $name }}"
                type="checkbox"
                value="1"
                @checked($isChecked)
                class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
            >
            <span>
                <span class="block text-sm font-medium text-slate-900">{{ $label }}</span>
                @if ($help)
                    <span class="mt-1 block text-xs text-slate-500">{{ $help }}</span>
                @endif
            </span>
        </label>
    @else
        <label for="{{ $id }}" class="mb-2 block text-sm font-medium text-slate-700">
            {{ $label }}
            @if ($field['required'])
                <span class="text-red-500">*</span>
            @endif
        </label>

        @if ($inputType === 'textarea')
            <textarea
                id="{{ $id }}"
                name="{{ $name }}"
                rows="4"
                placeholder="{{ $placeholder }}"
                @required($field['required'])
                class="block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
            >{{ old($name, $value) }}</textarea>
        @elseif ($inputType === 'select')
            <select
                id="{{ $id }}"
                name="{{ $name }}"
                @required($field['required'])
                class="block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
            >
                <option value="">{{ $placeholder }}</option>
                @foreach ($options as $option)
                    <option value="{{ $option['value'] }}" @selected((string) old($name, $value) === (string) $option['value']>
                        {{ $option['label'] }}
                    </option>
                @endforeach
            </select>
        @else
            <input
                id="{{ $id }}"
                name="{{ $name }}"
                type="{{ $inputType }}"
                value="{{ $inputType === 'password' ? '' : old($name, $value) }}"
                placeholder="{{ $placeholder }}"
                step="{{ $field['step'] ?? null }}"
                @required($field['required'])
                class="block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
            >
        @endif

        @if ($help)
            <p class="mt-2 text-xs text-slate-500">{{ $help }}</p>
        @endif
    @endif

    @error($name)
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
