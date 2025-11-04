@extends('layouts.app') 

@section('title', 'Contacts') 
@section('content') 

<div class="container my-3">
    <div class="card shadow rounded p-4 bg-light">
    <h1>Barangay 605 Hotline</h1>
    </div>

    <div class="card shadow rounded my-3 p-4 bg-light">
        <h2 class="mb-3 text-center">605 Barangay Residents Complaint Center</h2>
        <ul class="list-unstyled">
            <li><i class="fas fa-check-circle text-primary me-2"></i> Address: P. Sanchez Street, Barangay 605, Sta. Mesa, Manila City, Metro Manila, Philippines. </li>
            <li><i class="fas fa-search text-success me-2"></i> Email address: barangay605@gmail.com</li>
            <li><i class="fas fa-phone text-warning me-2"></i> Telephone #: 62705291</li>
            <li><i class="fas fa-book text-info me-2"></i> Cellphone #: 09**********</li>
        </ul>

        <p class="text-muted">
            The Barangay 605 Complaint System is a community-driven platform designed to make local governance more accessible, transparent, and responsive. Residents can file complaints, track their status, and connect with barangay officials—all in one place.
        </p>
    </div>
</div>
@endsection