@extends('layouts.appAdmin')
@section('title', 'Admin | Show') 
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header brand-green">Complaint Details</div>
        <div class="card-body">
          <div class="mb-3 row">
            <div class="col-12">
              <div class="p-2 mb-3" style="background:#f6f6f6; border-radius:6px;">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <div class="ref-plain" style="font-size:0.95rem">{{ $complaint->reference ?? 'N/A' }}</div>
                    <div class="small text-muted">Reference</div>
                  </div>
                  <div class="text-end">
                    @php
                      $st = $complaint->status ?? 'pending';
                      $displayClass = $st === 'closed' ? 'rejected' : $st;
                      $displayLabel = $st === 'closed' ? 'Rejected' : ucwords(str_replace('_',' ', $st));
                    @endphp
                    <div><span class="badge-status {{ $displayClass }}">{{ $displayLabel }}</span></div>
                    @if($complaint->status_changed_at)
                      <div class="small text-muted mt-1">since {{ $complaint->status_changed_at->timezone(config('app.timezone'))->format('M d, Y') }} ({{ $complaint->status_changed_at->timezone(config('app.timezone'))->diffForHumans() }})</div>
                    @else
                      <div class="small text-muted mt-1">since {{ $complaint->created_at->timezone(config('app.timezone'))->format('M d, Y') }} ({{ $complaint->created_at->timezone(config('app.timezone'))->diffForHumans() }})</div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Name</label>
            <div class="col-sm-9 col-form-label">
              {{ $complaint->first_name }} {{ $complaint->middle_name }} {{ $complaint->last_name }}
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Reference</label>
            <div class="col-sm-9 col-form-label">{{ $complaint->reference ?? 'N/A' }}</div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Contact</label>
            <div class="col-sm-9 col-form-label">{{ $complaint->contact }}</div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Date of Birth</label>
            <div class="col-sm-9 col-form-label">{{ $complaint->dob ?? 'N/A' }}</div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Type</label>
            <div class="col-sm-9 col-form-label">{{ $complaint->type ?? 'N/A' }}</div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Email</label>
            <div class="col-sm-9 col-form-label">{{ $complaint->email ?? 'N/A' }}</div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Address</label>
            <div class="col-sm-9 col-form-label">{{ $complaint->address }}</div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Complaint</label>
            <div class="col-sm-9 col-form-label">{{ $complaint->description ?? 'N/A' }}</div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Status</label>
            <div class="col-sm-9 col-form-label">
              @php
                $st = $complaint->status ?? 'pending';
                $displayClass = $st === 'closed' ? 'rejected' : $st;
                $displayLabel = $st === 'closed' ? 'Rejected' : ucwords(str_replace('_',' ', $st));
              @endphp
              <span class="badge-status {{ $displayClass }}">{{ $displayLabel }}</span>
              @if($complaint->status_changed_at)
                <div class="small text-muted mt-1">since {{ $complaint->status_changed_at->format('M d, Y') }} ({{ $complaint->status_changed_at->diffForHumans() }})</div>
              @else
                <div class="small text-muted mt-1">since {{ $complaint->created_at->format('M d, Y') }} ({{ $complaint->created_at->diffForHumans() }})</div>
              @endif
            </div>
          </div>

          <div class="d-flex justify-content-end">
            <a href="{{ route('admin.complaints') }}" class="btn btn-secondary me-2">Back</a>
            <a href="{{ route('admin.complaints.edit', $complaint->id) }}" class="btn btn-green me-2">Edit Details</a>
            @auth
            <a href="{{ route('admin.hearings.create', ['complaint_id' => $complaint->id]) }}" class="btn btn-success">Schedule Hearing</a>
            @endauth
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
.ref-plain { font-family: monospace; }
.badge-status { padding: 0.4rem 0.7rem; border-radius: 24px; }
.badge-status.pending { background-color: #ffd43b; color: #664d03; }
.badge-status.in_progress { background-color: #4dabf7; color: #0a416d; }
.badge-status.resolved { background-color: #51cf66; color: #1b4724; }
.badge-status.rejected { background-color: #ff6b6b; color: #6d1a1a; }
</style>
@endsection
