@extends('layouts.appAdmin')
@section('title', 'Admin | Creating') 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3 class="mb-0">Schedule a Hearing</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.hearings.store') }}" method="POST">
                        @csrf
                        @include('admin.hearings._form', ['hearing' => null, 'complaint' => $complaint ?? null])
                        <div class="mt-3">
                            <button class="btn btn-success">Schedule</button>
                            <a href="{{ route('admin.hearings.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
