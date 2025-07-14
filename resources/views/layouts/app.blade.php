<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Travel Order System')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
</head>
<body>
    @auth
        <div class="sidebar">
            <div class="logo">
                <span><img src="{{ asset('assets/img/denr-logo.png') }}" alt="DENR Logo" height="50" width="50"> TOS</span>
            </div>
            <nav>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>

                <a href="">
                    <i class="fas fa-user"></i> Employee
                </a>

                <a href="{{ route('positions.index') }}" class="{{ request()->routeIs('positions.index') ? 'active' : '' }}">
                    <i class="fas fa-briefcase"></i> Positions
                </a>
                
                <a href="{{ route('divsecunits.index') }}" class="{{ request()->routeIs('divsecunits.index') ? 'active' : '' }}">
                    <i class="fas fa-building"></i> Div/Sec/Unit
                </a>

                <a href="">
                    <i class="fas fa-file-alt"></i> Travel Orders
                </a>
                
                <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Users
                </a>

                <a href="">
                    <i class="fas fa-print"></i> Printing
                </a>

            </nav>
        </div>

        <div class="main-content">
            <header class="position-relative">
                <div class="welcome-message" style="color: var(--denr-primary);">
                    <i class="fas fa-user-circle me-2"></i>
                    <span>Welcome, <strong>{{ Auth::user()->name }}</strong></span>
                </div>
                <div class="user-profile position-relative">
                    <a href="{{ route('logout') }}" class="btn btn-danger btn-sm" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-1"></i>
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </header>
    @else
        <div class="login-container">
            <div class="login-content">
                @yield('content')
            </div>
        </div>
    @endauth

        <main>
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JavaScript Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Initialize dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.dropdown-toggle');
            dropdowns.forEach(dropdown => {
                new bootstrap.Dropdown(dropdown);
            });
        });
    </script>
    
    <script src="{{ asset('script.js') }}"></script>
    @stack('scripts')
</body>
</html>
