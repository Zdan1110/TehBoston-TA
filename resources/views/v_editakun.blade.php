<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Akun - Teh Boston</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #2e7d32, #4caf50);
            color: white;
            text-align: center;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .logo {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 1.2rem;
            font-weight: 300;
            letter-spacing: 1px;
        }

        .container {
            max-width: 600px;
            width: 90%;
            margin: 40px auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
            flex-grow: 1;
        }

        .form-header {
            background: #f1f8e9;
            padding: 25px;
            text-align: center;
            border-bottom: 2px solid #c5e1a5;
        }

        .form-header h2 {
            color: #2e7d32;
            font-size: 2rem;
            font-weight: 600;
        }

        .form-body {
            padding: 30px;
        }

        .input-group {
            margin-bottom: 25px;
            position: relative;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
            font-size: 0.95rem;
        }

        .input-group input {
            width: 100%;
            padding: 14px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .input-group input:focus {
            border-color: #4caf50;
            outline: none;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
        }

        .btn-update {
            background: linear-gradient(to right, #2e7d32, #4caf50);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 14px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
            box-shadow: 0 4px 10px rgba(76, 175, 80, 0.3);
            margin-top: 10px;
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(76, 175, 80, 0.4);
        }

        .footer {
            background: #333;
            color: #fff;
            text-align: center;
            padding: 20px 15px;
            font-size: 0.9rem;
            margin-top: auto;
        }

        .footer p {
            line-height: 1.6;
        }

        .copyright {
            margin-top: 10px;
            font-size: 0.85rem;
            color: #bbb;
        }

        .error-message {
            color: #f44336;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .id-display {
            text-align: center;
            font-size: 1.1rem;
            font-weight: 500;
            color: #333;
            margin-bottom: 25px;
            padding: 10px 0;
            background-color: #e8f5e9;
            border-radius: 8px;
        }
        .id-display strong {
            color: #2e7d32;
        }
    </style>
</head>
<body>
   

    <div class="container">
        <div class="form-header">
            <h2>Edit Profil Akun</h2>
        </div>

        <div class="form-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="id-display">
                ID Akun: <strong>{{ $user->id_akun }}</strong>
            </div>

            <form action="{{ route('user.update') }}" method="POST">
                @csrf
                @method('PUT') <div class="input-group">
                    <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required>
                    @error('nama')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="no_hp">Nomor Telepon <span class="text-danger">*</span></label>
                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" required>
                    @error('no_hp')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="username">Username <span class="text-danger">*</span></label>
                    <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                    @error('username')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="password">Password Baru (kosongkan jika tidak diubah)</label>
                    <input type="password" id="password" name="password" placeholder="Minimal 6 karakter">
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
                </div>

                <button type="submit" class="btn-update">Perbarui Profil</button>

                <a href="/home" class="btn-update" style="background: #ccc; color: #333; margin-top: 15px; text-align: center; display: inline-block; text-decoration: none;">
                    ← Kembali
                </a>

            </form>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2023 Teh Boston – Peluang Bisnis Franchise yang Menguntungkan</p>
        <p class="copyright">© 2025 Teh Boston. Hak Cipta Dilindungi. Dibuat dengan penuh cinta untuk para pecinta teh boston.</p>
    </footer>
</body>
</html>