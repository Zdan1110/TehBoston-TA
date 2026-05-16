<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teh Boston - Login & Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    {{-- Link to your external CSS file --}}
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    <style>
        .alert {
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
    border-left: 5px solid;
    }

    .alert-success {
        border-color: #28a745;
    }

    .alert-danger {
        border-color: #dc3545;
    }

    </style>
</head>
<body>
    <img src="https://cdn-icons-png.flaticon.com/512/2346/2346705.png" class="tea-decoration tea-leaf-1 floating" alt="Tea leaf">
    <img src="https://cdn-icons-png.flaticon.com/512/2346/2346705.png" class="tea-decoration tea-leaf-2 floating" alt="Tea leaf">

    <div class="container-main">
        <div class="brand-header">
            <h1>Teh Boston</h1>
        </div>

        <div class="container-auth" id="authContainer">

            <div class="auth-card login-card" id="loginCard">
                <div class="card-header">
          

                    <h2>Login</h2>
                </div>
                @if (session('success'))
                <div class="alert alert-success alert-dismissible popup-top fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger alert-dismissible popup-top fade show" role="alert">
                    <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="card-body">
                    <form action="{{ route('login.proses') }}" method="POST" id="loginForm">
                        @csrf
                        <div class="form-group">
                            <div class="input-group @error('username') is-invalid @enderror">
                                <i class="fas fa-user"></i>
                                <input type="text" class="form-control" name="username" placeholder="Username atau Email" value="{{ old('username') }}" required>
                            </div>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="input-group @error('password') is-invalid @enderror">
                                <i class="fas fa-lock"></i>
                                <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Password" required>
                                <span class="toggle-password" onclick="togglePassword('loginPassword', this)">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn-submit">
                            <span class="spinner" id="loginSpinner"></span>
                            <i class="fas fa-sign-in-alt"></i> Masuk Sekarang
                        </button>
                    </form>
                    <div class="form-footer">
                        <p>Lupa password? <a href="/forgotpassword">Reset di sini</a></p>
                        <a class="flip-link" id="switchToRegister">
                            Belum punya akun? <strong>Daftar di sini</strong>
                        </a>
                    </div>
                </div>
            </div>

            <div class="auth-card register-card" id="registerCard">
                <div class="card-header">
                    <h2>Register</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('register.proses') }}" method="POST" id="registerForm">
                        @csrf
                        <div class="form-group">
                            <div class="input-group @error('nama') is-invalid @enderror">
                                <i class="fas fa-user"></i>
                                <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap" value="{{ old('nama') }}" required>
                            </div>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="input-group @error('email') is-invalid @enderror">
                                <i class="fas fa-envelope"></i>
                                <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="input-group @error('no_hp') is-invalid @enderror">
                                <i class="fas fa-phone"></i>
                                <input type="text" class="form-control" name="no_hp" placeholder="No. HP" value="{{ old('no_hp') }}" required>
                            </div>
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="input-group @error('username') is-invalid @enderror">
                                <i class="fas fa-user-tag"></i>
                                <input type="text" class="form-control" name="username" placeholder="Username" value="{{ old('username') }}" required>
                            </div>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="input-group @error('password') is-invalid @enderror">
                                <i class="fas fa-lock"></i>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                <span class="toggle-password" onclick="togglePassword('password', this)">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <i class="fas fa-lock"></i>
                                <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" placeholder="Konfirmasi Password" required>
                                <span class="toggle-password" onclick="togglePassword('confirmPassword', this)">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                            <div class="error-message" id="passwordError"></div>
                        </div>
                        <button type="submit" class="btn-submit">
                            <span class="spinner" id="registerSpinner"></span>
                            <i class="fas fa-user-plus"></i> Daftar Sekarang
                        </button>
                    </form>
                    <div class="form-footer">
                        <a class="flip-link" id="switchToLogin">
                            Sudah punya akun? <strong>Masuk di sini</strong>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="brand-footer" style="margin-top: 30px; text-align: center; color: var(--primary-medium); font-size: 0.9rem;">
            <p>© 2025 Teh Boston. Hak Cipta Dilindungi. Dibuat dengan penuh cinta untuk para pecinta teh boston.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Link to your external JavaScript file --}}
    <script src="{{ asset('assets/js/login.js') }}"></script>

    {{-- PHP logic for session messages and errors (keep this in Blade) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Re-call showAlert for Laravel session messages
            @if(session('success'))
                showAlert('successAlert');
            @endif
            @if(session('error'))
                showAlert('errorAlert');
            @endif
            @if(session('status'))
                showAlert('statusAlert');
            @endif

            // Re-apply Laravel validation errors
            @if($errors->any())
                @php
                    $isRegisterError = $errors->hasAny(['nama', 'email', 'no_hp', 'username', 'password_confirmation']);
                @endphp

                if (@json($isRegisterError)) { // Convert PHP boolean to JavaScript boolean
                    document.getElementById('authContainer').classList.add('flipped');
                } else {
                    document.getElementById('authContainer').classList.remove('flipped');
                }

                setTimeout(setContainerHeight, 50);

                @foreach($errors->keys() as $field)
                    @if($field === 'password' && $isRegisterError)
                        const inputElement = document.querySelector('#registerForm input[name="password"]');
                    @elseif($field === 'password')
                        const inputElement = document.querySelector('#loginForm input[name="password"]');
                    @else
                        const inputElement = document.querySelector('input[name="{{ $field }}"]');
                    @endif

                    if (inputElement) {
                        inputElement.closest('.input-group').classList.add('is-invalid');
                    }
                @endforeach
            @endif
        });

        function togglePassword(inputId, iconElement) {
            const input = document.getElementById(inputId);
            const icon = iconElement.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>