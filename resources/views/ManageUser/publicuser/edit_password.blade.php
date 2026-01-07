<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Inquira</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .background-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 20%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 10%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 50px 40px;
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 24px;
        }

        h2 {
            color: #2c3e50;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #7f8c8d;
            font-size: 16px;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #34495e;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 16px 20px 16px 50px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: 16px;
            background: #f8f9fa;
            transition: all 0.3s ease;
            color: #2c3e50;
        }

        .form-input:focus {
            border-color: #667eea;
            background: #fff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #95a5a6;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .form-input:focus + .input-icon {
            color: #667eea;
        }

        .btn-primary {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 18px 0;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .alert {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: #fff;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            border: none;
            box-shadow: 0 4px 15px rgba(238, 90, 82, 0.3);
        }

        .success-message {
            background: linear-gradient(135deg, #51cf66, #40c057);
            color: #fff;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            border: none;
            box-shadow: 0 4px 15px rgba(64, 192, 87, 0.3);
        }

        .back-link {
            text-align: center;
            margin-top: 25px;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            color: #764ba2;
        }

        .password-toggle {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #95a5a6;
            cursor: pointer;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        .password-strength-container {
            margin-top: 10px;
        }

        .password-strength-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
            font-size: 13px;
        }

        .password-strength-text {
            font-weight: 600;
            color: #34495e;
        }

        .password-strength-value {
            font-weight: 700;
        }

        .password-strength-value.weak {
            color: #e74c3c;
        }

        .password-strength-value.medium {
            color: #f39c12;
        }

        .password-strength-value.strong {
            color: #27ae60;
        }

        .password-strength-bar {
            width: 100%;
            height: 8px;
            background-color: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
        }

        .password-strength-progress {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 4px;
        }

        .password-strength-progress.weak {
            width: 33%;
            background-color: #e74c3c;
        }

        .password-strength-progress.medium {
            width: 66%;
            background-color: #f39c12;
        }

        .password-strength-progress.strong {
            width: 100%;
            background-color: #27ae60;
        }

        .password-requirements {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .password-requirements-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            font-weight: 600;
            color: #1976d2;
            font-size: 14px;
        }

        .password-requirements-header i {
            margin-right: 8px;
            font-size: 16px;
        }

        .password-requirements ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .password-requirements li {
            display: flex;
            align-items: center;
            padding: 4px 0;
            font-size: 13px;
            color: #34495e;
        }

        .password-requirements li i {
            margin-right: 8px;
            font-size: 12px;
        }

        .password-requirements li.valid {
            color: #27ae60;
        }

        .password-requirements li.invalid {
            color: #7f8c8d;
        }

        @media (max-width: 480px) {
            .container {
                margin: 20px;
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="background-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="container">
        <div class="header">
            <div class="logo-icon">
                <i class="fas fa-key"></i>
            </div>
            <h2>Change Password</h2>
            <p class="subtitle">Update your account password</p>
        </div>

        @if(session('success'))
            <div class="success-message">
                <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert">
                <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="current_password">Current Password</label>
                <div class="input-wrapper">
                    <input type="password" class="form-input" id="current_password" name="current_password" required autocomplete="current-password">
                    <i class="fas fa-lock input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="new_password">New Password</label>
                <div class="input-wrapper">
                    <input type="password" class="form-input" id="new_password" name="new_password" required autocomplete="new-password">
                    <i class="fas fa-key input-icon"></i>
                    <i class="fas fa-eye password-toggle" id="toggleNewPassword"></i>
                </div>
                <div class="password-strength-container" id="passwordStrengthContainer" style="display: none;">
                    <div class="password-strength-label">
                        <span class="password-strength-text">Password Strength:</span>
                        <span class="password-strength-value" id="passwordStrengthText">Weak</span>
                    </div>
                    <div class="password-strength-bar">
                        <div class="password-strength-progress weak" id="passwordStrengthProgress"></div>
                    </div>
                </div>
                <div class="password-requirements">
                    <div class="password-requirements-header">
                        <i class="fas fa-info-circle"></i>
                        Password Requirements
                    </div>
                    <ul>
                        <li id="req-length" class="invalid">
                            <i class="fas fa-circle"></i>
                            Minimum 8 characters
                        </li>
                        <li id="req-uppercase" class="invalid">
                            <i class="fas fa-circle"></i>
                            At least one uppercase letter
                        </li>
                        <li id="req-lowercase" class="invalid">
                            <i class="fas fa-circle"></i>
                            At least one lowercase letter
                        </li>
                        <li id="req-number" class="invalid">
                            <i class="fas fa-circle"></i>
                            At least one number
                        </li>
                        <li id="req-special" class="invalid">
                            <i class="fas fa-circle"></i>
                            At least one special character (@$!%*?&)
                        </li>
                    </ul>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="new_password_confirmation">Confirm New Password</label>
                <div class="input-wrapper">
                    <input type="password" class="form-input" id="new_password_confirmation" name="new_password_confirmation" required autocomplete="new-password">
                    <i class="fas fa-shield-alt input-icon"></i>
                    <i class="fas fa-eye password-toggle" id="toggleConfirmPassword"></i>
                </div>
            </div>

            <button type="submit" class="btn-primary">
                <i class="fas fa-save" style="margin-right: 8px;"></i>
                Update Password
            </button>
        </form>

        <div class="back-link">
            <a href="{{ route('publicuser.profile') }}">
                <i class="fas fa-arrow-left" style="margin-right: 5px;"></i>
                Back to Profile
            </a>
        </div>
    </div>

    <script>
        // Password Strength Checker
        const newPasswordInput = document.getElementById('new_password');
        const passwordStrengthContainer = document.getElementById('passwordStrengthContainer');
        const passwordStrengthText = document.getElementById('passwordStrengthText');
        const passwordStrengthProgress = document.getElementById('passwordStrengthProgress');

        // Requirement elements
        const reqLength = document.getElementById('req-length');
        const reqUppercase = document.getElementById('req-uppercase');
        const reqLowercase = document.getElementById('req-lowercase');
        const reqNumber = document.getElementById('req-number');
        const reqSpecial = document.getElementById('req-special');

        newPasswordInput.addEventListener('input', function() {
            const password = this.value;
            
            if (password.length === 0) {
                passwordStrengthContainer.style.display = 'none';
                return;
            }

            passwordStrengthContainer.style.display = 'block';

            // Check requirements
            const hasLength = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[@$!%*?&]/.test(password);

            // Update requirement indicators
            updateRequirement(reqLength, hasLength);
            updateRequirement(reqUppercase, hasUppercase);
            updateRequirement(reqLowercase, hasLowercase);
            updateRequirement(reqNumber, hasNumber);
            updateRequirement(reqSpecial, hasSpecial);

            // Calculate strength
            let strength = 0;
            if (hasLength) strength++;
            if (hasUppercase) strength++;
            if (hasLowercase) strength++;
            if (hasNumber) strength++;
            if (hasSpecial) strength++;

            // Update strength indicator
            if (strength <= 2) {
                passwordStrengthText.textContent = 'Weak';
                passwordStrengthText.className = 'password-strength-value weak';
                passwordStrengthProgress.className = 'password-strength-progress weak';
            } else if (strength <= 4) {
                passwordStrengthText.textContent = 'Medium';
                passwordStrengthText.className = 'password-strength-value medium';
                passwordStrengthProgress.className = 'password-strength-progress medium';
            } else {
                passwordStrengthText.textContent = 'Strong';
                passwordStrengthText.className = 'password-strength-value strong';
                passwordStrengthProgress.className = 'password-strength-progress strong';
            }
        });

        function updateRequirement(element, isValid) {
            if (isValid) {
                element.classList.remove('invalid');
                element.classList.add('valid');
                element.querySelector('i').className = 'fas fa-check-circle';
            } else {
                element.classList.remove('valid');
                element.classList.add('invalid');
                element.querySelector('i').className = 'fas fa-circle';
            }
        }

        // Toggle password visibility
        const toggleNewPassword = document.getElementById('toggleNewPassword');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');

        toggleNewPassword.addEventListener('click', function() {
            togglePasswordVisibility(newPasswordInput, this);
        });

        toggleConfirmPassword.addEventListener('click', function() {
            const confirmPasswordInput = document.getElementById('new_password_confirmation');
            togglePasswordVisibility(confirmPasswordInput, this);
        });

        function togglePasswordVisibility(input, icon) {
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
