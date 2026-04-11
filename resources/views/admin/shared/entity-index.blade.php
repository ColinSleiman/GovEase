<div class="space-y-6">
    @if (session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <section class="flex flex-col gap-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ $entity['plural'] }}</h1>
            <p class="mt-2 max-w-3xl text-sm text-slate-600">{{ $entity['description'] }}</p>
        </div>

        <x-admin.button :href="route('admin.' . $entity['entity'] . '.create')" variant="green">
            Create New {{ $entity['singular'] }}
        </x-admin.button>
    </section>

    <x-admin.data-table
        :columns="$columns"
        :rows="$rows"
        :route-prefix="'admin.' . $entity['entity']"
        :empty-message="'No ' . strtolower($entity['plural']) . ' available yet.'"
    />
</div>
