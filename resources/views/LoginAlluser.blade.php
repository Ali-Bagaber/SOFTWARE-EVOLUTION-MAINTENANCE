<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Inquira</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 50%, #2c3e50 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            padding: 50px 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            max-width: 450px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3498db, #2ecc71);
        }

        .logo {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo h1 {
            color: #3498db;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .logo p {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .form-header h2 {
            color: #2c3e50;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-header p {
            color: #7f8c8d;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 15px 18px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 16px;
            font-family: inherit;
            background: #f8f9fa;
            transition: all 0.3s ease;
            color: #2c3e50;
        }

        .form-group input:focus {
            outline: none;
            border-color: #3498db;
            background: white;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 18px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 20px;
            user-select: none;
            color: #7f8c8d;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #3498db;
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            background: #3498db;
            color: white;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .login-btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .form-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        .form-links a {
            color: #3498db;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .form-links a:hover {
            color: #2980b9;
            text-decoration: underline;
        }

        .alert {
            padding: 15px 18px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .admin-indicator {
            display: none;
            background: linear-gradient(90deg, #e74c3c, #c0392b);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 20px;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #7f8c8d;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 40px 30px;
                margin: 20px;
            }
            
            .logo h1 {
                font-size: 28px;
            }
            
            .form-header h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>Inquira</h1>
            <pAdmin Portal Access</p>
        </div>

        <div class="form-header">
            <h2>Welcome Back</h2>
            <p>Sign in to your account</p>
        </div>

        <div class="admin-indicator" id="adminIndicator">
            🔐 Admin Login Mode Activated
        </div>

        @if(session('error'))
            <div class="alert error">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif        <form method="POST" id="loginForm" action="{{ route('publicuser.login.submit') }}">
            @csrf
            
            <div class="form-group">
                <label for="loginEmail">Email Address</label>
                <input type="email" name="email" id="loginEmail" placeholder="Enter your email address" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    <span class="password-toggle" onclick="togglePassword()">👁️</span>
                </div>
            </div>

            <button class="login-btn" type="submit">Sign In</button>
            <input type="hidden" name="is_admin_login" id="is_admin_login" value="0">

            <div class="form-links">
                <a href="{{ route('publicuser.recover') }}">Forgot Password?</a>
                <a href="{{ route('publicuser.register') }}">Create Account</a>
            </div>
        </form>

        <div class="form-footer">
            <p>© 2025 Inquira. All rights reserved.</p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = event.currentTarget;
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            icon.textContent = isPassword ? '🙈' : '👁️';
        }        // Dynamically set form action and show admin indicator
        document.getElementById('loginEmail').addEventListener('input', function() {
            const form = document.getElementById('loginForm');
            const adminIndicator = document.getElementById('adminIndicator');
            const isAdminInput = document.getElementById('is_admin_login');
            const email = this.value.trim().toLowerCase();
            if (email.endsWith('@admin.com')) {
                form.action = "{{ route('admin.login.submit') }}";
                isAdminInput.value = "1";
                if (adminIndicator) {
                    adminIndicator.style.display = 'block';
                }
                const adminBanner = document.querySelector('.admin-banner');
                if (!adminBanner) {
                    const banner = document.createElement('div');
                    banner.className = 'admin-banner';
                    banner.style.backgroundColor = '#e74c3c';
                    banner.style.color = 'white';
                    banner.style.padding = '10px';
                    banner.style.textAlign = 'center';
                    banner.style.marginBottom = '20px';
                    banner.style.borderRadius = '5px';
                    banner.innerHTML = '<strong>Admin Login Mode Activated</strong>';
                    form.insertBefore(banner, form.firstChild);
                }
            }else {
                form.action = "{{ route('publicuser.login.submit') }}";
                isAdminInput.value = "0";
                if (adminIndicator) {
                    adminIndicator.style.display = 'none';
                }
                const adminBanner = document.querySelector('.admin-banner');
                if (adminBanner) {
                    adminBanner.remove();
                }
            }
        });

        // Add loading state to button
        document.getElementById('loginForm').addEventListener('submit', function() {
            const button = document.querySelector('.login-btn');
            button.textContent = 'Signing In...';
            button.disabled = true;
        });

        // Add focus effects
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        document.getElementById('loginForm').addEventListener('submit', function(event) {
            const email = document.getElementById('loginEmail').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!email || !password) {
                event.preventDefault();
                alert('Email and Password cannot be empty.');
            }
        });
    </script>
</body>
</html>