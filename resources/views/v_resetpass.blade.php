<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Teh Boston</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Styling sama seperti sebelumnya */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f9f7f0, #e8f5e9);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 100%;
            max-width: 430px;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .logo {
            text-align: center;
            font-size: 2rem;
            color: #1a5d1a;
            font-weight: bold;
            margin-bottom: 8px;
        }

        h2 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 25px;
            font-size: 1.7rem;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #2E3438;
            font-weight: 500;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1.5px solid #ccc;
            margin-bottom: 15px;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            border-color: #4caf50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.15);
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
            transition: 0.3s;
        }

        .btn:hover {
            background: linear-gradient(90deg, #2e7d32, #1a5d1a);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26, 93, 26, 0.3);
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
        <h2>Reset Password</h2>

        {{-- Alert --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            </div>
        @endif

        {{-- Form --}}
        <form action="/reset-password" method="POST">
            @csrf
            <label for="kode">Kode Verifikasi (OTP)</label>
            <input type="text" name="kode" id="kode" placeholder="Masukkan kode OTP" required>

            <label for="password">Password Baru</label>
            <input type="password" name="password" id="password" placeholder="Password baru" required>

            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi password" required>

            <button type="submit" class="btn">
                Reset Password
            </button>
        </form>

        <div class="footer">
            &copy; 2025 Teh Boston - Franchise Berkualitas
        </div>
    </div>
</body>
</html>
