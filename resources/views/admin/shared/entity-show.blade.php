<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ $entity['singular'] }} Details</h1>
            <p class="mt-2 max-w-3xl text-sm text-slate-600">
                Review the current data stored for this {{ strtolower($entity['singular']) }}.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <x-admin.button :href="route('admin.' . $entity['entity'] . '.edit', $row['__id'])" variant="blue">
                Edit {{ $entity['singular'] }}
            </x-admin.button>
            <x-admin.button :href="route('admin.' . $entity['entity'] . '.index')" variant="white">
                Back to {{ $entity['plural'] }}
            </x-admin.button>
        </div>
    </section>

    <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <dl class="divide-y divide-slate-200">
            @foreach ($columns as $column)
                <div class="grid gap-2 px-6 py-4 md:grid-cols-[220px,1fr] md:gap-6">
                    <dt class="text-sm font-semibold text-slate-600">{{ $column['label'] }}</dt>
                    <dd class="text-sm text-slate-900">{{ $row[$column['name']] }}</dd>
                </div>
            @endforeach
        </dl>
    </section>
</div>
