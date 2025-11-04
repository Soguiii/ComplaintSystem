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
                                <span class="badge-status {{ $h->status }}">{{ ucwords(str_replace('_',' ', $h->status)) }}</span>
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
