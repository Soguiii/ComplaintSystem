@extends('layouts.app')

@section('title', $card['title'])
@section('content')

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="row g-0">
            <div class="col-md-5">
                <img src="{{ asset('images/' . $card['image']) }}" class="img-fluid rounded-start" alt="{{ $card['title'] }}" style="width:100%;height:100%;object-fit:cover;max-height:420px;">
            </div>
            <div class="col-md-7">
                <div class="card-body">
                    <h2 class="card-title">{{ $card['title'] }}</h2>
                    <p class="text-muted">{{ $card['excerpt'] }}</p>

                    <div class="mt-3">
                        {!! $card['description'] !!}
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('resources.index') }}" class="btn btn-secondary">Back to Resources</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
