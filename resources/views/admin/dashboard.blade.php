@extends('layouts.appAdmin')
@section('title', 'Admin | Dashboard') 
@section('content')
        <div class="container-fluid">
            
            <div class="card my-3">
                <div class="card-header brand-green"></div>
                <div class="card-body ">
                <h2>Admin Dashboard</h2>
                </div>
            </div>

                <div class="row">
                    <div class="col">
                        <div class="card shadow rounded p-4 bg-primary">
                            <h4 class="card-title text-white">Complaints</h4>
                            <h1 class="card-text text-white">{{ $total ?? 0 }}</span></h1>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card shadow rounded p-4 bg-secondary">
                            <h4 class="card-title text-white">Pending</h4>
                            <h1 class="card-text text-white">{{ $pending ?? 0 }}</span></h1>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card shadow rounded p-4 bg-warning">
                            <h4 class="card-title text-white">In Process</h4>
                            <h1 class="card-text text-white">{{ $in_progress ?? 0 }}</span></h1>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card shadow rounded p-4 bg-success">
                            <h4 class="card-title text-white">Completed</h4>
                            <h1 class="card-text text-white">{{ ($rejected + $resolved) ?? 0 }}</span></h1>
                        </div>
                    </div>
                </div>
            <div class="card my-3">
                <div class="card-header brand-green"></div>
                <div class="card-body ">
                <h2>Recent Complaints</h2>
                </div>
            </div>

            <div class="card shadow-sm rounded my-3">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:60px" class="ps-3">No.</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Contact</th>
                                <th>Complaint</th>
                                <th style="width:120px">Status</th>
                                <th style="width:80px" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentComplaints ?? [] as $c)
                                <tr class="align-middle">
                                    <td class="ps-3"><strong>{{ sprintf('%02d', $c->id) }}</strong></td>
                                    <td>{{ $c->first_name ?? 'N/A' }}</td>
                                    <td>{{ $c->last_name ?? 'N/A' }}</td>
                                    <td>{{ $c->contact ?? 'N/A' }}</td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $c->type ?? 'N/A' }}">
                                            {{ $c->type ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                        $st = $c->status ?? 'pending';
                                        $displayClass = $st === 'closed' ? 'rejected' : $st;
                                        $displayLabel = $st === 'closed' ? 'Rejected' : ucwords(str_replace('_',' ', $st));
                                        @endphp
                                        <span class="badge-status {{ $displayClass }}">{{ $displayLabel }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.complaints.show', $c->id) }}" 
                                           class="btn btn-sm btn-outline-primary rounded-circle" 
                                           title="View Complaint Details">
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fa-regular fa-folder-open me-2"></i>No recent complaints.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection

<style>
.badge-status { padding: 6px 10px; border-radius: 20px; display: inline-block; font-size: 1.25rem; font-weight: 600; }
.badge-status.pending { background-color: #ffd43b; color: #664d03; }
.badge-status.in_progress { background-color: #4dabf7; color: #0a416d; }
.badge-status.resolved { background-color: #51cf66; color: #1b4724; }
.badge-status.rejected { background-color: #ff6b6b; color: #6d1a1a; }
</style>