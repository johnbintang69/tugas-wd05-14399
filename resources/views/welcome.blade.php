<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Poliklinik</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body, html {
      height: 100%;
      font-family: 'Poppins', sans-serif;
    }

    .background {
      background-image: url('https://img.freepik.com/free-photo/medical-equipment-cabinet-hospital-clinical-laboratory_482257-33685.jpg');
      background-size: cover;
      background-position: center;
      filter: blur(8px);
      height: 100%;
      width: 100%;
      position: fixed;
      z-index: -1;
    }

    .overlay {
      background-color: rgba(255, 255, 255, 0.85);
      height: 100%;
      width: 100%;
      position: absolute;
      top: 0;
      left: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .content {
      text-align: center;
      padding: 2rem;
      border-radius: 10px;
    }

    .content h1 {
      font-size: 3rem;
      margin-bottom: 2rem;
      color: #1e3a8a;
    }

    .btn {
      display: inline-block;
      padding: 12px 28px;
      margin: 0.5rem;
      font-size: 1rem;
      border-radius: 50px;
      text-decoration: none;
      transition: 0.3s;
      font-weight: 500;
      border: 2px solid transparent;
    }

    .btn-login {
      background-color: #1e40af;
      color: #fff;
    }

    .btn-login:hover {
      background-color: #1a3694;
    }

    .btn-register {
      background-color: #fff;
      color: #1e40af;
      border: 2px solid #1e40af;
    }

    .btn-register:hover {
      background-color: #f1f5f9;
    }

    @media (max-width: 768px) {
      .content h1 {
        font-size: 2rem;
      }

      .btn {
        padding: 10px 20px;
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>
  <div class="background"></div>
  <div class="overlay">
    <div class="content">
      <h1><i class="fas fa-hospital me-2"></i>Poliklinik</h1>
      <a href="{{ route('login') }}" class="btn btn-login">Masuk</a>
      <a href="{{ route('register') }}" class="btn btn-register">Daftar</a>
    </div>
  </div>
</body>
</html>
