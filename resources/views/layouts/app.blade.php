<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Laravel App')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite('resources/css/app.css')
</head>
<body>
<div style="
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-image: url('{{ asset('images/cityhall.jpg') }}');
  background-size: cover;
  background-repeat: no-repeat;
  filter: blur(8px);
  z-index: -1;">
</div>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow sticky-top">
    <div class="container">
        <div class="d-flex align-items-center">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/brlogo.png') }}" alt="Barangay logo" class="d-inline-block">
            </a>
            <h4 class="brgy-title mb-0">BRGY-605</h4>
            <div class="vertical-divider"></div>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                    <a class="nav-link" href="{{ url('/complaint') }}">File Complaint</a>
                    </li>

                    <li class="nav-item">
                    <a class="nav-link" href="{{ url('/Track') }}">Track</a>
                    </li>

                    <li class="nav-item">
                    <a class="nav-link" href="{{ url('/Resources') }}">Resources</a>
                    </li>

                    <li class="nav-item">
                    <a class="nav-link" href="{{ url('/Contacts') }}">Contacts</a>
                    </li>
                
                     
            </ul>

             <ul class="navbar-nav ms-auto"> 
                @guest 
                    @if (Route::has('login')) 
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{ route('login') }}">Login</a> 
                        </li> 
                    @endif 
                    @if (Route::has('register')) 
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{ route('register') }}">Register</a> 
                        </li> 
                    @endif 
                @else 
                    <li class="nav-item dropdown"> 
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"> 
                            {{ Auth::user()->name }} 
                        </a> 
                        <ul class="dropdown-menu"> 
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li> 
                            <li> 
                                <a class="dropdown-item" href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); 
                                                 document.getElementById('logout-form').submit();"> 
                                    Logout 
                                </a> 
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none"> 
                                    @csrf 
                                </form> 
                            </li> 
                        </ul> 
                    </li> 
                @endguest 
            </ul> 
        </div>
    </div>
</nav>

<main class="container mt-4"> 
        @yield('content') 
</main>

<footer class="bg-light text-center">
  <small>&copy; 2025 Barangay 605 Complaint System</small>
</footer>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>