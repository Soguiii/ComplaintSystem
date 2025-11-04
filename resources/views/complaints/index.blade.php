@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">{{ __('Complaints List') }}</h2>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Date Filed</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($complaints as $complaint)
                                    <tr>
                                        <td>{{ $complaint->id }}</td>
                                        <td>{{ $complaint->first_name }} {{ $complaint->middle_name }} {{ $complaint->last_name }}</td>
                                        <td>{{ $complaint->contact }}</td>
                                        <td>{{ $complaint->email ?? 'N/A' }}</td>
                                        <td>{{ $complaint->address }}</td>
                                        <td>{{ $complaint->type }}</td>
                                        <td>{{ $complaint->description ?? 'N/A' }}</td>
                                        <td>{{ $complaint->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No complaints found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection