<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Teh Boston</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background: linear-gradient(135deg, #f9f7f0, #e8f5e9);
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 400px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .logo {
            text-align: center;
            font-size: 2rem;
            color: #1a5d1a;
            margin-bottom: 20px;
            font-weight: bold;
        }
        h2 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #2E3438;
            font-weight: 500;
        }
        input[type="email"] {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1.5px solid #ccc;
            margin-bottom: 15px;
            outline: none;
            transition: 0.3s;
        }
        input:focus {
            border-color: #4caf50;
        }
        .btn {
            width: 100%;
            background: linear-gradient(90deg, #1a5d1a, #2e7d32);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }
        .btn:hover {
            background: linear-gradient(90deg, #2e7d32, #1a5d1a);
        }
        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 5px solid #28a745;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 5px solid #e74c3c;
        }
        .alert i {
            font-size: 1.2rem;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
            color: #5D6D7E;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">Teh Boston</div>
        <h2>Lupa Password</h2>

        {{-- Alert --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-times-circle"></i> {{ session('error') }}
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('password.send') }}" method="POST">
            @csrf
            <label for="email">Email Terdaftar</label>
            <input type="email" name="email" id="email" placeholder="Masukkan email Anda" required>

            <button type="submit" class="btn">
                <i class="fas fa-paper-plane"></i> Kirim Kode OTP
            </button>
        </form>

        <div class="footer">
            &copy; 2025 Teh Boston - Franchise Berkualitas
        </div>
    </div>
</body>
</html>
