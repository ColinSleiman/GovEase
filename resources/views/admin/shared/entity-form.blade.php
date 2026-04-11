<div class="space-y-6">
    <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $pageTitle }}</h1>
                <p class="mt-2 max-w-3xl text-sm text-slate-600">
                    {{ $pageDescription }}
                </p>
            </div>

            <x-admin.button :href="route('admin.' . $entity['entity'] . '.index')" variant="white">
                Back to {{ $entity['plural'] }}
            </x-admin.button>
        </div>
    </section>

    <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <form action="{{ $formAction }}" method="POST" class="space-y-6">
            @csrf

            @if ($formMethod !== 'POST')
                @method($formMethod)
            @endif

            <div class="grid gap-5 md:grid-cols-2">
                @foreach ($fields as $field)
                    <x-admin.form-field
                        :field="$field"
                        :value="$values[$field['name']] ?? null"
                    />
                @endforeach
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <x-admin.button variant="green" type="submit">
                    {{ $submitLabel }}
                </x-admin.button>

                <x-admin.button :href="route('admin.' . $entity['entity'] . '.index')" variant="white">
                    Cancel
                </x-admin.button>
            </div>
        </form>
    </section>
</div>
