<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MCMC</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @if(config('recaptcha.enabled') && config('recaptcha.site_key'))
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 500px;
            position: relative;
            overflow: hidden;
        }

        .register-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .register-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .register-header h1 {
            color: #2d3748;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .register-header p {
            color: #718096;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: #4a5568;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f7fafc;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a0aec0;
            font-size: 18px;
            user-select: none;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        .register-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .register-btn:active {
            transform: translateY(0);
        }

        .form-links {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .form-links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            margin: 0 15px;
            transition: color 0.3s ease;
        }

        .form-links a:hover {
            color: #764ba2;
        }

        .error-message {
            background: #fed7d7;
            color: #9b2c2c;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #f56565;
        }

        .success-message {
            background: #c6f6d5;
            color: #22543d;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #48bb78;
        }

        .role-indicator {
            background: #e6fffa;
            color: #234e52;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            text-align: center;
            margin-bottom: 20px;
            border: 1px solid #81e6d9;
        }

        .password-strength-wrapper {
            margin-top: 8px;
        }

        .password-strength-label {
            font-size: 13px;
            color: #4a5568;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .password-strength-label span {
            font-weight: 700;
        }

        .password-strength-label.weak span {
            color: #e53e3e;
        }

        .password-strength-label.medium span {
            color: #ed8936;
        }

        .password-strength-label.strong span {
            color: #38a169;
        }

        .password-strength-bar {
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 12px;
        }

        .password-strength-fill {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 3px;
        }

        .password-strength-fill.weak {
            width: 33%;
            background: #e53e3e;
        }

        .password-strength-fill.medium {
            width: 66%;
            background: #ed8936;
        }

        .password-strength-fill.strong {
            width: 100%;
            background: #38a169;
        }

        .password-requirements {
            background: #edf2f7;
            border-radius: 8px;
            padding: 12px 15px;
            margin-top: 10px;
            border-left: 3px solid #4299e1;
        }

        .password-requirements-title {
            display: flex;
            align-items: center;
            font-size: 13px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .password-requirements-title i {
            color: #4299e1;
            margin-right: 6px;
        }

        .password-requirements ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .password-requirements li {
            font-size: 12px;
            color: #718096;
            padding: 4px 0;
            display: flex;
            align-items: center;
        }

        .password-requirements li::before {
            content: '•';
            color: #a0aec0;
            font-weight: bold;
            margin-right: 8px;
        }

        .password-requirements li.valid {
            color: #38a169;
        }

        .password-requirements li.valid::before {
            content: '✓';
            color: #38a169;
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 30px 20px;
                margin: 10px;
            }

            .register-header h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1><i class="fas fa-user-plus"></i> Create Account</h1>
            <p>Join our platform to submit inquiries and engage with services</p>
        </div>

        @if(session('error'))
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="success-message">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Please fix the following errors:</strong>
                <ul style="margin: 8px 0 0 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('publicuser.register.submit') }}">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" class="form-input" 
                       placeholder="Enter your full name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-input" 
                       placeholder="Enter your email address" value="{{ old('email') }}" required>
                <div class="role-indicator" id="roleIndicator" style="display: none;">
                    Account type will be automatically determined based on your email domain
                </div>
            </div>

            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="tel" name="contact_number" id="contact_number" class="form-input" 
                       placeholder="Enter your contact number" value="{{ old('contact_number') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" class="form-input" 
                           placeholder="Enter your password" required>
                    <span class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                <div class="password-strength-wrapper">
                    <div class="password-strength-label" id="strengthLabel">
                        Password Strength: <span id="strengthText">-</span>
                    </div>
                    <div class="password-strength-bar">
                        <div class="password-strength-fill" id="strengthBar"></div>
                    </div>
                </div>
                <div class="password-requirements">
                    <div class="password-requirements-title">
                        <i class="fas fa-info-circle"></i>
                        Password Requirements
                    </div>
                    <ul>
                        <li id="req-length">Minimum 8 characters</li>
                        <li id="req-uppercase">At least one uppercase letter</li>
                        <li id="req-lowercase">At least one lowercase letter</li>
                        <li id="req-number">At least one number</li>
                        <li id="req-special">At least one special character (@$!%*?&)</li>
                    </ul>
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" 
                           placeholder="Re-enter your password" required>
                    <span class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>

            @if(config('recaptcha.enabled') && config('recaptcha.site_key'))
            <div class="form-group" style="display: flex; justify-content: center; margin: 25px 0;">
                <div class="g-recaptcha" data-sitekey="{{ config('recaptcha.site_key') }}"></div>
            </div>
            @endif

            <button type="submit" class="register-btn" id="submitBtn">
                <i class="fas fa-user-plus"></i> Create Account
            </button>

            <div class="form-links">
                <a href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt"></i> Already have an account? Sign In
                </a>
            </div>
        </form>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const toggle = field.parentElement.querySelector('.password-toggle i');
            
            if (field.type === 'password') {
                field.type = 'text';
                toggle.className = 'fas fa-eye-slash';
            } else {
                field.type = 'password';
                toggle.className = 'fas fa-eye';
            }
        }

        // Show role indicator when email is typed
        document.getElementById('email').addEventListener('input', function() {
            const email = this.value;
            const indicator = document.getElementById('roleIndicator');
            
            if (email.length > 0) {
                indicator.style.display = 'block';
                
                if (email.endsWith('@admin.com')) {
                    indicator.innerHTML = '<i class="fas fa-crown"></i> This email will create an Administrator account';
                    indicator.style.background = '#fef5e7';
                    indicator.style.color = '#744210';
                    indicator.style.borderColor = '#f6e05e';
                } else if (email.endsWith('@agency') || email.endsWith('@agency.com')) {
                    indicator.innerHTML = '<i class="fas fa-building"></i> This email will create an Agency account';
                    indicator.style.background = '#e6fffa';
                    indicator.style.color = '#234e52';
                    indicator.style.borderColor = '#81e6d9';
                } else {
                    indicator.innerHTML = '<i class="fas fa-user"></i> This email will create a Public User account';
                    indicator.style.background = '#ebf8ff';
                    indicator.style.color = '#2a4365';
                    indicator.style.borderColor = '#90cdf4';
                }
            } else {
                indicator.style.display = 'none';
            }
        });

        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthLabel = document.getElementById('strengthLabel');
            const strengthText = document.getElementById('strengthText');
            
            // Check requirements
            const hasLength = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[@$!%*?&]/.test(password);
            
            // Update requirement indicators
            document.getElementById('req-length').className = hasLength ? 'valid' : '';
            document.getElementById('req-uppercase').className = hasUppercase ? 'valid' : '';
            document.getElementById('req-lowercase').className = hasLowercase ? 'valid' : '';
            document.getElementById('req-number').className = hasNumber ? 'valid' : '';
            document.getElementById('req-special').className = hasSpecial ? 'valid' : '';
            
            // Calculate strength
            const requirementsMet = [hasLength, hasUppercase, hasLowercase, hasNumber, hasSpecial].filter(Boolean).length;
            
            // Remove all strength classes
            strengthBar.className = 'password-strength-fill';
            strengthLabel.className = 'password-strength-label';
            
            if (password.length === 0) {
                strengthText.textContent = '-';
            } else if (requirementsMet <= 2) {
                strengthBar.classList.add('weak');
                strengthLabel.classList.add('weak');
                strengthText.textContent = 'Weak';
            } else if (requirementsMet <= 4) {
                strengthBar.classList.add('medium');
                strengthLabel.classList.add('medium');
                strengthText.textContent = 'Medium';
            } else {
                strengthBar.classList.add('strong');
                strengthLabel.classList.add('strong');
                strengthText.textContent = 'Strong';
            }
        });

        // Form validation with reCAPTCHA v2
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match. Please check and try again.');
                return false;
            }
            
            // Check all password requirements
            const hasLength = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[@$!%*?&]/.test(password);
            
            if (!hasLength || !hasUppercase || !hasLowercase || !hasNumber || !hasSpecial) {
                e.preventDefault();
                alert('Password must meet all requirements:\n• Minimum 8 characters\n• At least one uppercase letter\n• At least one lowercase letter\n• At least one number\n• At least one special character (@$!%*?&)');
                return false;
            }
            
            @if(config('recaptcha.enabled') && config('recaptcha.site_key'))
            // Check if reCAPTCHA is completed
            const recaptchaResponse = grecaptcha.getResponse();
            if (!recaptchaResponse) {
                e.preventDefault();
                alert('🔒 Please complete the reCAPTCHA verification.');
                return false;
            }
            @endif
            
            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';
        });
    </script>
</body>
</html>
