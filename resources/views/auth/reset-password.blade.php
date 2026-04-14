@extends('layouts.app')
@section('title', 'Reset Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="glass-card p-4 p-md-5">
            <div class="text-center mb-4">
                <div class="bg-primary bg-opacity-10 p-3 rounded-4 d-inline-block mb-3">
                    <i class="fa-solid fa-lock-open text-primary fs-3"></i>
                </div>
                <h2 class="fw-bold text-white mb-2">New Password</h2>
                <p class="text-slate-400 small">Please enter your new secure password below.</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                    <label class="form-label fw-bold text-slate-400 small text-uppercase tracking-wider">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $request->email ?? '') }}" required autofocus>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-slate-400 small text-uppercase tracking-wider">New Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Minimum 8 characters">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-slate-400 small text-uppercase tracking-wider">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
