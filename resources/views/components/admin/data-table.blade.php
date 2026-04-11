@props([
    'columns' => [],
    'rows' => [],
    'routePrefix' => null,
    'emptyMessage' => 'No records found.',
    'showView' => true,
    'showActions' => true,
])

<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    @foreach ($columns as $column)
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            {{ $column['label'] }}
                        </th>
                    @endforeach

                    @if ($showActions)
                        <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Actions
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse ($rows as $row)
                    <tr class="transition hover:bg-slate-50">
                        @foreach ($columns as $column)
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-slate-700">
                                {{ $row[$column['name']] }}
                            </td>
                        @endforeach

                        @if ($showActions && $routePrefix)
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    @if ($showView)
                                        <x-admin.button :href="route($routePrefix . '.show', $row['__id'])" variant="white" class="px-3 py-1.5 text-xs">
                                            View
                                        </x-admin.button>
                                    @endif

                                    <x-admin.button :href="route($routePrefix . '.edit', $row['__id'])" variant="blue" class="px-3 py-1.5 text-xs">
                                        Edit
                                    </x-admin.button>

                                    <form action="{{ route($routePrefix . '.destroy', $row['__id']) }}" method="POST" onsubmit="return confirm('Delete this record?');">
                                        @csrf
                                        @method('DELETE')
                                        <x-admin.button variant="red" type="submit" class="px-3 py-1.5 text-xs">
                                            Delete
                                        </x-admin.button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) + ($showActions ? 1 : 0) }}" class="px-4 py-6 text-center text-sm text-slate-500">
                            {{ $emptyMessage }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
