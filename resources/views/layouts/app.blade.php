<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Indo Ice Tea')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('image.png') }}">
    <style>
        :root {
            --primary: #ff7e5f;
            --primary-dark: #e66e52;
            --secondary: #feb47b;
            --dark: #2d3436;
            --gray: #636e72;
            --navbar-orange: #ff9933;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--dark);
        }

        header {
            background-color: var(--navbar-orange);
            color: white;
            padding: 15px 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 1.6rem;
            font-weight: 700;
            color: white;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 30px;
        }

        .nav-links a {
            text-decoration: none;
            color: white;
            font-weight: 500;
            font-size: 0.95rem;
            transition: 0.3s;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background: white;
            transition: 0.3s;
        }

        .nav-links a:hover::after,
        .nav-links a.active::after {
            width: 100%;
        }

        footer {
            background: var(--dark);
            color: #ddd;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }

        footer a {
            color: inherit;
            text-decoration: none;
        }
    </style>
    @stack('styles')
</head>
<body>
    <header>
        <div class="logo">🍹 Indo Ice Tea</div>
        <nav>
            <ul class="nav-links">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="{{ route('menu.index') }}" class="{{ request()->routeIs('menu.index') ? 'active' : '' }}">Menu</a></li>
                <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Kontak</a></li>
            </ul>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; 2024 Iceetheaa. <a href="{{ route('login') }}">All rights reserved.</a></p>
    </footer>

    @stack('scripts')
</body>
</html>
