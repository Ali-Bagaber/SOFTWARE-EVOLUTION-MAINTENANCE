<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Inquira</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            color: #2d3748;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 48px;
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 100%;
            max-width: 500px;
            position: relative;
            overflow: hidden;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.05) 0%, transparent 70%);
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(180deg); }
        }

        .form-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
        }

        .logo i {
            font-size: 36px;
            color: #667eea;
        }

        .form-title {
            font-size: 28px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .form-title i {
            color: #667eea;
        }

        .form-subtitle {
            color: #718096;
            font-size: 16px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 28px;
            position: relative;
            z-index: 1;
        }

        .form-label {
            display: block;
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid rgba(102, 126, 234, 0.1);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            font-size: 16px;
            color: #2d3748;
            transition: all 0.3s ease;
            position: relative;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .form-input::placeholder {
            color: #a0aec0;
        }

        .password-input-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #718096;
            cursor: pointer;
            font-size: 18px;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        .form-button {
            width: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 18px 24px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            z-index: 1;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .form-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .form-button:active {
            transform: translateY(-1px);
        }

        .form-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            margin-top: 24px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .back-link:hover {
            color: #764ba2;
            transform: translateX(-4px);
        }

        .password-strength {
            margin-top: 8px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .strength-weak { color: #f56565; }
        .strength-medium { color: #ed8936; }
        .strength-strong { color: #48bb78; }

        .password-requirements {
            background: rgba(102, 126, 234, 0.05);
            border: 1px solid rgba(102, 126, 234, 0.1);
            border-radius: 12px;
            padding: 16px;
            margin-top: 12px;
            font-size: 14px;
        }

        .password-requirements h4 {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .requirement {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
            color: #718096;
        }

        .requirement.met {
            color: #48bb78;
        }

        .requirement i {
            font-size: 12px;
            width: 16px;
        }

        .alert {
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }

        .alert-success {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.3);
        }

        .alert-error {
            background: linear-gradient(135deg, #f56565, #e53e3e);
            color: white;
            box-shadow: 0 8px 25px rgba(245, 101, 101, 0.3);
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 32px 24px;
                margin: 10px;
            }
            
            .form-title {
                font-size: 24px;
            }
            
            .logo {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <div class="logo">
                <i class="fas fa-search"></i>
                Inquira
            </div>
            <h1 class="form-title">
                <i class="fas fa-key"></i>
                Change Password
            </h1>
            <p class="form-subtitle">
                Update your agency account password securely
            </p>
        </div>        <form id="changePasswordForm" method="POST" action="{{ route('agency.password.change') }}">
            @csrf
            
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif
              @if($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="form-group">
                <label for="newPassword" class="form-label">New Password</label>
                <div class="password-input-wrapper">
                    <input 
                        type="password" 
                        id="newPassword" 
                        name="new_password" 
                        class="form-input" 
                        placeholder="Enter your new password"
                        required
                        oninput="checkPasswordStrength()"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('newPassword')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div id="passwordStrength" class="password-strength"></div>
            </div>

            <div class="form-group">
                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                <div class="password-input-wrapper">
                    <input 
                        type="password" 
                        id="confirmPassword" 
                        name="new_password_confirmation" 
                        class="form-input" 
                        placeholder="Confirm your new password"
                        required
                        oninput="checkPasswordMatch()"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div id="passwordMatch" class="password-strength"></div>
            </div>

            <button type="submit" class="form-button" id="submitBtn">
                <i class="fas fa-shield-alt"></i>
                Update Agency Password
            </button>
        </form>        <a href="{{ route('agency.profile') }}" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Back to Agency Profile
        </a>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const button = field.nextElementSibling;
            const icon = button.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                field.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('newPassword').value;
            const strengthDiv = document.getElementById('passwordStrength');
            
            if (password.length === 0) {
                strengthDiv.textContent = '';
                strengthDiv.className = 'password-strength';
            } else if (password.length < 6) {
                strengthDiv.textContent = 'Weak Password';
                strengthDiv.className = 'password-strength strength-weak';
            } else if (password.length < 10) {
                strengthDiv.textContent = 'Medium Password';
                strengthDiv.className = 'password-strength strength-medium';
            } else {
                strengthDiv.textContent = 'Strong Password';
                strengthDiv.className = 'password-strength strength-strong';
            }
            
            checkFormValidity();
        }

        function checkPasswordMatch() {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const matchDiv = document.getElementById('passwordMatch');
            
            if (confirmPassword.length === 0) {
                matchDiv.textContent = '';
                matchDiv.className = 'password-strength';
            } else if (newPassword === confirmPassword) {
                matchDiv.textContent = 'Passwords Match';
                matchDiv.className = 'password-strength strength-strong';
            } else {
                matchDiv.textContent = 'Passwords Do Not Match';
                matchDiv.className = 'password-strength strength-weak';
            }
            
            checkFormValidity();
        }        function checkFormValidity() {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const submitBtn = document.getElementById('submitBtn');
            
            const passwordsMatch = newPassword === confirmPassword && confirmPassword.length > 0;
            const newPasswordFilled = newPassword.length >= 6; // Minimum 6 characters
            
            if (newPasswordFilled && passwordsMatch) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-shield-alt"></i> Update Agency Password';
            } else {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-shield-alt"></i> Update Agency Password';
            }
        }        // Form submission handler - Real form submission
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const submitBtn = document.getElementById('submitBtn');
            
            // Client-side validation
            if (newPassword.length < 6) {
                e.preventDefault();
                alert('New password must be at least 6 characters long');
                return;
            }
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('New passwords do not match');
                return;
            }
            
            // Show loading state while form submits
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating Password...';
            submitBtn.disabled = true;
            
            // Allow form to submit to server
        });        // Add input event listeners for real-time validation
        document.getElementById('newPassword').addEventListener('input', () => {
            checkPasswordStrength();
            checkPasswordMatch();
        });
        document.getElementById('confirmPassword').addEventListener('input', checkPasswordMatch);
    </script>
</body>
</html>