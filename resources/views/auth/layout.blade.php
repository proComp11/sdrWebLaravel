<!DOCTYPE html>
<html>
    <head>
        <title>SDR MANAGER WEB APP</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>
<body>
    <nav class="navbar navbar-light navbar-expand-lg mb-5" style="background-color: #e3f2fd;">
    <div class="container">
        <img
            src="{{ URL::asset('assets/sdrlogo.png')}}"
            height="50px;"
            alt="SDR Logo"
            loading="lazy"
            style="margin-top: -1px;"
        />
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <span class="navbar-text d-flex">
        </span>
        <ul class="navbar-nav ms-auto">
            @guest
                <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="{{ route('register-user') }}">Register</a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link btn btn-danger btn-sm" href="{{ route('signout') }}">Logout</a>
                </li>
            @endguest
        </ul>

    </div>
    </div>
    </nav>
        @yield('content')
    </body>
</html>
