<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('meta')

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    html, body {
        height: 100%;
    }

    body {
        font-family: 'Poppins', sans-serif;
        display: flex;
        flex-direction: column;
    }

    main.py-4 {
        flex: 1;
    }

    .navbar-brand {
        font-weight: 600;
    }

    .bg-gradient-dark {
        background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.8) 100%);
    }
    </style>

    @stack('styles')
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('blog.index') ? 'active' : '' }}"
                           href="{{ route('blog.index') }}">Blog</a>
                    </li>
                </ul>
                {{-- <div class="d-flex">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                        @endif
                    @endauth
                </div> --}}
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        {{ $slot }}
    </main>

    <!-- Footer -->
    {{-- <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-3">About Us</h5>
                    <p class="text-muted mb-0">
                        {{ config('app.name') }} is your go-to destination for insightful articles and the latest updates in technology.
                    </p>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-3">Quick Links</h5>
                    <ul class="list-unstyled text-muted">
                        <li><a href="{{ route('blog.index') }}" class="text-decoration-none text-muted">Blog</a></li>
                        @guest
                            <li><a href="{{ route('login') }}" class="text-decoration-none text-muted">Login</a></li>
                            @if (Route::has('register'))
                                <li><a href="{{ route('register') }}" class="text-decoration-none text-muted">Register</a></li>
                            @endif
                        @endguest
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3">Follow Us</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-github"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center text-muted">
                <small>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</small>
            </div>
        </div>
    </footer> --}}

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
