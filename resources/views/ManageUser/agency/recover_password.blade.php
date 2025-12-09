<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery - Inquira</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #334155;
        }

        .recovery-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            width: 100%;
            max-width: 480px;
            margin: 20px;
        }

        .recovery-header {
            background: linear-gradient(135deg, #059669, #34d399);
            color: white;
            padding: 40px 32px 32px;
            text-align: center;
            position: relative;
        }

        .recovery-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="pattern" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23pattern)"/></svg>');
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }

        .logo i {
            font-size: 32px;
            color: #34d399;
        }

        .recovery-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .recovery-subtitle {
            opacity: 0.9;
            font-size: 16px;
            line-height: 1.5;
            position: relative;
            z-index: 1;
        }

        .recovery-form {
            padding: 40px 32px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            color: #374151;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            background: #f9fafb;
            transition: all 0.3s ease;
            position: relative;
        }

        .form-input:focus {
            outline: none;
            border-color: #059669;
            background: white;
            box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1);
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
            color: #6b7280;
            cursor: pointer;
            font-size: 18px;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #059669;
        }

        .password-strength {
            margin-top: 12px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .strength-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .strength-bar {
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-text {
            font-size: 12px;
            font-weight: 500;
        }

        .strength-weak { background: #ef4444; color: #dc2626; }
        .strength-medium { background: #f59e0b; color: #d97706; }
        .strength-strong { background: #10b981; color: #059669; }

        .password-requirements {
            margin-top: 12px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .requirements-title {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            margin-bottom: 4px;
            color: #64748b;
        }

        .requirement-item.met {
            color: #059669;
        }

        .requirement-item i {
            font-size: 10px;
        }        .error-message {
            margin-top: 8px;
            padding: 12px 16px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            color: #dc2626;
            font-size: 14px;
            display: none;
        }

        .error-message.show {
            display: block;
        }
        
        #validationMessages {
            margin-top: 16px;
        }
        
        #passwordMismatch, #emptyFields {
            display: none;
            margin-bottom: 10px;
        }

        .success-message {
            margin-top: 8px;
            padding: 12px 16px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            color: #059669;
            font-size: 14px;
            display: none;
        }

        .success-message.show {
            display: block;
        }

        .recovery-btn {
            width: 100%;
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            border: none;
            padding: 18px 24px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .recovery-btn:hover {
            background: linear-gradient(135deg, #047857, #059669);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(5, 150, 105, 0.3);
        }        .recovery-btn:active {
            transform: translateY(0);
            background: #047857;  /* Darker green to show active state */
        }

        .recovery-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
            opacity: 0.8;
        }
        
        /* Add a pulse animation for clicked state */
        @keyframes btnPulse {
            0% { transform: scale(1); }
            50% { transform: scale(0.98); }
            100% { transform: scale(1); }
        }
        
        .recovery-btn.clicked {
            animation: btnPulse 0.3s ease;
        }

        .back-link {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
        }

        .back-link a {
            color: #059669;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            color: #047857;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            display: none;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .recovery-container {
                margin: 10px;
                border-radius: 12px;
            }

            .recovery-header {
                padding: 32px 24px 24px;
            }

            .recovery-form {
                padding: 32px 24px;
            }

            .logo {
                font-size: 24px;
            }

            .logo i {
                font-size: 28px;
            }

            .recovery-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="recovery-container">        <div class="recovery-header">
            <div class="logo">
                <i class="fas fa-search"></i>
                Inquira
            </div>
            <h1 class="recovery-title">Set New Password</h1>
            <p class="recovery-subtitle">Create a strong password to secure your agency account</p>        </div>        <form class="recovery-form" id="recoveryForm" action="{{ route('agency.password.update') }}" method="POST" novalidate>
            @csrf<input type="hidden" name="email" value="{{ $email }}">
            
            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" id="name" name="name" class="form-input" placeholder="Enter your full name" required value="{{ auth()->user()->name ?? '' }}">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">New Password</label>
                <div class="password-input-wrapper">
                    <input type="password" id="password" name="password" class="form-input" placeholder="Enter your new password" required>
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <div class="password-input-wrapper">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Confirm your new password" required>
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="contact_number" class="form-label">Contact Number</label>
                <input type="text" id="contact_number" name="contact_number" class="form-input" placeholder="Enter your contact number" required value="{{ auth()->user()->contact_number ?? '' }}">
            </div>            <!-- Add validation message area -->
            <div id="validationMessages">
                <!-- Password mismatch message -->
                <div id="passwordMismatch" class="error-message">
                    Passwords don't match. Please check and try again.
                </div>
                
                <!-- Empty fields message -->
                <div id="emptyFields" class="error-message">
                    Please fill in all required fields.
                </div>
            </div>
            
            <button type="submit" class="recovery-btn" id="submitBtn">
                UPDATE INFORMATION
            </button>

            @if(session('error'))
                <div class="error-message show">{{ session('error') }}</div>
            @endif

            @if(session('success'))
                <div class="success-message show">{{ session('success') }}</div>
            @endif
        </form>
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

        function checkPasswordStrength(password) {
            // Simple check - just return true if password exists
            return password.length > 0;
        }        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            
            // Simply return whether passwords match
            if (confirmation.length === 0) {
                return false;
            }
            
            return password === confirmation;
        }

        function updateSubmitButton() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const contactNumber = document.getElementById('contact_number').value;
            const name = document.getElementById('name').value;
            const submitBtn = document.getElementById('submitBtn');
            
            // Always make sure button is clickable regardless of validation state
            // We'll handle validation when the form is submitted
            submitBtn.disabled = false;
            
            // Visual indication if passwords match
            if (password && confirmation) {
                if (password === confirmation) {
                    document.getElementById('password_confirmation').style.borderColor = '#10b981';
                } else {
                    document.getElementById('password_confirmation').style.borderColor = '#ef4444';
                }
            }
        }

        // Event listeners
        document.getElementById('password').addEventListener('input', function() {
            checkPasswordMatch();
            updateSubmitButton();
        });        document.getElementById('password_confirmation').addEventListener('input', function() {
            checkPasswordMatch();
            updateSubmitButton();
        });        // Form validation and submission
        document.getElementById('recoveryForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            
            // Get form elements
            const form = this;
            const submitBtn = document.getElementById('submitBtn');
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const name = document.getElementById('name').value;
            const contactNumber = document.getElementById('contact_number').value;
            
            // Hide all validation messages
            document.getElementById('passwordMismatch').style.display = 'none';
            document.getElementById('emptyFields').style.display = 'none';
            
            // Check for empty required fields
            if (!name || !password || !confirmation || !contactNumber) {
                document.getElementById('emptyFields').style.display = 'block';
                return false;
            }
            
            // Check if passwords match
            if (password !== confirmation) {
                document.getElementById('passwordMismatch').style.display = 'block';
                return false;
            }
            
            // Add visual feedback to button
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> UPDATING...';
            submitBtn.classList.add('clicked');
            
            // Submit the form after visual feedback
            setTimeout(function() {
                form.submit();
            }, 100);
        });        // Initialize
        updateSubmitButton();
        
        // Add input event listeners to all form fields
        document.getElementById('name').addEventListener('input', updateSubmitButton);
        document.getElementById('contact_number').addEventListener('input', updateSubmitButton);
    </script>
</body>
</html>