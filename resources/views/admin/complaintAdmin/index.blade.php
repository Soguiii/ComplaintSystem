@extends('layouts.appAdmin')
@section('title', 'Admin | Complaints') 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">{{ __('Active Complaints') }}</h2>
                    <div class="d-flex gap-2">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Filter Status
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                                <li><a class="dropdown-item" href="{{ route('admin.complaints', ['status' => 'all']) }}">All Active</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.complaints', ['status' => 'pending']) }}">Pending</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.complaints', ['status' => 'in_progress']) }}">In Progress</a></li>
                            </ul>
                        </div>
                        <form class="d-flex" action="{{ route('admin.complaints') }}" method="GET">
                            <input type="text" name="search" class="form-control me-2" placeholder="Search complaints..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-outline-primary">Search</button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width:130px">Reference</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th style="width:120px">Status</th>
                                    <th style="width:120px">Date Filed</th>
                                    <th style="width:180px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($complaints as $complaint)
                                    <tr>
                                        <td class="ref-plain">{{ $complaint->reference }}</td>
                                        <td>{{ $complaint->first_name }} {{ $complaint->last_name }}</td>
                                        <td>{{ $complaint->type ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                $st = $complaint->status ?? 'pending';
                                                $displayClass = $st === 'closed' ? 'rejected' : $st;
                                                $displayLabel = $st === 'closed' ? 'Rejected' : ucwords(str_replace('_', ' ', $st));
                                            @endphp
                                            <span class="badge-status {{ $displayClass }}">{{ $displayLabel }}</span>
                                        </td>
                                        <td>{{ $complaint->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.complaints.show', $complaint->id) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="View Details">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.complaints.edit', $complaint->id) }}" 
                                                   class="btn btn-sm btn-outline-success" 
                                                   title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.complaints.destroy', $complaint->id) }}" 
                                                      method="POST" 
                                                      class="d-inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this complaint?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fa fa-folder-open me-2"></i>No active complaints found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $complaints->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.badge-status {
    padding: 0.5em 0.8em;
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 500;
    text-transform: capitalize;
}

.badge-status.pending {
    background-color: #ffd43b;
    color: #664d03;
}

.badge-status.in_progress {
    background-color: #4dabf7;
    color: #0a416d;
}

.badge-status.resolved {
    background-color: #51cf66;
    color: #1b4724;
}

.badge-status.rejected {
    background-color: #ff6b6b;
    color: #6d1a1a;
}

.btn-group .btn {
    border-radius: 4px;
    margin: 0 2px;
}

.ref-plain {
    font-family: monospace;
    font-size: 0.9rem;
}

.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
}

/* Pagination styling */
.pagination {
    margin-bottom: 0;
}

.page-item.active .page-link {
    background-color: #28a745;
    border-color: #28a745;
}

.page-link {
    color: #28a745;
}

.page-link:hover {
    color: #1e7e34;
}

.page-item.disabled .page-link {
    color: #6c757d;
}
</style>
@endsection
