@extends('components.side_headr')

@section('title', 'Profile Menu - Inquiria')

@section('additional_css')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #eaf0fa 0%, #f4f8fb 100%);
            min-height: 100vh;
            display: flex;
        }

        /* Profile Alert Message Styles */
        .profile-alert-success {
            background: linear-gradient(135deg, #10b981, #047857);
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            margin: 0 24px 20px 24px;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: profileSlideDown 0.4s ease-out forwards;
            z-index: 100;
        }

        .profile-alert-content {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }

        .profile-alert-icon {
            font-size: 20px;
        }

        .profile-alert-close {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 16px;
            opacity: 0.8;
            transition: opacity 0.2s;
        }

        .profile-alert-close:hover {
            opacity: 1;
        }

        @keyframes profileSlideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 0px 0px 24px 0px;
            background: linear-gradient(135deg, #eaf0fa 0%, #f4f8fb 100%);
            min-height: 100vh;
        }

        .profile-container {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            border: 1px solid #e3eaf3;
            margin-left: -190px;
            width: 80rem;
            margin-top: 24px;
        }

        .profile-header {
            background: linear-gradient(135deg, #3fa1f5 0%, #2563eb 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
            position: relative;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid white;
            margin: 0 auto 20px;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 16px rgba(63, 161, 245, 0.13);
        }

        .profile-avatar:hover {
            transform: scale(1.05);
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-placeholder {
            width: 100%;
            height: 100%;
            background: #22304a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
        }

        .camera-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 8px;
            text-align: center;
            font-size: 12px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .profile-avatar:hover .camera-overlay {
            opacity: 1;
        }

        .profile-info {
            text-align: center;
        }

        .profile-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .profile-role {
            font-size: 16px;
            opacity: 0.9;
        }

        .profile-content {
            padding: 40px;
        }

        .tabs {
            display: flex;
            border-bottom: 2px solid #e3eaf3;
            margin-bottom: 30px;
        }

        .tab {
            padding: 15px 25px;
            background: none;
            border: none;
            font-size: 16px;
            font-weight: 500;
            color: #7f8c8d;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }

        .tab.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e3eaf3;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-input:focus {
            outline: none;
            border-color: #3fa1f5;
            background: white;
            box-shadow: 0 0 0 3px rgba(63, 161, 245, 0.08);
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3fa1f5, #2563eb);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(63, 161, 245, 0.13);
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
            margin-right: 10px;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .btn-danger {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.13);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3fa1f5, #2563eb);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            overflow: hidden;
            position: relative;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            position: absolute;
            top: 0;
            left: 0;
        }

        .user-avatar span,
        .user-avatar svg,
        .user-avatar div {
            position: relative;
            z-index: 2;
        }

        .password-strength {
            margin-top: 10px;
            height: 4px;
            background: #ecf0f1;
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak {
            background: #e74c3c;
        }

        .strength-medium {
            background: #f39c12;
        }

        .strength-strong {
            background: #27ae60;
        }

        .strength-text {
            font-size: 12px;
            margin-top: 5px;
            font-weight: 500;
        }

        .file-input {
            display: none;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            display: none;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }

            .main-content {
                margin-left: 0;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .tabs {
                flex-wrap: wrap;
            }

            .tab {
                flex: 1;
                min-width: 120px;
            }
        }

        /* Profile Alert Styles */
        .profile-alert-success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .profile-alert-content {
            display: flex;
            align-items: center;
        }

        .profile-alert-icon {
            font-size: 18px;
            margin-right: 10px;
        }

        .profile-alert-close {
            background: none;
            border: none;
            color: #155724;
            cursor: pointer;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .profile-alert-close:hover {
            color: #0c5460;
        }

        @keyframes profileSlideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes profileFadeOut {
            to {
                transform: translateY(-20px);
                opacity: 0;
            }
        }
    </style>
@endsection

@section('content')
<div class="main-content">
        
        <!-- Flash Message Section -->
        @if(session('success'))
        <div class="profile-alert-success" id="profile-success-message">
            <div class="profile-alert-content">
                <i class="fas fa-check-circle profile-alert-icon"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="profile-alert-close" onclick="closeProfileAlert('profile-success-message');">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif

        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-avatar" onclick="document.getElementById('profile_picture').click()">
                    <div class="avatar-placeholder" id="avatar-display">
                        @php
                            $profilePic = auth()->user()->profile_picture;
                        @endphp
                        @if($profilePic && file_exists(public_path('storage/' . $profilePic)))
                            <img src="{{ asset('storage/' . $profilePic) }}" alt="Profile Picture"
                                style="width:100%;height:100%;object-fit:cover;border-radius:50%;" />
                        @else
                            {{ collect(explode(' ', auth()->user()->name))->map(fn($n) => strtoupper(substr($n, 0, 1)))->join('') }}
                        @endif
                    </div>
                    <div class="camera-overlay">Click to change photo</div>
                </div>
                <div class="profile-info">
                    <div class="profile-name" id="display-name">{{ auth()->user()->name }}</div>
                    <div class="profile-role">
                        {{ auth()->user()->user_role === 'publicuser' ? 'Public User' : ucfirst(auth()->user()->user_role) }}
                    </div>
                </div>
            </div>

            <div class="profile-content">
                <div class="success-message" id="success-message">
                    Profile updated successfully!
                </div>

                <div class="tabs">
                    <button class="tab active" onclick="showTab('personal')">Personal Info</button>
                </div>

                <!-- Personal Info Tab -->
                <div class="tab-content active" id="personal-tab">
                    <!-- Profile Information Section -->
                    <div
                        style="background:#ffffff; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.06); margin-bottom:30px; overflow:hidden;">
                        <div
                            style="background:linear-gradient(135deg, #3498db 0%, #2980b9 100%); padding:20px; color:white;">
                            <h3 style="margin:0; font-size:18px; font-weight:600;">
                                <i class="fas fa-user-edit" style="margin-right:10px;"></i>
                                Personal Information
                            </h3>
                            <p style="margin:5px 0 0 0; opacity:0.9; font-size:14px;">Update your profile details below
                            </p>
                        </div>
                        <div style="padding:32px;">
                            <form id="personal-form" method="POST" action="{{ route('profile.update') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">User Name</label>
                                        <input type="text" class="form-input" name="name"
                                            value="{{ old('name', auth()->user()->name) }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" class="form-input" name="email"
                                            value="{{ old('email', auth()->user()->email) }}" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Phone Number</label>
                                        <input type="tel" class="form-input" name="contact_number"
                                            value="{{ old('contact_number', auth()->user()->contact_number) }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Profile Picture</label>
                                        <input type="file" name="profile_picture" id="profile_picture"
                                            class="form-input" accept="image/*" style="display:none;">
                                        <div id="profile-picture-preview" style="margin-top:10px;">
                                            @php
                                                $profilePic = auth()->user()->profile_picture;
                                            @endphp
                                            @if($profilePic)
                                                <img src="{{ asset('storage/' . $profilePic) }}" alt="Profile Picture"
                                                    style="width:60px;height:60px;border-radius:50%;object-fit:cover;">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <button type="submit" class="btn btn-primary">Update Personal Info</button>
                                    <button type="button" class="btn btn-secondary">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Security Section -->
                    <div
                        style="background:#ffffff; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.06); overflow:hidden; margin-top:32px;">
                        <div style="background:#f8fafc; padding:20px; color:#1e293b;">
                            <h3 style="margin:0; font-size:18px; font-weight:600;">
                                <i class="fas fa-shield-alt" style="margin-right:10px; color:#3498db;"></i>
                                Security Settings
                            </h3>
                            <p style="margin:5px 0 0 0; opacity:0.9; font-size:14px;">Manage your account security and
                                password</p>
                        </div>
                        <div style="padding:32px;">
                            <div
                                style="display:flex; align-items:center; justify-content:space-between; padding:20px; background:#f8fafc; border-radius:8px; border-left:4px solid #3498db;">
                                <div>
                                    <h4 style="margin:0 0 5px 0; color:#2c3e50; font-size:16px;">Change Password</h4>
                                    <p style="margin:0; color:#7f8c8d; font-size:14px;">Update your password to keep
                                        your account secure</p>
                                </div>
                                <a href="{{ route('publicuser.edit_password') }}" class="btn btn-primary"
                                    style="margin-left:20px;">
                                    <i class="fas fa-key" style="margin-right:8px;"></i>
                                    Change Password
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.remove('active');
            });

            // Remove active class from all tabs
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab content
            document.getElementById(tabName + '-tab').classList.add('active');

            // Add active class to clicked tab
            event.target.classList.add('active');
        }

        function checkPasswordStrength() {
            const password = document.getElementById('new-password').value;
            const strengthBar = document.getElementById('strength-bar');
            const strengthText = document.getElementById('strength-text');

            let strength = 0;
            let feedback = '';

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            strengthBar.className = 'strength-bar';

            if (strength < 3) {
                strengthBar.classList.add('strength-weak');
                strengthBar.style.width = '33%';
                feedback = 'Weak password';
                strengthText.style.color = '#e74c3c';
            } else if (strength < 5) {
                strengthBar.classList.add('strength-medium');
                strengthBar.style.width = '66%';
                feedback = 'Medium strength';
                strengthText.style.color = '#f39c12';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthBar.style.width = '100%';
                feedback = 'Strong password';
                strengthText.style.color = '#27ae60';
            }

            strengthText.textContent = feedback;
        }

        function showSuccessMessage() {
            const message = document.getElementById('success-message');
            message.style.display = 'block';
            setTimeout(() => {
                message.style.display = 'none';
            }, 3000);
        }

        function confirmDeleteAccount() {
            if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
                alert('Account deletion process initiated. You will receive an email with further instructions.');
            }
        }

        // Handle avatar click to open file input
        document.querySelector('.profile-avatar').addEventListener('click', function () {
            document.getElementById('profile_picture').click();
        });

        // Handle file input change for preview
        document.getElementById('profile_picture').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    // Update both the top avatar and the preview in the form
                    document.getElementById('avatar-display').innerHTML = `<img src="${e.target.result}" alt="Profile Picture">`;
                    document.getElementById('profile-picture-preview').innerHTML = `<img src="${e.target.result}" alt="Profile Picture" style="width:60px;height:60px;border-radius:50%;object-fit:cover;">`;
                };
                reader.readAsDataURL(file);
            }
        });

        // Handle form submissions
        document.getElementById('password-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (newPassword !== confirmPassword) {
                alert('Passwords do not match!');
                return;
            }

            showSuccessMessage();
            this.reset();
            document.getElementById('strength-bar').style.width = '0%';
            document.getElementById('strength-text').textContent = '';
        });

        document.getElementById('preferences-form').addEventListener('submit', function (e) {
            e.preventDefault();
            showSuccessMessage();
        });

        // Auto-hide profile success messages
        function closeProfileAlert(alertId) {
            const alert = document.getElementById(alertId);
            if (alert) {
                alert.style.animation = 'profileFadeOut 0.3s ease-in forwards';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }
        }

        // Auto-close profile alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const profileAlert = document.getElementById('profile-success-message');
            if (profileAlert) {
                setTimeout(() => {
                    closeProfileAlert('profile-success-message');
                }, 5000);
            }
        });
    </script>
@endsection