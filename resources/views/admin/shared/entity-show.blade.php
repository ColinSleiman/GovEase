<div class="admin-page">
    <section class="admin-page-header">
        <div>
            <h1 class="admin-page-title">{{ $entity['singular'] }} Details</h1>
            <p class="admin-page-subtitle max-w-3xl">
                Review the current data stored for this {{ strtolower($entity['singular']) }}.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <x-admin.actions.button :href="route('admin.' . $entity['entity'] . '.edit', $row['__id'])" variant="blue">
                Edit {{ $entity['singular'] }}
            </x-admin.actions.button>
            <x-admin.actions.button :href="route('admin.' . $entity['entity'] . '.index')" variant="white">
                Back to {{ $entity['plural'] }}
            </x-admin.actions.button>
        </div>
    </section>

    <section class="admin-table-wrap">
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
