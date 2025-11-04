@extends('layouts.app') 

@section('title', 'Complaint') 
@section('content') 

<div class="container">


     <div class="container p-3  bg-light text-dark rounded">
        <h1>BARANGAY 605, MANILA - Complaint Form</h1>
    </div>

    <div class="container p-5 my-3 bg-light text-dark rounded">  
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                {{-- @if(session('reference'))
                <div class="alert alert-info" role="alert">
                        Your Reference Number: <strong>{{ session('reference') }}</strong>
                </div>
                @endif --}}

                {{-- Modal popup for verification success (auto-show when redirected after clicking email link) --}}
                @if(session('success'))
                <div class="modal fade" id="verificationModal" tabindex="-1" aria-labelledby="verificationModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="verificationModalLabel">Verification Successful</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>{{ session('success') }}</p>
                                @if(session('reference'))
                                <p>Your Reference Number: <strong>{{ session('reference') }}</strong></p>
                                @endif
                                <p class="text-muted">You can now track your complaint using the reference number.</p>
                            </div>
                            <div class="modal-footer">
                                <a href="{{ url('/') }}" class="btn btn-secondary">Home</a>
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
        <h1>Enter Form:</h1>
            <form method="POST" action="{{ route('complaints.store') }}" class="was-validated">

            @csrf
            <div class="row">
                <div class="col">
                    <label for="name" class=form-label>First Name:</label> <br>
                     <input type="text" name="first_name" placeholder="First Name" class = "form-control" required><br>
                </div>

                <div class="col">
                    <label for="name" class=form-label>Middle Name</label><br>
                    <input type="text" name="middle_name" placeholder="Middle Name" class = "form-control" required><br>
                </div>    
            </div>

            <div class="row">
                <div class="col">
                    <label for="name" class=form-label>Last name</label><br>
                    <input type="text" name="last_name" placeholder="Last Name" class = "form-control" required><br>
                </div>
                <div class="col">
                <label for="email" class=form-label>Email <span class="text-danger">*</span></label><br>
                <input type="email" name="email" placeholder="Email" class="form-control" required><br>
                <small class="text-muted">A verification link will be sent to this email.</small>
                </div>
            </div>
            



            <label for="contact" class=form-label>Contact Number:</label><br>
            <input type="text" name="contact" placeholder="Contact No." class = "form-control" required><br>

            <label for="birth" class=form-label>Birthday</label><br>
            <input type="date" name="dob" placeholder="Date of Birth (optional)" class = "form-control"><br>

            <label for="address" class=form-label>Address</label><br>
            <input type="text" name="address" placeholder="Address" required class = "form-control"><br>


      
            <label for="address" class=form-label>Select</label><br>
            <select name="type" class= "form-select" required>
                <option value="">Select Complaint Type</option>
                <option value="Domestic Conflict">Domestic Conflict</option>
                <option value="Noise Disturbance">Noise Disturbance</option>
                <option value="Property Dispute">Land/Property Dispute</option>
                <option value="Barangay Official Conduct">Barangay Official Conduct</option>
                <option value="Tanod Misconduct">Tanod Misconduct</option>
                <option value="Others">Others</option>
            </select><br>

            <label for="text" class=form-label> Comment:</label><br>
            <textarea name="description" placeholder="Describe your complaint (optional) " class = "form-control"></textarea><br>

            <button type="submit" class="btn btn-primary mt-3">
            <i class="fas fa-paper-plane"></i> Submit
            </button>
            </form>  
</div> 
</div>


@endsection

@push('scripts')
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modalEl = document.getElementById('verificationModal');
        if (modalEl) {
            var modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    });
</script>
@endif
@endpush

