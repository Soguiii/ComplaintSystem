@extends('layouts.appAdmin')
@section('title', 'Admin | Hearing Schedule') 
@section('content')
<div class="container">


<div class="card text-left my-3">
                  <div class="card-body">
                    <h1 class="h3">Upcoming Hearings</h1>
                    <a href="{{ route('admin.hearings.create') }}" class="btn btn-success">Schedule Hearing</a>
                  </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            {{-- Simple calendar view grouped by date --}}
            @php
                $grouped = $hearings->groupBy(function($h) {
                    return $h->scheduled_at ? $h->scheduled_at->format('Y-m-d') : 'unscheduled';
                });
            @endphp

            <div class="mb-4">
                <h5>Calendar</h5>
                <div class="d-flex flex-wrap gap-3">
                    @forelse($grouped as $date => $items)
                        <div class="card p-2" style="min-width:200px; background-color:#e9f7ef; border:1px solid #d4efd8;">
                            <div class="fw-bold text-success mb-1">{{ $date === 'unscheduled' ? 'Unscheduled' : 
                                \Carbon\Carbon::parse($date)->format('M d, Y') }}</div>
                            <ul class="list-unstyled mb-0">
                                @foreach($items as $h)
                                    <li style="padding:6px 0; border-bottom:1px dashed rgba(0,0,0,0.05);">
                                        <div class="small"><strong>{{ $h->scheduled_at ? $h->scheduled_at->format('H:i') : 'TBD' }}</strong> — {{ $h->title }}</div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @empty
                        <div class="text-muted">No scheduled hearings.</div>
                    @endforelse
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Title</th>
                            <th>Complainant</th>
                            <th>Scheduled</th>
                            <th style="width:140px">Status</th>
                            <th style="width:160px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hearings as $h)
                        <tr>
                            <td class="ref-plain">H-{{ sprintf('%04d', $h->id) }}</td>
                            <td>{{ $h->title }}</td>
                            <td>{{ $h->complainant ?? 'N/A' }}</td>
                            <td>{{ $h->scheduled_at ? $h->scheduled_at->format('M d, Y') : 'TBD' }}</td>
                            <td>
                                <div>
                                    <span class="badge-status {{ $h->status }}">{{ ucwords(str_replace('_',' ', $h->status)) }}</span>
                                </div>
                                @if(isset($h->status_changed_at) && $h->status_changed_at)
                                    <div class="small text-muted mt-1">since {{ $h->status_changed_at->format('M d, Y') }} ({{ $h->status_changed_at->diffForHumans() }})</div>
                                @else
                                    <div class="small text-muted mt-1">since {{ $h->created_at->format('M d, Y') }} ({{ $h->created_at->diffForHumans() }})</div>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.hearings.show', $h->id) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="fa fa-eye"></i></a>
                                    <a href="{{ route('admin.hearings.edit', $h->id) }}" class="btn btn-sm btn-outline-success" title="Edit"><i class="fa fa-edit"></i></a>
                                    <form action="{{ route('admin.hearings.destroy', $h->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete hearing?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fa fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No hearings scheduled.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $hearings->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<style>
.ref-plain { font-family: monospace; }
.badge-status { padding: 6px 10px; border-radius: 20px; }
.badge-status.scheduled { background-color: #4dabf7; color: #07263b; }
.badge-status.resolved { background-color: #51cf66; color: #123212; }
.badge-status.cancelled { background-color: #ff6b6b; color: #4b1313; }
</style>

@endsection
