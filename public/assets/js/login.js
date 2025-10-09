document.addEventListener('DOMContentLoaded', function() {
    const authContainer = document.getElementById('authContainer');
    const loginCard = document.getElementById('loginCard');
    const registerCard = document.getElementById('registerCard');

    // Function to set container height based on active card
    function setContainerHeight() {
        if (authContainer.classList.contains('flipped')) {
            authContainer.style.height = registerCard.offsetHeight + 'px';
        } else {
            authContainer.style.height = loginCard.offsetHeight + 'px';
        }
    }

    // Show alert messages with animation
    function showAlert(alertId) {
        const alertElement = document.getElementById(alertId);
        if (alertElement) {
            alertElement.classList.add('show');
            setTimeout(() => {
                alertElement.classList.remove('show');
                alertElement.style.display = 'none'; // Hide after animation
            }, 5000); // Hide after 5 seconds
        }
    }

    // Initial check for session messages and display them
    // These need to be dynamically rendered by your templating engine (Laravel Blade in this case)
    // The following PHP logic needs to remain in your Blade file or passed as data to JS.
    /*
    @if(session('success'))
        showAlert('successAlert');
    @endif
    @if(session('error'))
        showAlert('errorAlert');
    @endif
    @if(session('status'))
        showAlert('statusAlert');
    @endif
    */

    // Switch to register form
    document.getElementById('switchToRegister').addEventListener('click', function(e) {
        e.preventDefault();
        authContainer.classList.add('flipped');
        setContainerHeight();
    });

    // Switch to login form
    document.getElementById('switchToLogin').addEventListener('click', function(e) {
        e.preventDefault();
        authContainer.classList.remove('flipped');
        setContainerHeight();
    });

    // Password confirmation validation for register form
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const passwordError = document.getElementById('passwordError');

    function validatePassword() {
        if (passwordInput.value !== confirmPasswordInput.value) {
            passwordError.textContent = 'Konfirmasi password tidak cocok!';
            confirmPasswordInput.closest('.input-group').classList.add('is-invalid');
            return false;
        } else {
            passwordError.textContent = '';
            confirmPasswordInput.closest('.input-group').classList.remove('is-invalid');
            return true;
        }
    }

    confirmPasswordInput.addEventListener('input', validatePassword);
    passwordInput.addEventListener('input', validatePassword);

    // Register form submission validation and spinner
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        // Clear previous validation styles/messages
        document.querySelectorAll('#registerForm .is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        document.querySelectorAll('#registerForm .invalid-feedback').forEach(el => {
            el.textContent = ''; // Clear text content
        });

        let isValid = true;

        // Client-side validation for required fields
        this.querySelectorAll('input[required]').forEach(input => {
            if (!input.value.trim()) {
                input.closest('.input-group').classList.add('is-invalid');
                let feedback = input.closest('.form-group').querySelector('.invalid-feedback');
                if (!feedback) { // Create if not exists (for password_confirmation primarily)
                    feedback = document.createElement('div');
                    feedback.classList.add('invalid-feedback');
                    input.closest('.form-group').appendChild(feedback);
                }
                feedback.textContent = 'Harap isi bidang ini.';
                isValid = false;
            }
        });

        if (!validatePassword()) { // Check password confirmation
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault(); // Stop form submission if validation fails
            setContainerHeight(); // Re-adjust height if errors appear/disappear
        } else {
            document.getElementById('registerSpinner').style.display = 'inline-block';
            // Disable button to prevent multiple submissions
            this.querySelector('.btn-submit').setAttribute('disabled', 'disabled');
        }
    });

    // Login form submission and spinner
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        document.getElementById('loginSpinner').style.display = 'inline-block';
        // Disable button to prevent multiple submissions
        this.querySelector('.btn-submit').setAttribute('disabled', 'disabled');
    });

    // Display any Laravel validation errors
    // This part still needs to be in your Blade file because it uses server-side PHP variables.
    /*
    @if($errors->any())
        @php
            $isRegisterError = $errors->hasAny(['nama', 'email', 'no_hp', 'username', 'password_confirmation']);
        @endphp

        @if($isRegisterError)
            authContainer.classList.add('flipped');
        @else
            authContainer.classList.remove('flipped');
        @endif

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
    */

    // Set initial container height
    setContainerHeight(); // Call initially to set correct height

    // Handle orientation changes and window resize
    function handleOrientationChange() {
        if (window.matchMedia("(max-height: 500px) and (orientation: landscape)").matches) {
            document.querySelectorAll('.tea-decoration').forEach(el => {
                el.style.display = 'none';
            });
        } else {
            document.querySelectorAll('.tea-decoration').forEach(el => {
                el.style.display = 'block';
            });
        }
        setContainerHeight(); // Re-adjust height on resize/orientation change
    }

    // Initial check
    handleOrientationChange();

    // Add event listener for orientation changes
    window.addEventListener('resize', handleOrientationChange);
});