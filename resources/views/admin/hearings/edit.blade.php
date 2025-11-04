@extends('layouts.appAdmin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3 class="mb-0">Edit Hearing</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.hearings.update', $hearing->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('admin.hearings._form', ['hearing' => $hearing])
                        <div class="mt-3">
                            <button class="btn btn-success">Save Changes</button>
                            <a href="{{ route('admin.hearings.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
