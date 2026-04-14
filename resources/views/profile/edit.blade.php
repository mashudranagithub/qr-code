@extends('layouts.app')
@section('title', 'Profile Settings')

@section('content')
<div class="row align-items-center mb-5 mt-md-4">
    <div class="col-lg-12">
        <h1 class="h2 fw-bold mb-1 gradient-text">Account Settings</h1>
        <p class="text-slate-400 mb-0">Manage your profile information and security preferences.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="glass-card p-4 p-md-5 mb-4">
            <h4 class="fw-bold text-white mb-4">Profile Information</h4>
            
            @if (session('status') === 'profile-updated')
                <div class="alert alert-success bg-success bg-opacity-10 border-0 text-success small mb-4">
                    Your profile has been updated successfully.
                </div>
            @elseif (session('status') === 'profile-updated-verify')
                <div class="alert alert-warning bg-warning bg-opacity-10 border-0 text-warning small mb-4">
                    Profile updated. Please check your email to verify your new address.
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label x-small fw-bold text-uppercase text-slate-400 mb-2">Full Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label x-small fw-bold text-uppercase text-slate-400 mb-2">Email Address</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="mt-2 text-warning small">
                                Your email address is unverified.
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-12 mt-5">
                        <hr class="border-white border-opacity-10 mb-5">
                        <h4 class="fw-bold text-white mb-4">Update Password</h4>
                        <p class="text-slate-400 small mb-4">Leave these fields blank if you don't want to change your password.</p>
                    </div>

                    <div class="col-12">
                        <label class="form-label x-small fw-bold text-uppercase text-slate-400 mb-2">Current Password</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label x-small fw-bold text-uppercase text-slate-400 mb-2">New Password</label>
                        <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label x-small fw-bold text-uppercase text-slate-400 mb-2">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control">
                    </div>

                    <div class="col-12 mt-5">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="fa-solid fa-cloud-arrow-up me-2"></i>Save All Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="glass-card p-4 text-center">
            <div class="mb-4">
                <div class="bg-primary bg-opacity-10 p-4 rounded-circle d-inline-block position-relative" style="width: 100px; height: 100px;">
                    <i class="fa-solid fa-user-gear text-primary fs-1"></i>
                    <span class="position-absolute bottom-0 end-0 bg-success border border-white border-opacity-20 rounded-circle" style="width: 15px; height: 15px;"></span>
                </div>
            </div>
            <h5 class="fw-bold text-white mb-1">{{ $user->name }}</h5>
            <p class="text-slate-400 small mb-4">{{ $user->email }}</p>
            
            <div class="bg-white bg-opacity-5 rounded-4 p-3 mb-4 text-start">
                <div class="d-flex justify-content-between mb-2">
                    <span class="x-small text-slate-500">Member Since</span>
                    <span class="x-small text-white fw-bold">{{ $user->created_at->format('M Y') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="x-small text-slate-500">QR Collection</span>
                    <span class="x-small text-white fw-bold">{{ $user->qrCodes()->count() }} Codes</span>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-2 small">
                    <i class="fa-solid fa-right-from-bracket me-2"></i>Sign Out
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .x-small { font-size: 0.72rem; letter-spacing: 0.05em; }
</style>
@endsection
