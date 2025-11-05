@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center">
            <h4>Select Active User Role</h4>
        </div>
        <div class="card-body text-center">
            <p class="mb-4">Please select which user is currently using the dashboard:</p>

            <form method="POST" action="{{ route('admin.setRole') }}">
                @csrf
                <div class="btn-group-vertical" style="gap: 15px;">
                    <button type="submit" name="role" value="Secretary" class="btn btn-outline-primary btn-lg w-100">
                        Secretary
                    </button>
                    <button type="submit" name="role" value="Staff" class="btn btn-outline-success btn-lg w-100">
                        Staff
                    </button>
                    <button type="submit" name="role" value="Kagawad" class="btn btn-outline-warning btn-lg w-100">
                        Kagawad
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
