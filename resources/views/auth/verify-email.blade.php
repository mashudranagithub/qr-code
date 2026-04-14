@extends('layouts.app')
@section('title', 'Verify Email')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="glass-card p-4 p-md-5 text-center">
            <div class="mb-4">
                <div class="bg-primary bg-opacity-10 p-4 rounded-circle d-inline-block mb-3" style="width: 80px; height: 80px;">
                    <i class="fa-solid fa-envelope-circle-check text-primary fs-2"></i>
                </div>
                <h2 class="fw-bold text-white mb-2">Verify Your Email</h2>
                <p class="text-slate-400">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?</p>
            </div>

            @if (session('message'))
                <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success small mb-4">
                    A new verification link has been sent to the email address you provided during registration.
                </div>
            @endif

            <div class="d-grid gap-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-paper-plane me-2"></i>Resend Verification Email
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link text-slate-400 text-decoration-none small">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
