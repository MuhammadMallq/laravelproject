<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Indo Ice Tea</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                        url('https://images.unsplash.com/photo-1556679343-c7306c1976bc?q=80&w=1600&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            width: 90%;
            max-width: 400px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            text-align: center;
            border-top: 10px solid #ff7e5f;
            backdrop-filter: blur(5px);
        }

        h2 { color: #333; margin-bottom: 10px; font-weight: 600; font-size: 28px; }
        p.subtitle { color: #777; font-size: 14px; margin-bottom: 30px; }

        .error {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
            border: 1px solid #fecaca;
            animation: shake 0.5s;
        }

        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
            100% { transform: translateX(0); }
        }

        .form-group { margin-bottom: 20px; text-align: left; }

        label {
            display: block;
            font-size: 13px;
            margin-bottom: 8px;
            color: #444;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #eee;
            border-radius: 12px;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #fdfdfd;
        }

        input:focus {
            outline: none;
            border-color: #ff7e5f;
            background: #fff;
            box-shadow: 0 0 10px rgba(255,126,95,0.1);
        }

        button {
            width: 100%;
            padding: 16px;
            background: #333;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        button:hover {
            background: #ff7e5f;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255,126,95,0.4);
        }

        .footer-text { margin-top: 25px; font-size: 12px; color: #aaa; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>🍹 Indo Ice Tea</h2>
        <p class="subtitle">Panel Admin - Silahkan masuk</p>

        @if($errors->any())
        <div class="error">
            ⚠️ @foreach($errors->all() as $error) {{ $error }} @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Username Anda" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Password Anda" required>
            </div>
            <button type="submit">Masuk ke Tampilan Admin</button>
        </form>

        <div class="footer-text">
            &copy; 2024 Indo Ice Tea Management System
        </div>
    </div>
</body>
</html>
