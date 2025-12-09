<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New Agency - Inquira System</title>
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

        .registration-wrapper {
            width: 100%;
            max-width: 500px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header-section {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .logo {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }

        .tagline {
            font-size: 16px;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }

        .form-section {
            padding: 40px 30px;
        }

        .page-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .page-title h1 {
            color: #2c3e50;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .page-title p {
            color: #7f8c8d;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 600;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #3498db;
            background: white;
            box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
            transform: translateY(-2px);
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #bdc3c7;
            font-size: 18px;
        }

        .submit-section {
            margin-top: 35px;
        }

        .btn-register {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(52, 152, 219, 0.4);
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .btn-register:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .message {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-weight: 500;
            display: none;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-message {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .error-message {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .back-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e9ecef;
        }

        .back-link a {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-link a:hover {
            color: #2980b9;
            transform: translateX(-5px);
        }

        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }

        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 60%;
            right: 15%;
            animation-delay: 2s;
        }

        .floating-element:nth-child(3) {
            width: 40px;
            height: 40px;
            bottom: 30%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        @media (max-width: 600px) {
            .registration-wrapper {
                margin: 10px;
                border-radius: 15px;
            }
            
            .form-section {
                padding: 30px 20px;
            }
            
            .header-section {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="floating-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>

    <div class="registration-wrapper">
        <div class="header-section">
            <div class="logo">Inquira</div>
            <div class="tagline">Administrative System</div>
        </div>

        <div class="form-section">
            <div class="page-title">
                <h1>Register New Agency</h1>
                <p>Create a new agency account</p>
            </div>

            @if(session('success'))
                <div class="success-message message" id="successMessage">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="error-message message" id="errorMessage">
                    ❌ {{ $errors->first() }}
                </div>
            @endif            <form id="registrationForm" method="POST" action="{{ route('admin.handleRegisterAgency') }}">
                @csrf
                <div class="form-group">
                    <label for="agencyName">Enter person name </label>
                    <div class="input-wrapper">
                        <input 
                            type="text" 
                            id="agencyName" 
                            name="agencyName" 
                            class="form-control" 
                            placeholder="Enter person name here"
                            required
                        >
                        <span class="input-icon">🏢</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="agencyEmail">Agency Email Address</label>
                    <div class="input-wrapper">
                        <input 
                            type="email" 
                            id="agencyEmail" 
                            name="agencyEmail" 
                            class="form-control" 
                            placeholder="Enter agency email address"
                            required
                        >
                        <span class="input-icon">📧</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="agencyPassword">Password</label>
                    <div class="input-wrapper">
                        <input 
                            type="password" 
                            id="agencyPassword" 
                            name="agencyPassword" 
                            class="form-control" 
                            placeholder="Enter password"
                            required
                        >
                        <span class="input-icon">🔒</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <div class="input-wrapper">
                        <input 
                            type="password" 
                            id="confirmPassword" 
                            name="confirmPassword" 
                            class="form-control" 
                            placeholder="Confirm password"
                            required
                        >
                        <span class="input-icon">✓</span>
                    </div>
                </div>

                <div class="submit-section">
                    <button type="submit" class="btn-register">
                        Register Agency
                    </button>
                </div>
            </form>

            <div class="back-link">
                <a href="{{ route('admin.settings') }}">← Back to Admin Dashboard</a>
            </div>
        </div>
    </div>    <script>
        const form = document.getElementById('registrationForm');
        form.addEventListener('submit', function(event) {
            // Validate agency name
            const agencyName = document.getElementById('agencyName').value;
            if (!agencyName.trim()) {
                event.preventDefault();
                alert('Please enter a valid agency name.');
                return;
            }
            
            // Validate password matching
            const password = document.getElementById('agencyPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            if (password !== confirmPassword) {
                event.preventDefault();
                alert('Passwords do not match. Please try again.');
                return;
            }

            // Validate password length
            if (password.length < 6) {
                event.preventDefault();
                alert('Password must be at least 6 characters long.');
                return;
            }

            const confirmation = confirm('Are you sure you want to register this agency?');
            if (!confirmation) {
                event.preventDefault();
            }
        });

        // Auto-hide success message after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.opacity = '0';
                    successMessage.style.transform = 'translateY(-20px)';
                    setTimeout(function() {
                        successMessage.style.display = 'none';
                    }, 300);
                }, 5000);
            }
        });
    </script>
</body>
</html>