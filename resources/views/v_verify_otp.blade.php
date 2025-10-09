<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - Teh Boston</title>
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
            max-width: 500px;
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
            font-size: 1.8rem;
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
            text-align: center; /* Center the OTP input */
            letter-spacing: 5px; /* Add some spacing for OTP digits */
            transition: all 0.3s;
        }

        .input-group input:focus {
            border-color: #4caf50;
            outline: none;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
        }

        .btn-verify {
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

        .btn-verify:hover {
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

        .resend-otp {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
        }

        .resend-otp a {
            color: #4caf50;
            text-decoration: none;
            font-weight: 500;
        }

        .resend-otp a:hover {
            text-decoration: underline;
        }

        .resend-timer {
            font-weight: bold;
            color: #f44336;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">Teh Boston</div>
        <div class="subtitle">Peluang Bisnis Franchise yang Menguntungkan</div>
    </header>

    <div class="container">
        <div class="form-header">
            <h2>Verifikasi OTP</h2>
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

            <form id="otpForm" method="POST" action="{{ route('user.verify-otp') }}">
                @csrf

                <p style="text-align: center; margin-bottom: 20px; color: #666;">
                    Silakan masukkan kode OTP (One-Time Password) yang telah kami kirimkan ke email Anda.
                </p>

                <div class="input-group">
                    <label for="otp">Kode OTP <span class="text-danger">*</span></label>
                    <input type="text" id="otp" name="otp" placeholder="Contoh: 123456" required autofocus maxlength="6" pattern="\d{6}" title="OTP harus 6 digit angka">
                    @error('otp')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-verify">Verifikasi OTP</button>

                <div class="resend-otp">
                    <a href="#" id="resendOtpLink" style="display: none;">Kirim ulang OTP</a>
                </div>
            </form>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2023 Teh Boston – Peluang Bisnis Franchise yang Menguntungkan</p>
        <p class="copyright">© 2025 Teh Boston. Hak Cipta Dilindungi. Dibuat dengan penuh cinta untuk para pecinta teh boston.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const resendOtpLink = document.getElementById('resendOtpLink');
            const resendTimerDisplay = document.getElementById('resendTimer');
            let countdown = 60; // 60 seconds

            function startCountdown() {
                resendOtpLink.style.display = 'none';
                resendTimerDisplay.style.display = 'inline';
                countdown = 60; // Reset countdown
                const timer = setInterval(() => {
                    countdown--;
                    resendTimerDisplay.textContent = `(${countdown} detik)`;
                    if (countdown <= 0) {
                        clearInterval(timer);
                        resendTimerDisplay.style.display = 'none';
                        resendOtpLink.style.display = 'inline';
                    }
                }, 1000);
            }

            // Mulai countdown jika ada pesan sukses (indikasi OTP baru dikirim)
            @if(session('success'))
                startCountdown();
            @endif

            // Implementasi kirim ulang OTP (membutuhkan rute AJAX POST)
            resendOtpLink.addEventListener('click', function(e) {
                e.preventDefault();
                // Lakukan permintaan AJAX untuk mengirim ulang OTP
                fetch('{{ route('user.resend-otp') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Kode OTP baru telah dikirim.');
                        startCountdown(); // Mulai countdown lagi
                    } else {
                        alert('Gagal mengirim ulang OTP: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengirim ulang OTP.');
                });
            });
        });
    </script>
</body>
</html>