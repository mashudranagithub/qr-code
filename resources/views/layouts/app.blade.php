<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Boichitra - Professional QR Generator')</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🔳</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.4);
            --accent: #22d3ee;
            --slate-50: #f8fafc;
            --slate-300: #cbd5e1;
            --slate-400: #94a3b8;
            --slate-900: #0f172a;
            --slate-950: #020617;
            --glass: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        body { 
            font-family: 'Outfit', sans-serif; 
            background-color: var(--slate-950);
            background-image: 
                radial-gradient(circle at 0% 0%, rgba(99, 102, 241, 0.12) 0%, transparent 40%),
                radial-gradient(circle at 100% 100%, rgba(34, 211, 238, 0.08) 0%, transparent 40%);
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--slate-300);
            -webkit-font-smoothing: antialiased;
        }

        .navbar {
            background: rgba(2, 6, 23, 0.8) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            padding: 1.25rem 0;
            z-index: 1000;
        }

        .glass-card { 
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--glass-border);
            border-radius: 28px; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .btn {
            border-radius: 12px;
            padding: 10px 24px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.95rem;
            border: none !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }

        .btn-info {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.2);
        }

        .btn-outline-light {
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.05) 100%);
            color: #ffffff !important;
            border: 1px solid rgba(255,255,255,0.2) !important;
            backdrop-filter: blur(10px);
        }

        .btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.2);
            color: #ffffff !important;
        }

        .btn-outline-light:hover {
            background: linear-gradient(135deg, rgba(255,255,255,0.25) 0%, rgba(255,255,255,0.1) 100%);
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.1);
        }

        .btn-primary:hover { box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3); }
        .btn-warning:hover { box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3); }
        .btn-danger:hover { box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3); }
        .btn-info:hover { box-shadow: 0 8px 20px rgba(6, 182, 212, 0.3); }

        .form-control, .form-select {
            border-radius: 14px;
            padding: 12px 18px;
            border: 1px solid var(--glass-border);
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff !important;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
            border-color: var(--primary);
            background-color: rgba(255, 255, 255, 0.08);
        }

        .form-control::placeholder { color: rgba(255, 255, 255, 0.3); }

        .gradient-text {
            background: linear-gradient(135deg, #fff 0%, var(--slate-400) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
            letter-spacing: -0.02em;
        }
        
        .accent-text { color: var(--accent); }

        .nav-link {
            font-weight: 500;
            color: var(--slate-400) !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: #fff !important;
        }

        /* Dropdown Styling Fixes */
        .dropdown-menu {
            background: rgba(15, 23, 42, 0.95) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border) !important;
            border-radius: 20px !important;
            padding: 0.75rem !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4) !important;
            margin-top: 15px !important;
            z-index: 2000 !important;
        }

        .dropdown-item {
            color: var(--slate-300) !important;
            border-radius: 12px !important;
            padding: 0.75rem 1rem !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.08) !important;
            color: #fff !important;
            transform: translateX(5px);
        }

        .dropdown-item.text-danger:hover {
            background: rgba(239, 68, 68, 0.1) !important;
            color: #ef4444 !important;
        }

        /* Mobile Responsive Utilities */
        @media (max-width: 991.98px) {
            .navbar { padding: 1rem 0; }
            .navbar-brand { font-size: 1.5rem !important; }
            .display-4 { font-size: 2.75rem !important; }
            .gradient-text { letter-spacing: -0.01em; }
            .sticky-lg-top { position: relative !important; top: 0 !important; }
            .glass-card { border-radius: 22px; }
            main.container { padding-left: 20px; padding-right: 20px; }
        }

        @media (max-width: 575.98px) {
            .display-4 { font-size: 2.25rem !important; }
            .h5 { font-size: 1.1rem !important; }
            .btn-primary { padding: 12px 24px; width: 100%; }
            .glass-card { padding: 1.5rem !important; }
            .nav-pills .nav-link { padding: 12px 5px !important; font-size: 0.85rem; }
            .table-responsive { border-radius: 15px; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-5">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="{{ url('/') }}">
                <i class="fa-solid fa-qrcode text-primary me-2"></i>Boichitra<span class="text-primary">QR</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Generator</a>
                    </li>
                    @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">My Dashboard</a>
                    </li>
                    @endauth
                </ul>
                <div class="d-flex align-items-center gap-2">
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle px-3" type="button" data-bs-toggle="dropdown">
                                <i class="fa-regular fa-user-circle me-1"></i> {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0">
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                                        <i class="fa-solid fa-user-gear me-2"></i>Profile Settings
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider border-white border-opacity-10"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-danger py-2" type="submit">
                                            <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light">
                            <i class="fa-solid fa-right-to-bracket me-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            <i class="fa-solid fa-user-plus me-2"></i>Sign Up Free
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    <footer class="container py-5 mt-5 border-top border-white border-opacity-10">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <div class="text-slate-500 small">&copy; {{ date('Y') }} Boichitra QR Code. International Professional Standards.</div>
            </div>
            <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                <div class="text-slate-600 x-small">Design & Developed by <a href="https://mashudrana.com" target="_blank" class="text-slate-400 text-decoration-none hover-white">Md. Mashud Rana</a></div>
            </div>
        </div>
    </footer>

    <style>
        .hover-white:hover { color: #fff !important; }
        .x-small { font-size: 0.75rem; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
