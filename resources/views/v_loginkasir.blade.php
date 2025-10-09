<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kasir - Teh Boston</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #073716;
            --primary-medium: #0b581b;
            --primary-light: #c8e6c9;
            --cream-light: #f8f4e1;
            --cream-medium: #e9e1c0;
            --cream-dark: #d9d1b0;
            --accent-green: #28a745;
            --accent-red: #dc3545;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--cream-light) 0%, var(--cream-medium) 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            overflow-x: hidden;
            transition: background 0.5s ease;
        }
        
        .container-animated {
            width: 100%;
            max-width: 450px;
            animation: fadeInScale 0.8s ease-out;
            transform-origin: center;
        }
        
        @keyframes fadeInScale {
            0% {
                opacity: 0;
                transform: scale(0.95);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .card-custom {
            border-radius: 18px;
            border: none;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(7, 55, 22, 0.15);
            transition: all 0.5s ease;
            background: white;
            position: relative;
            border: 2px solid var(--cream-dark);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-medium) 100%);
            padding: 25px 15px;
            text-align: center;
            border-bottom: 3px solid var(--cream-medium);
            position: relative;
        }
        
        .card-header img {
            width: 80px;
            filter: drop-shadow(0 3px 5px rgba(0,0,0,0.2));
            transition: transform 0.5s ease;
        }
        
        .card-header:hover img {
            transform: rotate(5deg) scale(1.05);
        }
        
        .card-header h2 {
            color: white;
            margin-top: 15px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 3px rgba(0,0,0,0.2);
        }
        
        .card-body {
            padding: 30px;
            background: linear-gradient(to bottom, #ffffff, var(--cream-light));
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .input-group {
            margin-bottom: 5px;
        }
        
        .input-group-text {
            background: var(--primary-dark);
            color: white;
            border: none;
            border-radius: 8px 0 0 8px !important;
            width: 45px;
            justify-content: center;
            transition: all 0.3s;
        }
        
        .form-control {
            height: 50px;
            border: 2px solid var(--cream-dark);
            border-radius: 0 8px 8px 0 !important;
            padding-left: 15px;
            transition: all 0.3s;
            font-size: 1rem;
            background-color: #ffffff;
        }
        
        .form-control:focus {
            border-color: var(--primary-medium);
            box-shadow: 0 0 0 0.25rem rgba(11, 88, 27, 0.15);
        }
        
        .form-control:focus + .input-group-text {
            background: var(--primary-medium);
            transform: scale(1.05);
        }
        
        .btn-cashier {
            background: linear-gradient(to right, var(--primary-dark), var(--primary-medium));
            border: none;
            height: 50px;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(7, 55, 22, 0.25);
            margin-top: 10px;
            color: white;
            font-size: 1.1rem;
        }
        
        .btn-cashier:hover {
            background: linear-gradient(to right, var(--primary-medium), var(--primary-dark));
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(7, 55, 22, 0.35);
        }
        
        .btn-cashier:active {
            transform: translateY(0);
        }
        
        .card-footer {
            background-color: var(--cream-light);
            border-top: 1px solid var(--cream-dark);
            padding: 20px;
            text-align: center;
            border-radius: 0 0 18px 18px;
        }
        
        .card-footer a {
            color: var(--primary-medium);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            position: relative;
            font-size: 0.95rem;
        }
        
        .card-footer a:hover {
            color: var(--primary-dark);
            text-decoration: none;
        }
        
        .card-footer a::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: var(--primary-medium);
            transform: scaleX(0);
            transform-origin: bottom right;
            transition: transform 0.3s ease;
        }
        
        .card-footer a:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }
        
        .tea-decoration {
            position: absolute;
            z-index: -1;
            opacity: 0.1;
            transition: all 1s ease;
        }
        
        .tea-leaf-1 {
            top: 10%;
            left: 5%;
            width: 70px;
            transform: rotate(-20deg);
            filter: sepia(100%) hue-rotate(70deg) saturate(500%);
        }
        
        .tea-leaf-2 {
            bottom: 15%;
            right: 5%;
            width: 60px;
            transform: rotate(25deg);
            filter: sepia(100%) hue-rotate(70deg) saturate(500%);
        }
        
        .tea-leaf-3 {
            top: 40%;
            right: 15%;
            width: 50px;
            transform: rotate(60deg);
            filter: sepia(100%) hue-rotate(70deg) saturate(500%);
        }
        
        .tea-leaf-4 {
            bottom: 40%;
            left: 15%;
            width: 55px;
            transform: rotate(300deg);
            filter: sepia(100%) hue-rotate(70deg) saturate(500%);
        }
        
        .error-message {
            color: var(--accent-red);
            font-size: 0.85rem;
            margin-top: 5px;
            display: none;
            animation: shake 0.5s ease;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .brand-text {
            color: var(--primary-medium);
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            font-weight: 500;
            opacity: 0;
            animation: fadeIn 1s ease 0.5s forwards;
            background: rgba(255, 255, 255, 0.7);
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
        }
        
        @keyframes fadeIn {
            to { opacity: 1; }
        }
        
        .brand-text span {
            font-weight: 700;
            color: var(--primary-dark);
        }
        
        .show-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            z-index: 10;
        }
        
        .show-password:hover {
            color: var(--primary-medium);
        }
        
        .cashier-icon {
            background: var(--cream-light);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 4px 10px rgba(7, 55, 22, 0.15);
            border: 2px solid var(--cream-medium);
        }
        
        .cashier-icon i {
            font-size: 36px;
            color: var(--primary-dark);
        }
        
        .cashier-title {
            color: var(--primary-dark);
            text-align: center;
            font-weight: 600;
            margin-bottom: 25px;
            font-size: 1.1rem;
            position: relative;
            display: inline-block;
            padding: 0 10px;
        }
        
        .cashier-title::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 3px;
            background: linear-gradient(to right, transparent, var(--cream-medium), transparent);
            bottom: -8px;
            left: 0;
            border-radius: 3px;
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            font-size: 0.9rem;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-medium);
            border-color: var(--primary-medium);
        }
        
        .form-check-label {
            color: #555;
        }
        
        .forgot-password {
            color: var(--primary-medium);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .forgot-password:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        /* Animasi daun teh */
        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(5deg); }
            100% { transform: translateY(0) rotate(0deg); }
        }
        
        .tea-leaf-1 { animation: float 8s infinite ease-in-out; }
        .tea-leaf-2 { animation: float 7s infinite ease-in-out 1s; }
        .tea-leaf-3 { animation: float 9s infinite ease-in-out 0.5s; }
        .tea-leaf-4 { animation: float 6s infinite ease-in-out 1.5s; }
        
        .form-message {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            display: none;
        }
        
        .form-message.error {
            background-color: #ffe6e6;
            color: #cc0000;
            border: 1px solid #ff9999;
        }
        
        .form-message.success {
            background-color: #e6ffe6;
            color: #006600;
            border: 1px solid #99cc99;
        }
        
        .loading-spinner {
            display: none;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 4px solid var(--primary-medium);
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Dekorasi daun teh animasi -->
    <img src="https://cdn-icons-png.flaticon.com/512/2346/2346705.png" class="tea-decoration tea-leaf-1" alt="Tea leaf">
    <img src="https://cdn-icons-png.flaticon.com/512/2346/2346705.png" class="tea-decoration tea-leaf-2" alt="Tea leaf">
    <img src="https://cdn-icons-png.flaticon.com/512/2346/2346705.png" class="tea-decoration tea-leaf-3" alt="Tea leaf">
    <img src="https://cdn-icons-png.flaticon.com/512/2346/2346705.png" class="tea-decoration tea-leaf-4" alt="Tea leaf">

    <div class="container-animated">
        <div class="card card-custom">
            <!-- Header dengan animasi -->
            <div class="card-header">
                <div class="cashier-icon">
                    <i class="fas fa-cash-register"></i>
                </div>
                <h2>Login Kasir</h2>
            </div>

            <div class="card-body">
                <form id="loginForm" action="{{ route('login.proses') }}" method="POST">
                    @csrf
                    
                    <!-- Form messages for errors/success -->
                    <div id="formMessage" class="form-message"></div>
                    
                    <div class="text-center mb-4">
                        <h3 class="cashier-title">Sistem Kasir Teh Boston</h3>
                    </div>
                    
                    <!-- Username -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                            <input type="text" class="form-control" name="username" id="username" placeholder="Username Kasir" required autofocus>
                        </div>
                    </div>
                    
                    <!-- Password -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            <span class="show-password" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        <div class="error-message" id="loginError"></div>
                    </div>
                    
                    <!-- Remember me & Forgot password -->
                    <div class="remember-forgot">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                            <label class="form-check-label" for="rememberMe">
                                Ingat saya
                            </label>
                        </div>
                        <a href="#" class="forgot-password">Lupa password?</a>
                    </div>

                    <button type="submit" class="btn btn-cashier w-100">
                        <i class="fas fa-sign-in-alt me-2"></i>Masuk ke Sistem
                    </button>
                    
                    <!-- Loading spinner -->
                    <div id="loadingSpinner" class="loading-spinner mt-3"></div>
                </form>
            </div>

            <div class="card-footer">
                <small>Kembali ke <a href="/" id="homeLink">Beranda</a> | <a href="/admin" id="adminLink">Login Admin</a></small>
            </div>
        </div>
        
        <div class="brand-text text-center w-100">
            Sistem Kasir Teh Boston &copy; 2023 - <span>Transaksi Cepat dan Akurat</span>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle show password
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
                });
            }
            
            // Form submission handler
            const loginForm = document.getElementById('loginForm');
            
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const form = this;
                    const formData = new FormData(form);
                    const errorElement = document.getElementById('loginError');
                    const formMessage = document.getElementById('formMessage');
                    const loadingSpinner = document.getElementById('loadingSpinner');
                    
                    // Clear previous messages
                    if (errorElement) errorElement.style.display = 'none';
                    if (formMessage) formMessage.style.display = 'none';
                    
                    // Show loading spinner
                    if (loadingSpinner) loadingSpinner.style.display = 'block';
                    
                    // Client-side validation
                    const username = formData.get('username').trim();
                    const password = formData.get('password').trim();
                    
                    if (!username || !password) {
                        if (errorElement) {
                            errorElement.textContent = 'Harap isi semua bidang!';
                            errorElement.style.display = 'block';
                            setTimeout(() => {
                                errorElement.style.animation = 'shake 0.5s ease';
                            }, 10);
                        }
                        if (loadingSpinner) loadingSpinner.style.display = 'none';
                        return;
                    }
                    
                    // Submit form via AJAX
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Login success
                            if (formMessage) {
                                formMessage.textContent = 'Login berhasil! Mengarahkan...';
                                formMessage.className = 'form-message success';
                                formMessage.style.display = 'block';
                            }
                            
                            // Redirect to kasir dashboard
                            setTimeout(() => {
                                window.location.href = '/kasir';
                            }, 1500);
                        } else {
                            // Login failed
                            if (errorElement) {
                                errorElement.textContent = data.message || 'Username atau password salah!';
                                errorElement.style.display = 'block';
                                setTimeout(() => {
                                    errorElement.style.animation = 'shake 0.5s ease';
                                }, 10);
                            }
                            
                            if (formMessage) {
                                formMessage.textContent = data.message || 'Terjadi kesalahan saat login';
                                formMessage.className = 'form-message error';
                                formMessage.style.display = 'block';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (formMessage) {
                            formMessage.textContent = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
                            formMessage.className = 'form-message error';
                            formMessage.style.display = 'block';
                        }
                    })
                    .finally(() => {
                        if (loadingSpinner) loadingSpinner.style.display = 'none';
                    });
                });
            }
            
            // Page transition for links
            const homeLink = document.getElementById('homeLink');
            const adminLink = document.getElementById('adminLink');
            
            if (homeLink) {
                homeLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    animatePageTransition(this.href);
                });
            }
            
            if (adminLink) {
                adminLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    animatePageTransition(this.href);
                });
            }
            
            // Page transition animation
            function animatePageTransition(url) {
                const card = document.querySelector('.card-custom');
                if (card) {
                    card.style.animation = 'fadeOutScale 0.5s ease forwards';
                }
                
                document.body.style.background = 'linear-gradient(135deg, var(--cream-medium), var(--cream-light))';
                
                setTimeout(() => {
                    window.location.href = url;
                }, 500);
            }
            
            // Add custom keyframe for fadeOutScale
            const styleSheet = document.styleSheets[0];
            styleSheet.insertRule(`
                @keyframes fadeOutScale {
                    0% {
                        opacity: 1;
                        transform: scale(1);
                    }
                    100% {
                        opacity: 0;
                        transform: scale(0.95);
                    }
                }
            `, styleSheet.cssRules.length);
        });
    </script>
</body>
</html>