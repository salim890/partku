@extends('layouts.app')

@section('title', 'Diagram Part')

@push('styles')
<style>
    .motor-card { border: 1px solid #eee; border-radius: 8px; transition: 0.3s; background: #fff; text-decoration: none; color: inherit; }
    .motor-card:hover { border-color: #cc0000; box-shadow: 0 5px 15px rgba(204,0,0,0.1); transform: translateY(-5px); }
    .motor-icon { font-size: 4rem; color: #cc0000; margin-bottom: 15px; }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Katalog Diagram Part</h2>
        <p class="text-muted">Pilih model motor Honda kamu untuk melihat diagram part.</p>
    </div>

    <div class="row g-4 justify-content-center">
        @foreach($motors as $m)
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('diagram.show', [$m, 'CVT']) }}" class="motor-card d-block p-4 text-center">
                <i class="bi bi-scooter motor-icon"></i>
                <h5 class="fw-bold mb-0">Honda {{ $m }}</h5>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection