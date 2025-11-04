@extends('layouts.app') 

@section('title', 'Dashboard') 
@section('content') 

<div class="container my-3">
  <div class="card shadow rounded p-4 bg-light text-center">
    <h1 class="mb-4">Welcome to Barangay 605 Complaint</h1>
    <div class="row g-3 justify-content-center">
      <div class="col-md-3">
        <a href="{{ url('/complaint') }}" class="btn btn-primary w-100">
          <i class="fas fa-edit me-2"></i> File a Complaint
        </a>
      </div>
      <div class="col-md-3">
        <a href="{{ url('/Track') }}" class="btn btn-success w-100">
          <i class="fas fa-search me-2"></i> Track my Complaint
        </a>
      </div>
      <div class="col-md-3">
        <a href="{{ url('/Contacts') }}" class="btn btn-warning w-100 text-white">
          <i class="fas fa-phone me-2"></i> Contact Us
        </a>
      </div>
      <div class="col-md-3">
        <a href="{{ url('/Resources') }}" class="btn btn-info w-100 text-white">
          <i class="fas fa-book me-2"></i> Resources
        </a>
      </div>
    </div>
  </div>
<br>
 <div class="card shadow rounded p-4 bg-light">
  <h2 class="mb-3 text-center">About This Website</h2>
  <p class="text-muted">
    The Barangay 605 Complaint System is a community-driven platform designed to make local governance more accessible, transparent, and responsive. Residents can file complaints, track their status, and connect with barangay officials—all in one place.
  </p>
  <ul class="list-unstyled">
    <li><i class="fas fa-check-circle text-primary me-2"></i> Submit complaints securely and easily</li>
    <li><i class="fas fa-search text-success me-2"></i> Track updates and resolution status</li>
    <li><i class="fas fa-phone text-warning me-2"></i> Contact barangay support directly</li>
    <li><i class="fas fa-book text-info me-2"></i> Access resources and guidelines</li>
  </ul>
</div>

</div>


@endsection