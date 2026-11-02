@extends('layouts.app')
@section('title', 'Daftar')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 8px;">
                <h4 class="fw-bold text-center mb-4">Daftar Akun Baru</h4>
                
                {{-- KOTAK ERROR --}}
                @if($errors->any())
                    <div class="alert alert-danger p-2 small mb-3">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required minlength="6">
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                        <label class="form-check-label small" for="terms">
                            Saya menyetujui <a href="{{ route('kebijakan') }}" target="_blank" class="text-danger">Kebijakan Privasi</a> dan Syarat & Ketentuan PartKu.
                        </label>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 fw-bold py-2">Daftar</button>
                </form>
                <div class="text-center mt-3 small">Sudah punya akun? <a href="{{ route('login') }}" class="text-danger fw-bold text-decoration-none">Login</a></div>
            </div>
        </div>
    </div>
</div>
@endsection