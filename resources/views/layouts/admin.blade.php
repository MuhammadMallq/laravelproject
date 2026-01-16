<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel | Indo Ice Tea')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #ff7e5f; --secondary: #feb47b; --bg: #f4f7f6; --sidebar-width: 250px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: var(--bg); display: flex; }

        .sidebar {
            width: var(--sidebar-width);
            background: white;
            height: 100vh;
            position: fixed;
            padding: 20px;
            border-right: 1px solid #eee;
            display: flex;
            flex-direction: column;
        }

        .sidebar h2 {
            color: var(--primary);
            font-size: 1.4rem;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .menu-link {
            text-decoration: none;
            color: #777;
            padding: 12px 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: 0.3s;
            font-weight: 500;
        }

        .menu-link:hover, .menu-link.active {
            background: #fff0eb;
            color: var(--primary);
        }

        .logout { margin-top: auto; color: #e74c3c; }

        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 30px;
            min-height: 100vh;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar">
        <h2><i class="fas fa-lemon"></i> Admin Dashboard</h2>
        <a href="{{ route('admin.dashboard') }}#overview" class="menu-link" onclick="window.location.href='{{ route('admin.dashboard') }}#overview'; location.reload();">
            <i class="fas fa-chart-line"></i> Dashboard
        </a>

        <a href="{{ route('admin.dashboard') }}#stok" class="menu-link" onclick="window.location.href='{{ route('admin.dashboard') }}#stok'; location.reload();">
            <i class="fas fa-box-open"></i> Manajemen Stok
        </a>
        <a href="{{ route('admin.dashboard') }}#pesanan" class="menu-link" onclick="window.location.href='{{ route('admin.dashboard') }}#pesanan'; location.reload();">
            <i class="fas fa-history"></i> Riwayat Pesanan
        </a>
        <a href="{{ route('logout') }}" class="menu-link logout">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
