@extends('layouts.app')
@section('title', 'Forgot Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="glass-card p-4 p-md-5">
            <div class="text-center mb-4">
                <div class="bg-primary bg-opacity-10 p-3 rounded-4 d-inline-block mb-3">
                    <i class="fa-solid fa-key text-primary fs-3"></i>
                </div>
                <h2 class="fw-bold text-white mb-2">Forgot Password?</h2>
                <p class="text-slate-400 small">No problem. Just let us know your email address and we will email you a password reset link.</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success small mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-bold text-slate-400 small text-uppercase tracking-wider">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary">
                        Email Password Reset Link
                    </button>
                </div>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-slate-400 text-decoration-none small">
                        <i class="fa-solid fa-arrow-left me-1"></i>Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
