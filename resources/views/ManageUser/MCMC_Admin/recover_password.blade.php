@php
    use Illuminate\Support\Facades\Auth;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquira - Update Password</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8f9fa;
            overflow-x: hidden;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            color: white;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            padding: 30px 25px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header h2 {
            font-size: 28px;
            font-weight: 600;
            color: #3498db;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-header h2::before {
            content: "🔍";
            font-size: 24px;
        }

        .sidebar-nav {
            padding: 30px 0;
        }

        .nav-item {
            display: block;
            padding: 15px 25px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: #3498db;
            transform: translateX(5px);
        }

        .nav-item.active {
            background: rgba(52, 152, 219, 0.2);
            color: #3498db;
            border-left-color: #3498db;
        }

        .nav-item i {
            font-size: 18px;
            width: 20px;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: 280px;
            background: #f8f9fa;
            min-height: 100vh;
        }

        .header {
            background: white;
            padding: 20px 40px;
            border-bottom: 1px solid #e9ecef;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 32px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header h1::before {
            content: "🔒";
            font-size: 28px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
        }

        .user-details h3 {
            color: #2c3e50;
            font-size: 16px;
            font-weight: 600;
        }

        .user-details p {
            color: #7f8c8d;
            font-size: 14px;
        }

        /* Password Update Content */
        .password-content {
            padding: 40px;
        }

        .password-section {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
            max-width: 800px;
            margin: 0 auto;
        }

        .password-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            opacity: 0.1;
            border-radius: 50%;
            transform: translate(50px, -50px);
        }

        .section-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-header h2 {
            color: #2c3e50;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .section-header h2::before {
            content: "🛡️";
            font-size: 24px;
        }

        .section-header p {
            color: #7f8c8d;
            font-size: 16px;
        }

        /* Alert Styles */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .alert ul {
            margin: 0;
            padding-left: 20px;
        }

        /* Form Styles */
        .password-form {
            max-width: 500px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 600;
            font-size: 16px;
        }

        .password-input-wrapper {
            position: relative;
        }

        .form-group input[type="password"] {
            width: 100%;
            padding: 15px 50px 15px 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input[type="password"]:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #7f8c8d;
            font-size: 18px;
            padding: 5px;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #3498db;
        }

        .password-strength {
            margin-top: 10px;
            font-size: 14px;
        }

        .strength-bar {
            height: 4px;
            background: #e9ecef;
            border-radius: 2px;
            margin: 8px 0;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { background: #e74c3c; width: 25%; }
        .strength-fair { background: #f39c12; width: 50%; }
        .strength-good { background: #3498db; width: 75%; }
        .strength-strong { background: #27ae60; width: 100%; }



        .submit-button {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 15px 40px;
            border-radius: 10px;
            border: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 30px;
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(52, 152, 219, 0.3);
        }

        .submit-button:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 30px;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #2980b9;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 250px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .header {
                padding: 15px 20px;
            }

            .password-content {
                padding: 20px;
            }

            .password-section {
                padding: 30px 20px;
            }

            .section-header h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>Inquira</h2>
            </div>            <div class="sidebar-nav">
                <a href="{{ route('admin.home') }}" class="nav-item">
                    <i>📊</i> Dashboard
                </a>
                <a href="#" class="nav-item" onclick="showAssignInquiries()">
                    <i>📝</i> Assign Inquiries
                </a>
                <a href="#" class="nav-item" onclick="showMonitorStatus()">
                    <i>👁️</i> Monitor Status
                </a>
                <a href="{{ route('admin.settings') }}" class="nav-item">
                    <i>⚙️</i> Settings
                </a>
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="nav-item" style="background:none;border:none;padding:15px 25px;display:block;width:100%;text-align:left;color:rgba(255,255,255,0.8);font-size:18px;">
                        <i>🚪</i> Logout
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <h1>Update Password</h1>
                <div class="user-info">
                    @if(Auth::user()->profile_picture)
                        <div class="user-avatar" style="background-image: url('{{ asset('storage/' . Auth::user()->profile_picture) }}'); background-size: cover; background-position: center;"></div>
                    @else
                        <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1) . (strpos(Auth::user()->name, ' ') ? substr(Auth::user()->name, strpos(Auth::user()->name, ' ') + 1, 1) : '')) }}</div>
                    @endif
                    <div class="user-details">
                        <h3>{{ Auth::user()->name }}</h3>
                        <p>{{ Auth::user()->user_role === 'admin' ? 'System Administrator' : ucfirst(Auth::user()->user_role) }}</p>
                    </div>
                </div>
            </header>

            <div class="password-content">                <div class="password-section">
                    <a href="{{ route('admin.settings') }}" class="back-link">
                        <span>←</span> Back to Settings
                    </a>

                    <div class="section-header">
                        <h2>Update Your Password</h2>
                        <p>Choose a strong password to keep your account secure</p>
                    </div>                    <!-- Success/Error Messages -->
                    @if(session('success'))
                    <div class="alert alert-success" id="sessionSuccessMessage">
                        ✅ {{ session('success') }}
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-error">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div id="successMessage" class="alert alert-success" style="display: none;">
                        Your password has been updated successfully!
                    </div>

                    <div id="errorMessage" class="alert alert-error" style="display: none;">
                        <ul id="errorList"></ul>
                    </div>                    <!-- Password Update Form -->
                    <form id="passwordForm" class="password-form" method="POST" action="{{ route('admin.update_password') }}">
                        @csrf
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="current_password" name="current_password" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                    👁️
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="newPassword">New Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="newPassword" name="new_password" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('newPassword')">
                                    👁️
                                </button>
                            </div>
                            <div class="password-strength">
                                <div class="strength-bar">
                                    <div id="strengthFill" class="strength-fill"></div>
                                </div>
                                <span id="strengthText">Password strength: None</span>
                            </div>
                        </div>                        <div class="form-group">
                            <label for="confirmPassword">Confirm New Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="confirmPassword" name="new_password_confirmation" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword')">
                                    👁️
                                </button>
                            </div>
                            <div id="matchMessage" style="margin-top: 8px; font-size: 14px;"></div>
                        </div>



                        <button type="submit" class="submit-button" id="submitBtn" disabled>
                            Update Password
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Password visibility toggle
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const button = field.nextElementSibling;
            
            if (field.type === 'password') {
                field.type = 'text';
                button.textContent = '🙈';
            } else {
                field.type = 'password';
                button.textContent = '👁️';
            }
        }

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            
            // Basic strength calculation
            if (password.length >= 6) strength++;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength++;

            return { strength };
        }

        function updateStrengthDisplay(strength) {
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');
            
            strengthFill.className = 'strength-fill';
            
            switch(strength) {
                case 0:
                    strengthText.textContent = 'Password strength: Very Weak';
                    strengthFill.classList.add('strength-weak');
                    break;
                case 1:
                case 2:
                    strengthText.textContent = 'Password strength: Weak';
                    strengthFill.classList.add('strength-weak');
                    break;
                case 3:
                    strengthText.textContent = 'Password strength: Fair';
                    strengthFill.classList.add('strength-fair');
                    break;
                case 4:
                    strengthText.textContent = 'Password strength: Good';
                    strengthFill.classList.add('strength-good');
                    break;
                case 5:
                case 6:
                    strengthText.textContent = 'Password strength: Strong';
                    strengthFill.classList.add('strength-strong');
                    break;
            }
        }

        // Password match checker
        function checkPasswordMatch() {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const matchMessage = document.getElementById('matchMessage');
            
            if (confirmPassword === '') {
                matchMessage.textContent = '';
                return false;
            }
            
            if (newPassword === confirmPassword) {
                matchMessage.style.color = '#27ae60';
                matchMessage.textContent = '✓ Passwords match';
                return true;
            } else {
                matchMessage.style.color = '#e74c3c';
                matchMessage.textContent = '✗ Passwords do not match';
                return false;
            }
        }        // Form validation
        function validateForm() {
            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const passwordsMatch = checkPasswordMatch();
            const hasMinLength = newPassword.length >= 6;
            
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = !(hasMinLength && passwordsMatch && newPassword.length > 0 && currentPassword.length > 0);
        }

        // Event listeners
        document.getElementById('newPassword').addEventListener('input', function() {
            const { strength } = checkPasswordStrength(this.value);
            updateStrengthDisplay(strength);
            validateForm();
        });

        document.getElementById('confirmPassword').addEventListener('input', function() {
            validateForm();
        });
        
        document.getElementById('current_password').addEventListener('input', function() {
            validateForm();
        });        // Form submission
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            // Do not prevent default - allow the form to submit to the server
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            // Only validate, but let the form submit to server
            if (!(newPassword === confirmPassword && newPassword.length >= 6)) {
                e.preventDefault();
                // Hide previous messages
                document.getElementById('successMessage').style.display = 'none';
                document.getElementById('errorMessage').style.display = 'block';
                const errorList = document.getElementById('errorList');
                errorList.innerHTML = '<li>Passwords do not match or are too short. Please try again.</li>';
            }
        });        // Navigation Functions
        function showAssignInquiries() {
            console.log('Navigating to Assign Inquiries');
            alert('Assign Inquiries page - Feature coming soon!');
        }

        function showMonitorStatus() {
            console.log('Navigating to Monitor Status');
            alert('Monitor Status page - Feature coming soon!');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Password update page loaded successfully');
            
            // Auto-hide session success message after 5 seconds
            const sessionSuccessMessage = document.getElementById('sessionSuccessMessage');
            if (sessionSuccessMessage) {
                setTimeout(function() {
                    sessionSuccessMessage.style.opacity = '0';
                    sessionSuccessMessage.style.transform = 'translateY(-20px)';
                    setTimeout(function() {
                        sessionSuccessMessage.style.display = 'none';
                    }, 300);
                }, 5000);
            }
        });
    </script>
</body>
</html>