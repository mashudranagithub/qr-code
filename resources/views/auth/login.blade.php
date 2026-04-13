@extends('layouts.app')
@section('title', 'Login to QRGen')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5">
        <div class="glass-card p-4 p-md-5">
            <div class="text-center mb-5">
                <i class="fa-solid fa-qrcode fa-3x text-primary mb-3"></i>
                <h2 class="fw-bold h3">Welcome Back</h2>
                <p class="text-muted">Sign in to manage your premium QR codes</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger border-0 small py-2 mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="email" class="form-label small fw-600 text-uppercase">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-4">
                            <i class="fa-regular fa-envelope text-muted"></i>
                        </span>
                        <input type="email" name="email" id="email" class="form-control border-start-0 rounded-end-4" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between">
                        <label for="password" class="form-label small fw-600 text-uppercase">Password</label>
                        <a href="#" class="small text-primary text-decoration-none">Forgot?</a>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-4">
                            <i class="fa-solid fa-lock text-muted"></i>
                        </span>
                        <input type="password" name="password" id="password" class="form-control border-start-0 rounded-end-4" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label small text-muted" for="remember">Stay signed in for 30 days</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 mb-4 shadow">
                    <i class="fa-solid fa-right-to-bracket me-2"></i>Sign In
                </button>

                <div class="text-center">
                    <p class="text-muted small mb-0">Don't have an account? 
                        <a href="{{ route('register') }}" class="text-primary fw-600 text-decoration-none">Create Account</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
