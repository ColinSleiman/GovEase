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
        <label for="{{ $id }}" class="form-checkbox-card">
            <input
                id="{{ $id }}"
                name="{{ $name }}"
                type="checkbox"
                value="1"
                @checked($isChecked)
                class="form-checkbox-input"
            >
            <span>
                <span class="block text-sm font-medium text-slate-900">{{ $label }}</span>
                @if ($help)
                    <span class="mt-1 block text-xs text-slate-500">{{ $help }}</span>
                @endif
            </span>
        </label>
    @else
        <label for="{{ $id }}" class="form-label">
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
                class="form-control-base"
            >{{ old($name, $value) }}</textarea>
        @elseif ($inputType === 'select')
            <select
                id="{{ $id }}"
                name="{{ $name }}"
                @required($field['required'])
                class="form-control-base"
            >
                <option value="">{{ $placeholder }}</option>
                @foreach ($options as $option)
                    <option value="{{ $option['value'] }}" @selected((string) old($name, $value) === (string) $option['value'])>
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
                class="form-control-base"
            >
        @endif

        @if ($help)
            <p class="form-help-text">{{ $help }}</p>
        @endif
    @endif

    @error($name)
        <p class="form-error-text">{{ $message }}</p>
    @enderror
</div>
