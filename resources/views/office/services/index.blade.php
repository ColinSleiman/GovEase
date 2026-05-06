@extends('layouts.office')

@section('title', 'Services | GovEase Office')

@section('content')
    <div class="admin-page">
        <section class="admin-page-header">
            <div>
                <h1 class="admin-page-title">Services</h1>
                <p class="admin-page-subtitle">Manage service offerings for your office.</p>
            </div>
            <x-office.actions.button :href="route('office.services.create')" variant="green">Create Service</x-office.actions.button>
        </section>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <section class="admin-table-wrap">
            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead class="admin-table-head">
                        <tr>
                            <th class="admin-table-th">ID</th>
                            <th class="admin-table-th">Name</th>
                            <th class="admin-table-th">Category</th>
                            <th class="admin-table-th">Price</th>
                            <th class="admin-table-th">Duration</th>
                            <th class="admin-table-th">Created</th>
                            <th class="admin-table-th-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="admin-table-body">
                        @forelse ($data as $row)
                            <tr class="admin-table-row">
                                <td class="admin-table-td">{{ $row->id }}</td>
                                <td class="admin-table-td">{{ $row->name }}</td>
                                <td class="admin-table-td">{{ $row->serviceCategory?->name ?? '-' }}</td>
                                <td class="admin-table-td">{{ $row->price }}</td>
                                <td class="admin-table-td">{{ $row->duration }} mins</td>
                                <td class="admin-table-td">{{ $row->created_at?->format('M d, Y') }}</td>
                                <td class="admin-table-actions-cell">
                                    <div class="admin-actions">
                                        <x-office.actions.button :href="route('office.services.show', $row->id)" variant="white" class="btn-xs">View</x-office.actions.button>
                                        <x-office.actions.button :href="route('office.services.edit', $row->id)" variant="blue" class="btn-xs">Edit</x-office.actions.button>
                                        <form action="{{ route('office.services.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Delete this service?');">
                                            @csrf
                                            @method('DELETE')
                                            <x-office.actions.button variant="red" type="submit" class="btn-xs">Delete</x-office.actions.button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="admin-empty">No services found for your office.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="card-padded">
            {{ $data->links() }}
        </div>
    </div>
@endsection
