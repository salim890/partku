@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 8px;">
                <h4 class="fw-bold text-center mb-4">Login PartKu</h4>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" required autofocus>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    @if($errors->any()) <div class="text-danger small mb-3">{{ $errors->first() }}</div> @endif
                    <button type="submit" class="btn btn-danger w-100 fw-bold py-2">Masuk</button>
                </form>
<div class="text-center mt-2">
    <a href="{{ route('password.request') }}" class="text-muted small text-decoration-none">Lupa Password?</a>
</div>
                <div class="text-center mt-3 small">Belum punya akun? <a href="{{ route('register') }}" class="text-danger fw-bold text-decoration-none">Daftar</a></div>
            </div>
        </div>
    </div>
</div>
@endsection