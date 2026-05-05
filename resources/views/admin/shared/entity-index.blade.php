<div class="admin-page">
    @if (session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <section class="admin-page-header">
        <div>
            <h1 class="admin-page-title">{{ $entity['plural'] }}</h1>
            <p class="admin-page-subtitle max-w-3xl">{{ $entity['description'] }}</p>
        </div>

        <x-admin.actions.button :href="route('admin.' . $entity['entity'] . '.create')" variant="green">
            Create New {{ $entity['singular'] }}
        </x-admin.actions.button>
    </section>

    <x-admin.tables.data-table
        :columns="$columns"
        :rows="$rows"
        :route-prefix="'admin.' . $entity['entity']"
        :empty-message="'No ' . strtolower($entity['plural']) . ' available yet.'"
    />
</div>
