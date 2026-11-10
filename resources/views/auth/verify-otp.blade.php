@extends('layouts.app')
@section('title', 'Verifikasi OTP')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 8px;">
                <h4 class="fw-bold text-center mb-4">Verifikasi Email</h4>
                <p class="text-muted text-center small mb-4">Kami telah mengirimkan 6 digit kode OTP ke email <strong>{{ session('verify_email') }}</strong>. Silakan cek kotak masuk atau folder spam.</p>
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form action="{{ route('verify.otp.submit') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kode OTP (6 Angka)</label>
                        <input type="text" name="otp_code" class="form-control text-center fs-3 fw-bold" maxlength="6" placeholder="000000" required autofocus>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 fw-bold py-2">Verifikasi Akun</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection