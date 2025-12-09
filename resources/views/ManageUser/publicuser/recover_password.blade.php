<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery - Inquira</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #4a6fa5, #516a8b);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
        }

        .recovery-container {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand {
            color: #4a9eff;
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .welcome-text {
            color: #2d3748;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .subtitle {
            color: #718096;
            font-size: 14px;
            margin-bottom: 0;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #2d3748;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            background-color: #fffbf0;
            color: #2d3748;
            box-sizing: border-box;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #4a9eff;
            box-shadow: 0 0 0 3px rgba(74, 158, 255, 0.1);
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #718096;
            font-size: 16px;
            padding: 4px;
            border-radius: 4px;
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: #4a9eff;
        }

        .recovery-btn {
            width: 100%;
            padding: 14px;
            background: #4a9eff;
            color: white;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
            text-transform: uppercase;
        }

        .recovery-btn:hover {
            background: #3182ce;
            transform: translateY(-1px);
        }

        .recovery-btn:active {
            transform: translateY(0);
        }

        .recovery-btn:disabled {
            background: #a0aec0;
            cursor: not-allowed;
            transform: none;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            margin-top: 24px;
            font-size: 13px;
        }

        .footer-links a {
            color: #4a9eff;
            text-decoration: none;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        .copyright {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: #a0aec0;
        }

        /* Enhanced Alert Styles */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid;
            position: relative;
            animation: slideDown 0.3s ease-out;
        }

        .alert-success {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            color: #166534;
            border-color: #bbf7d0;
        }

        .alert-error {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            color: #dc2626;
            border-color: #fecaca;
        }

        .alert-info {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            color: #0c4a6e;
            border-color: #7dd3fc;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            color: #92400e;
            border-color: #fde68a;
        }

        .alert-icon {
            display: inline-block;
            margin-right: 8px;
            font-size: 16px;
        }

        .alert-close {
            position: absolute;
            top: 8px;
            right: 12px;
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s ease;
        }

        .alert-close:hover {
            opacity: 1;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .email-sent-message {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px solid #0ea5e9;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            animation: pulse 2s infinite;
        }

        .email-sent-message .icon {
            font-size: 48px;
            color: #0ea5e9;
            margin-bottom: 12px;
        }

        .email-sent-message h3 {
            color: #0c4a6e;
            margin: 0 0 8px 0;
            font-size: 18px;
        }

        .email-sent-message p {
            color: #075985;
            margin: 0;
            font-size: 14px;
            line-height: 1.5;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        .password-requirements {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            margin-top: 8px;
            font-size: 12px;
            color: #64748b;
        }

        .requirement {
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 4px 0;
        }

        .requirement.met {
            color: #16a34a;
        }

        .requirement-icon {
            font-size: 10px;
        }

        .btn-loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            margin: auto;
            border: 2px solid white;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            gap: 8px;
            align-items: center;
        }

        .step-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .step {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #e2e8f0;
            transition: all 0.3s ease;
            position: relative;
        }

        .step.active {
            background: #4a9eff;
            transform: scale(1.2);
            box-shadow: 0 0 0 3px rgba(74, 158, 255, 0.2);
        }

        .step.completed {
            background: #48bb78;
            transform: scale(1.1);
        }

        .step.completed::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 8px;
            font-weight: bold;
        }

        .step-label {
            font-size: 10px;
            color: #718096;
            font-weight: 500;
            text-align: center;
            min-width: 60px;
        }

        .step-label.active {
            color: #4a9eff;
            font-weight: 600;
        }

        .step-label.completed {
            color: #48bb78;
            font-weight: 600;
        }

        .step-connector {
            width: 30px;
            height: 2px;
            background: #e2e8f0;
            transition: background-color 0.3s ease;
            margin: 0 10px;
        }

        .step-connector.completed {
            background: #48bb78;
        }

        @media (max-width: 480px) {
            .recovery-container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .brand {
                font-size: 28px;
            }
            
            .welcome-text {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    @php
        // Always define $step to avoid undefined variable errors
        if (!isset($step)) {
            $step = session('recovery_email') ? 'password' : 'email';
        }
        
        // Ensure we have the email from session if we're on the password step
        if ($step === 'password' && !isset($email)) {
            $email = session('recovery_email');
        }
    @endphp

    <div class="recovery-container">
        <div class="header">
            <div class="brand">Inquira</div>
            <div class="welcome-text">Password Recovery</div>
            <p class="subtitle" id="subtitleText">
                @if($step === 'email')
                    Enter your email address to receive a password reset link
                @elseif($step === 'password')
                    Great! Now create your new password below
                @else
                    Check your email and enter your new password below
                @endif
            </p>
        </div>

        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step-wrapper">
                <div class="step {{ $step === 'email' ? 'active' : 'completed' }}" id="step1"></div>
                <div class="step-label {{ $step === 'email' ? 'active' : 'completed' }}">Enter Email</div>
            </div>
            <div class="step-connector {{ $step === 'password' ? 'completed' : '' }}"></div>
            <div class="step-wrapper">
                <div class="step {{ $step === 'password' ? 'active' : '' }}" id="step2"></div>
                <div class="step-label {{ $step === 'password' ? 'active' : '' }}">New Password</div>
            </div>
        </div>

        <!-- Dynamic Messages -->
        <div id="messageContainer">
            @if ($errors->any())
                <div class="alert alert-error" id="errorAlert">
                    <span class="alert-icon">⚠️</span>
                    <div style="display: inline-block;">
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                    <button type="button" class="alert-close" onclick="closeAlert('errorAlert')">&times;</button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error" id="sessionErrorAlert">
                    <span class="alert-icon">❌</span>
                    {{ session('error') }}
                    <button type="button" class="alert-close" onclick="closeAlert('sessionErrorAlert')">&times;</button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success" id="successAlert">
                    <span class="alert-icon">✅</span>
                    {{ session('success') }}
                    <button type="button" class="alert-close" onclick="closeAlert('successAlert')">&times;</button>
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info" id="infoAlert">
                    <span class="alert-icon">ℹ️</span>
                    {{ session('info') }}
                    <button type="button" class="alert-close" onclick="closeAlert('infoAlert')">&times;</button>
                </div>
            @endif
        </div>

        <!-- Email Sent Confirmation (shown after successful email submission) -->
        @if($step === 'password' && isset($email))
            <div class="email-sent-message">
                <div class="icon">📧</div>
                <h3>Email Verified Successfully!</h3>
                <p>Password reset instructions have been sent to <strong>{{ $email }}</strong></p>
                <p style="margin-top: 8px; font-size: 13px;">You can now proceed to create your new password below.</p>
            </div>
        @endif

        <!-- Email Step Form -->
        <form id="emailForm" method="POST" action="{{ route('publicuser.recover.submit') }}" style="display: {{ $step === 'email' ? 'block' : 'none' }};">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required value="{{ old('email', $email ?? '') }}" 
                       placeholder="Enter your registered email address">
                <small style="color: #718096; font-size: 12px; margin-top: 4px; display: block;">
                    We'll verify this email is registered in our system
                </small>
            </div>
            <button type="submit" class="recovery-btn" id="emailSubmitBtn">
                <span class="btn-text">Verify Email & Continue</span>
            </button>
        </form>

        <!-- Password Reset Step Form -->
        <form method="POST" action="{{ route('publicuser.recover.password.submit') }}" id="passwordForm" style="display: {{ $step === 'password' ? 'block' : 'none' }};">
            @csrf
            <input type="hidden" name="email" value="{{ $email ?? '' }}">
            
            <div class="form-group">
                <label for="newPassword">New Password</label>
                <div class="password-wrapper">
                    <input type="password" id="newPassword" name="new_password" required 
                           placeholder="Enter your new password">
                    <button type="button" class="password-toggle" onclick="togglePassword('newPassword', this)">
                        👁️
                    </button>
                </div>
                <div class="password-requirements">
                    <div class="requirement" id="lengthReq">
                        <span class="requirement-icon">○</span>
                        At least 6 characters
                    </div>
                    <div class="requirement" id="letterReq">
                        <span class="requirement-icon">○</span>
                        Contains letters
                    </div>
                    <div class="requirement" id="numberReq">
                        <span class="requirement-icon">○</span>
                        Contains numbers (recommended)
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="confirmPassword">Confirm New Password</label>
                <div class="password-wrapper">
                    <input type="password" id="confirmPassword" name="new_password_confirmation" required
                           placeholder="Confirm your new password">
                    <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword', this)">
                        👁️
                    </button>
                </div>
            </div>

            <button type="submit" class="recovery-btn" id="passwordSubmitBtn">
                <span class="btn-text">Update Password</span>
            </button>
        </form>

        <div class="footer-links">
            <a href="{{ url('/login') }}">← Back to Login</a>
            @php
                $lastLogin = null;
                $lookupEmail = old('email', $email ?? null);
                if ($lookupEmail) {
                    $user = \App\Models\User::where('email', $lookupEmail)->first();
                    if ($user && $user->last_login_at) {
                        $lastLogin = \Carbon\Carbon::parse($user->last_login_at)->format('Y-m-d H:i:s');
                    }
                }
            @endphp
            @if($lastLogin)
                <span style="margin-left: 10px; color: #718096; font-size: 13px;">
                    Last login: {{ $lastLogin }}
                </span>
            @endif
        </div>

        <div class="copyright">
            © 2025 Inquira. All rights reserved.
        </div>
    </div>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            button.textContent = isPassword ? '🙈' : '👁️';
        }

        function closeAlert(alertId) {
            const alert = document.getElementById(alertId);
            if (alert) {
                alert.style.animation = 'slideUp 0.3s ease-out forwards';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }
        }

        function showMessage(type, message) {
            const messageContainer = document.getElementById('messageContainer');
            const alertId = 'dynamicAlert' + Date.now();
            
            const icons = {
                success: '✅',
                error: '❌',
                info: 'ℹ️',
                warning: '⚠️'
            };
            
            const alertHTML = `
                <div class="alert alert-${type}" id="${alertId}">
                    <span class="alert-icon">${icons[type] || 'ℹ️'}</span>
                    ${message}
                    <button type="button" class="alert-close" onclick="closeAlert('${alertId}')">&times;</button>
                </div>
            `;
            
            messageContainer.insertAdjacentHTML('beforeend', alertHTML);
            
            // Auto-close after 5 seconds
            setTimeout(() => {
                closeAlert(alertId);
            }, 5000);
        }

        // Enhanced form submission handlers
        document.getElementById('emailForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('emailSubmitBtn');
            const email = document.getElementById('email').value;
            
            if (!email || !validateEmail(email)) {
                e.preventDefault();
                showMessage('error', 'Please enter a valid email address.');
                return;
            }
            
            // Add loading state
            btn.classList.add('btn-loading');
            btn.querySelector('.btn-text').textContent = 'Verifying Email...';
            
            // Show immediate feedback
            setTimeout(() => {
                showMessage('info', `Verifying ${email} and preparing next step...`);
            }, 100);
        });

        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('passwordSubmitBtn');
            const password = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            const errors = validatePassword(password, confirmPassword);
            if (errors.length > 0) {
                e.preventDefault();
                showMessage('error', errors.join('<br>'));
                return;
            }
            
            // Add loading state
            btn.classList.add('btn-loading');
            btn.querySelector('.btn-text').textContent = 'Updating...';
            
            showMessage('info', 'Updating your password...');
        });

        // Password strength checker
        document.getElementById('newPassword')?.addEventListener('input', function() {
            const password = this.value;
            
            // Length requirement
            const lengthReq = document.getElementById('lengthReq');
            if (password.length >= 6) {
                lengthReq.classList.add('met');
                lengthReq.querySelector('.requirement-icon').textContent = '✓';
            } else {
                lengthReq.classList.remove('met');
                lengthReq.querySelector('.requirement-icon').textContent = '○';
            }
            
            // Letter requirement
            const letterReq = document.getElementById('letterReq');
            if (/[a-zA-Z]/.test(password)) {
                letterReq.classList.add('met');
                letterReq.querySelector('.requirement-icon').textContent = '✓';
            } else {
                letterReq.classList.remove('met');
                letterReq.querySelector('.requirement-icon').textContent = '○';
            }
            
            // Number requirement
            const numberReq = document.getElementById('numberReq');
            if (/[0-9]/.test(password)) {
                numberReq.classList.add('met');
                numberReq.querySelector('.requirement-icon').textContent = '✓';
            } else {
                numberReq.classList.remove('met');
                numberReq.querySelector('.requirement-icon').textContent = '○';
            }
        });

        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function validatePassword(password, confirmPassword) {
            const errors = [];
            
            if (password.length < 6) {
                errors.push('Password must be at least 6 characters long');
            }
            
            if (password !== confirmPassword) {
                errors.push('Passwords do not match');
            }
            
            if (!/[a-zA-Z]/.test(password)) {
                errors.push('Password must contain at least one letter');
            }
            
            return errors;
        }

        // Auto-close alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert && alert.parentNode) {
                        closeAlert(alert.id);
                    }
                }, 5000);
            });
        });
    </script>
</body>
</html>