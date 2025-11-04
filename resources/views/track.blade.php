@extends('layouts.app')

@section('title', 'Track')
@section('content')

<div class="container my-5">
  <div class="card shadow rounded p-4 bg-light">
    <h1>Barangay 605, MANILA - Track Complaint</h1>
  </div>

  <div class="card shadow rounded my-2 p-4 bg-light">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('complaints.track') }}" method="POST">
      @csrf
      
      <div class="row mb-3">
        <div class="col-12 mb-3">
          <label for="reference" class="form-label">Reference Code</label>
          <input type="text" 
                 name="reference" 
                 id="reference"
                 value="{{ old('reference') }}" 
                 placeholder="CMP-20251101-ABC123" 
                 class="form-control @error('reference') is-invalid @enderror">
          @error('reference')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <div class="form-text">Enter your reference code if you have one, or search by name below</div>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6 mb-3 mb-md-0">
          <label for="first_name" class="form-label">First Name:</label>
          <input type="text" 
                 name="first_name" 
                 id="first_name"
                 value="{{ old('first_name') }}" 
                 placeholder="First Name" 
                 class="form-control @error('first_name') is-invalid @enderror">
          @error('first_name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label for="last_name" class="form-label">Last Name</label>
          <input type="text" 
                 name="last_name" 
                 id="last_name"
                 value="{{ old('last_name') }}" 
                 placeholder="Last Name" 
                 class="form-control @error('last_name') is-invalid @enderror">
          @error('last_name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Track Complaint</button>
    </form>
  </div>

  @if(isset($complaint))
    @if($complaint)
      <div class="card shadow rounded my-3 p-4 bg-light">
        <h3>Complaint Details</h3>
        <hr>
        <p><strong>Name:</strong> {{ $complaint->first_name }} {{ $complaint->middle_name }} {{ $complaint->last_name }}</p>
        <p><strong>Contact:</strong> {{ $complaint->contact }}</p>
        <p><strong>Email:</strong> {{ $complaint->email ?? 'N/A' }}</p>
        <p><strong>Address:</strong> {{ $complaint->address }}</p>
        <p><strong>Type:</strong> {{ $complaint->type }}</p>
        <p><strong>Description:</strong> {{ $complaint->description ?? 'N/A' }}</p>
        <p><strong>Status:</strong> 
          <span class="badge 
            @if($complaint->status == 'pending') bg-warning 
            @elseif($complaint->status == 'in_progress') bg-info 
            @elseif($complaint->status == 'resolved') bg-success 
            @else bg-secondary 
            @endif">
            {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
          </span>
        </p>

        @if($complaint->hearing)
          <div class="mt-4">
            <h4>Hearing Schedule</h4>
            <hr>
            <div class="alert {{ $complaint->hearing->status == 'cancelled' ? 'alert-danger' : 'alert-info' }}">
              <h5 class="alert-heading">{{ $complaint->hearing->title }}</h5>
              <p class="mb-2">
                <strong>Scheduled Date:</strong> 
                {{ $complaint->hearing->scheduled_at ? $complaint->hearing->scheduled_at->format('F d, Y h:i A') : 'TBD' }}
              </p>
              <p class="mb-2">
                <strong>Status:</strong> 
                <span class="badge bg-{{ $complaint->hearing->status == 'scheduled' ? 'primary' : ($complaint->hearing->status == 'resolved' ? 'success' : 'danger') }}">
                  {{ ucfirst($complaint->hearing->status) }}
                </span>
              </p>
              @if($complaint->hearing->details)
                <p class="mb-0"><strong>Details:</strong><br>{{ $complaint->hearing->details }}</p>
              @endif
            </div>
          </div>
        @endif
      </div>
    @else
      <div class="alert alert-danger mt-3">
        @if(request()->filled('reference'))
          No complaint found with reference number: {{ request()->reference }}
        @else
          No complaint found for {{ request()->first_name }} {{ request()->last_name }}
        @endif
      </div>
    @endif
  @endif

</div>
@endsection
