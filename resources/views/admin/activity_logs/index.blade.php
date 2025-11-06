@extends('layouts.appAdmin')
@section('title', 'Admin | Activity Logs')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Activity Logs</h3>
            <form class="d-flex" method="GET">
                <select name="action" class="form-select me-2" style="width:220px">
                    <option value="">All actions</option>
                    <option value="view">View Complaint</option>
                    <option value="open_edit">Open Edit</option>
                    <option value="update">Update Complaint</option>
                    <option value="schedule_hearing">Schedule Hearing</option>
                </select>
                <button class="btn btn-outline-primary">Filter</button>
            </form>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>When</th>
                        <th>Role</th>
                        <th>Action</th>
                        <th>Complaint</th>
                        <th>Hearing</th>
                        <th>Details</th>
                        <th>ID</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->timezone(config('app.timezone'))->format('M d, Y H:i') }}</td>
                            <td>{{ $log->role ?? 'Unknown' }}</td>
                            <td>{{ $log->action }}</td>
                            <td>{{ $log->complaint ? $log->complaint->reference : '-' }}</td>
                            <td>{{ $log->hearing_id ? 'H-'.$log->hearing_id : '-' }}</td>
                            <td style="max-width:320px; white-space:normal; word-break:break-word;">{{ $log->details ?? '-' }}</td>
                            <td>{{ $log->id }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No activity found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                {{ $logs->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
