<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'QR Code Generator')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #4338ca 100%);
            border: none;
            padding: 14px 32px;
            border-radius: 16px;
            font-weight: 600;
            color: #ffffff;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px var(--primary-glow);
            filter: brightness(1.1);
            color: #ffffff;
        }

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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-5">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="{{ url('/') }}">
                <i class="fa-solid fa-qrcode text-primary me-2"></i>QR<span class="text-primary">Gen</span>
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
                <div class="d-flex align-items-center">
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle border-secondary px-3" type="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end glass-card border-0 shadow-lg mt-3">
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
                        <a href="{{ route('login') }}" class="btn text-light fw-500 me-2">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">Sign Up Free</a>
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
            <div class="col-md-6 text-center text-md-start text-muted small">
                &copy; {{ date('Y') }} QRGen. International Professional Standards.
            </div>
            <div class="col-md-6 text-center text-md-end text-muted small">
                Premium vCard Delivery System.
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
