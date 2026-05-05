<div class="admin-page">
    <section class="card-padded">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="admin-page-title">{{ $pageTitle }}</h1>
                <p class="admin-page-subtitle max-w-3xl">
                    {{ $pageDescription }}
                </p>
            </div>

            <x-admin.actions.button :href="route('admin.' . $entity['entity'] . '.index')" variant="white">
                Back to {{ $entity['plural'] }}
            </x-admin.actions.button>
        </div>
    </section>

    <section class="card-padded">
        <form action="{{ $formAction }}" method="POST" class="space-y-6">
            @csrf

            @if ($formMethod !== 'POST')
                @method($formMethod)
            @endif

            <div class="admin-form-grid">
                @foreach ($fields as $field)
                    <x-admin.forms.form-field
                        :field="$field"
                        :value="$values[$field['name']] ?? null"
                    />
                @endforeach
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <x-admin.actions.button variant="green" type="submit">
                    {{ $submitLabel }}
                </x-admin.actions.button>

                <x-admin.actions.button :href="route('admin.' . $entity['entity'] . '.index')" variant="white">
                    Cancel
                </x-admin.actions.button>
            </div>
        </form>
    </section>
</div>
