@extends('layouts.app') 

@section('title', 'Resources') 
@section('content') 

<div class="container my-5">
    <div class="container p-3 my-4  bg-light text-dark rounded text-center">
        <h1 class="h3">BARANGAY 605 - Resources</h1>
        <p class="text-muted">Helpful categories and guidance for common barangay concerns. Click "Learn more" to read details.</p>
    </div>

    <div class="row g-4 resources-grid">

        @foreach($cards as $card)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-center fw-bold">{{ $card['title'] }}</h5>
                    <p class="card-text text-center text-muted flex-grow-1">{{ $card['excerpt'] }}</p>
                    <div class="text-center mt-3">
                        <a href="{{ route('resources.show', $card['slug']) }}"  rel="noopener" class="btn btn-outline-success">LEARN MORE</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

@push('styles')
<style>
    .placeholder-img {
        width: 100%;
        height: 160px;
        overflow: hidden;
        border-radius: 6px;
        background: #f5f5f5;
        display: block;
    }
    .placeholder-img-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .card .card-body { padding: 1.25rem; }
    .card-title { margin-top: 0.5rem; }
    .card { background: #fff; position: relative; }

    .card-text { min-height: 3.5rem; }

    .resources-grid { align-items: flex-start; }


    .resources-grid > [class*='col-'] { display: flex; }
    .resources-grid > [class*='col-'] .card { width: 100%; }
</style>
@endpush


@endsection