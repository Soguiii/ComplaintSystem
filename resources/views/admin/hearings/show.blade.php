@extends('layouts.appAdmin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3 class="mb-0">Hearing Details</h3></div>
                <div class="card-body">
                    <p><strong>Title:</strong> {{ $hearing->title }}</p>
                    <p><strong>Type:</strong> {{ $hearing->type ?? 'N/A' }}</p>
                    <p><strong>Complainant:</strong> {{ $hearing->complainant ?? 'N/A' }}</p>
                    <p><strong>Contact:</strong> {{ $hearing->contact ?? 'N/A' }}</p>
                    <p><strong>Scheduled At:</strong> {{ $hearing->scheduled_at ? $hearing->scheduled_at->format('M d, Y H:i') : 'TBD' }}</p>
                    <p><strong>Status:</strong> <span class="badge-status {{ $hearing->status }}">{{ ucwords($hearing->status) }}</span></p>
                    <hr>
                    <p>{{ $hearing->details }}</p>
                    <div class="mt-3">
                        <a href="{{ route('admin.hearings.edit', $hearing->id) }}" class="btn btn-success">Edit</a>
                        <a href="{{ route('admin.hearings.index') }}" class="btn btn-secondary ms-2">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
