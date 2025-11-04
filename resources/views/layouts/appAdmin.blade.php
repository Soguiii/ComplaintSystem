<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Laravel App')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @vite('resources/css/app.css')
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
                <a class="nav-link" href="{{ route('admin.hearings.index') }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Hearing Schedule</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main  -->
    <div class="content-wrapper" style="background-image: url('{{ asset('images/cityhall.jpg') }}'); background-size: cover; background-repeat: no-repeat; position: relative;">
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(211, 210, 210, 0.8);  "></div>
        <!-- Navbar -->
        <nav class="navbar bg-white border-bottom position-relative">
            <div class="container-fluid">
                <button class="sidebar-toggler" type="button" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="ms-auto">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-2"></i>{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
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
        <div class="content p-4">
            @yield('content')
        </div>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

@stack('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const wrapper = document.querySelector('.wrapper');
    
    sidebarToggle.addEventListener('click', function() {
        wrapper.classList.toggle('sidebar-collapsed');
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('collapsed');
    });
});
</script>

</body>
</html>