<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poliklinik</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2d8cf0;
            --secondary-color: #5ebd3e;
            --dark-color: #333;
            --light-color: #f9f9f9;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), url('https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover;
        }
        
        .header {
            text-align: center;
            padding: 10px 0;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }
        
        .header h1 {
            color: var(--primary-color);
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .header p {
            font-size: 20px;
            color: var(--dark-color);
            margin-bottom: 30px;
        }
        
        .auth-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .auth-card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            width: 100%;
            max-width: 300px;
            margin: 0 20px;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 14px 35px;
            border-radius: 30px;
            font-weight: 500;
            width: 100%;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #2579d8;
            border-color: #2579d8;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(45, 140, 240, 0.3);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 14px 35px;
            border-radius: 30px;
            font-weight: 500;
            width: 100%;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(45, 140, 240, 0.3);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Selamat Datang di<br>Poliklinik Sehat</h1>
        <p>Pelayanan Kesehatan Terbaik untuk Anda</p>
    </div>

    <!-- Authentication Card -->
    <div class="auth-container">
        <div class="auth-card">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/home') }}" class="btn btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
                @endauth
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>