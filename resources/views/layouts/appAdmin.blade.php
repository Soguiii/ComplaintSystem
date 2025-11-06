<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Laravel App')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @vite(['resources/js/app.js', 'resources/sass/app.scss', 'resources/css/dashboard.css'])
</head>
<body>
<div class="wrapper d-flex">
    <!-- Sidebar -->
    <div class="sidebar bg-white">
        <div class="sidebar-header border-bottom p-3">
            <div class="sidebar-brand d-flex align-items-center">
                <img src="{{ asset('images/brlogo.png') }}" alt="Brgy Logo" style="height: 40px; width: 40px; object-fit: contain;">
                <span class="ms-2 fw-semibold">BRGY-605</span>
            </div>
        </div>
        <ul class="sidebar-nav mt-2">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.complaints') }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Complaints</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.all_files') }}">
                    <i class="fas fa-folder"></i>
                    <span>All Files</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.activity_logs') }}">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Activity Logs</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.hearings.index') }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Hearing Schedule</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main  -->
    <div class="content-wrapper d-flex flex-column" style="background-image: url('{{ asset('images/cityhall.jpg') }}'); background-size: cover; background-repeat: no-repeat; min-height:100vh;">
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(211, 210, 210, 0.8); z-index:0; pointer-events:none;"></div>
        <!-- Navbar -->
        <nav class="navbar bg-white border-bottom position-relative" style="z-index: index 2;;">
            <div class="container-fluid">
                <button class="sidebar-toggler" type="button" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="ms-auto">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownAdmin" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-2"></i>{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownAdmin">
                            <li><a class="dropdown-item" href="{{url('/') }}"><i class="fas fa-user-cog me-2"></i>Back to Main</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="content p-4 flex-fill" style="position:relative; z-index:2;">
            @yield('content')
        </div>

        <footer class="bg-light text-center py-3 mt-3" style="position:relative; z-index:2;">
            <div class="container">
                <small>&copy; {{ date('Y') }} Barangay 605 Complaint System — Admin</small>
            </div>
        </footer>

    </div>
</div>

@stack('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const wrapper = document.querySelector('.wrapper');
    const sidebar = document.querySelector('.sidebar');

    function isMobile() { return window.innerWidth < 768; }

    sidebarToggle.addEventListener('click', function() {
        if (isMobile()) {
            // mobile: slide overlay
            const isShown = sidebar.classList.toggle('show');
            if (isShown) {
                // add backdrop
                let backdrop = document.createElement('div');
                backdrop.className = 'sidebar-backdrop';
                backdrop.id = 'sidebarBackdrop';
                backdrop.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    document.body.removeChild(backdrop);
                });
                document.body.appendChild(backdrop);
                document.body.style.overflow = 'hidden';
                sidebarToggle.setAttribute('aria-expanded', 'true');
            } else {
                const existing = document.getElementById('sidebarBackdrop');
                if (existing) document.body.removeChild(existing);
                document.body.style.overflow = '';
                sidebarToggle.setAttribute('aria-expanded', 'false');
            }
        } else {
            // desktop: collapse to icons-only
            wrapper.classList.toggle('sidebar-collapsed');
            sidebar.classList.toggle('collapsed');
            const expanded = sidebar.classList.contains('collapsed') ? 'false' : 'true';
            sidebarToggle.setAttribute('aria-expanded', expanded);
        }
    });

    // respond to window resize: remove mobile backdrop if switching to desktop
    window.addEventListener('resize', function() {
        const existing = document.getElementById('sidebarBackdrop');
        if (!isMobile() && existing) {
            sidebar.classList.remove('show');
            document.body.removeChild(existing);
            document.body.style.overflow = '';
        }
    });
});
</script>

</body>
</html>