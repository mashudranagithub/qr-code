@extends('layouts.app')
@section('title', 'Login - QR Code Generator')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="glass-card p-4">
            <h3 class="mb-4 text-center fw-bold">Welcome Back</h3>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Log In</button>
            </form>
            <div class="mt-3 text-center">
                <small>Don't have an account? <a href="{{ route('register') }}">Sign up here</a></small>
            </div>
        </div>
    </div>
</div>
@endsection
