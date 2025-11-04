@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Verify Your Complaint</div>

                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-envelope fa-3x text-primary"></i>
                    </div>
                    <h2>Check Your Email</h2>
                    <p>We've sent a verification link to your email address.</p>
                    <p>Please check your email and click the verification link to complete your complaint submission.</p>
                    <p class="text-muted mt-4">Didn't receive the email? Check your spam folder.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection