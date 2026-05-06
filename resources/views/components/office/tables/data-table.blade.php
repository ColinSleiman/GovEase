@props([
    'columns' => [],
    'rows' => [],
    'routePrefix' => null,
    'emptyMessage' => 'No records found.',
    'showView' => true,
    'showActions' => true,
])

<div class="admin-table-wrap">
    <div class="admin-table-scroll">
        <table class="admin-table">
            <thead class="admin-table-head">
                <tr>
                    @foreach ($columns as $column)
                        <th scope="col" class="admin-table-th">
                            {{ $column['label'] }}
                        </th>
                    @endforeach

                    @if ($showActions)
                        <th scope="col" class="admin-table-th-right">
                            Actions
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody class="admin-table-body">
                @forelse ($rows as $row)
                    <tr class="transition admin-table-row">
                        @foreach ($columns as $column)
                            <td class="admin-table-td whitespace-nowrap">
                                {{ $row[$column['name']] }}
                            </td>
                        @endforeach

                        @if ($showActions && $routePrefix)
                            <td class="admin-table-actions-cell">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    @if ($showView)
                                        <x-office.actions.button :href="route($routePrefix . '.show', $row['__id'])" variant="white" class="btn-xs">
                                            View
                                        </x-office.actions.button>
                                    @endif

                                    <x-office.actions.button :href="route($routePrefix . '.edit', $row['__id'])" variant="blue" class="btn-xs">
                                        Edit
                                    </x-office.actions.button>

                                    <form action="{{ route($routePrefix . '.destroy', $row['__id']) }}" method="POST" onsubmit="return confirm('Delete this record?');">
                                        @csrf
                                        @method('DELETE')
                                        <x-office.actions.button variant="red" type="submit" class="btn-xs">
                                            Delete
                                        </x-office.actions.button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) + ($showActions ? 1 : 0) }}" class="admin-empty">
                            {{ $emptyMessage }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
