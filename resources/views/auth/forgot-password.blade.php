<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Sistem Surat Menyurat Bank Lampung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #004080 0%, #0066cc 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .forgot-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        
        .forgot-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .forgot-header h2 {
            color: #004080;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .forgot-header p {
            color: #666;
            font-size: 14px;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #ddd;
        }
        
        .form-control:focus {
            border-color: #0066cc;
            box-shadow: 0 0 0 0.2rem rgba(0,102,204,0.25);
        }
        
        .btn-send {
            background-color: #0066cc;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            border: none;
            transition: all 0.3s;
        }
        
        .btn-send:hover {
            background-color: #004080;
            transform: translateY(-1px);
        }
        
        .back-to-login {
            color: #0066cc;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }
        
        .back-to-login:hover {
            color: #004080;
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-header">
            <i class="bi bi-key" style="font-size: 50px; color: #004080;"></i>
            <h2>Lupa Password</h2>
            <p>Masukkan email Anda untuk menerima link reset password</p>
        </div>
        
        @if (session('status'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> {{ session('status') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            
            <div class="mb-4">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" 
                           placeholder="email@banklampung.co.id" required autofocus>
                </div>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-send">
                <i class="bi bi-send"></i> Kirim Link Reset Password
            </button>
        </form>
        
        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="back-to-login">
                <i class="bi bi-arrow-left"></i> Kembali ke Login
            </a>
        </div>
        
        <div class="text-center mt-4">
            <small class="text-muted">© 2024 Bank Lampung. All rights reserved.</small>
        </div>
    </div>
</body>
</html>