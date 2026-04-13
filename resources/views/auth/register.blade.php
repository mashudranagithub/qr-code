@extends('layouts.app')
@section('title', 'Join QRGen Today')

@section('content')
<div class="row justify-content-center align-items-center mb-5" style="min-height: 80vh;">
    <div class="col-md-6">
        <div class="glass-card p-4 p-md-5">
            <div class="text-center mb-5">
                <i class="fa-solid fa-bolt fa-3x text-primary mb-3"></i>
                <h2 class="fw-bold h3">Get Started for Free</h2>
                <p class="text-muted">Create your account and start generating smart codes</p>
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

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="form-label small fw-600 text-uppercase">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-4">
                            <i class="fa-regular fa-user text-muted"></i>
                        </span>
                        <input type="text" name="name" id="name" class="form-control border-start-0 rounded-end-4" value="{{ old('name') }}" placeholder="John Doe" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label small fw-600 text-uppercase">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-4">
                            <i class="fa-regular fa-envelope text-muted"></i>
                        </span>
                        <input type="email" name="email" id="email" class="form-control border-start-0 rounded-end-4" value="{{ old('email') }}" placeholder="name@example.com" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="password" class="form-label small fw-600 text-uppercase">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 rounded-start-4">
                                <i class="fa-solid fa-lock text-muted"></i>
                            </span>
                            <input type="password" name="password" id="password" class="form-control border-start-0 rounded-end-4" placeholder="••••••••" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="password_confirmation" class="form-label small fw-600 text-uppercase">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 rounded-start-4">
                                <i class="fa-solid fa-lock text-muted"></i>
                            </span>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control border-start-0 rounded-end-4" placeholder="••••••••" required>
                        </div>
                    </div>
                </div>

                <div class="mb-4 text-muted small">
                    By signing up, you agree to our <a href="#" class="text-primary text-decoration-none">Terms of Service</a> and <a href="#" class="text-primary text-decoration-none">Privacy Policy</a>.
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 mb-4 shadow">
                    <i class="fa-solid fa-user-plus me-2"></i>Create My Account
                </button>

                <div class="text-center">
                    <p class="text-muted small mb-0">Already have an account? 
                        <a href="{{ route('login') }}" class="text-primary fw-600 text-decoration-none">Sign In Instead</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
