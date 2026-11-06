@extends('layouts.app')
@section('title', 'Lupa Password')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 8px;">
                <h4 class="fw-bold text-center mb-4">Lupa Password?</h4>
                <p class="text-muted text-center small mb-4">Masukkan email yang terdaftar. Kami akan mengirimkan link untuk membuat password baru.</p>
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" required autofocus>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 fw-bold py-2">Kirim Link Reset</button>
                </form>
                <div class="text-center mt-3 small">
                    <a href="{{ route('login') }}" class="text-muted text-decoration-none"><i class="bi bi-arrow-left"></i> Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection