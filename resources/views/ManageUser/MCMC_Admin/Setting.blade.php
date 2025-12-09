@extends('components.admin_side_headr')

@section('title', 'Inquira - Admin Settings')
@section('page_title', 'Admin Settings')

@section('additional_styles')
    <style>
        .settings-content {
            padding: 40px;
        }
        .profile-section {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }
        .profile-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            opacity: 0.1;
            border-radius: 50%;
            transform: translate(50px, -50px);
        }
        .profile-header {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 30px;
        }
        .profile-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 32px;
            box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
            overflow: hidden;
        }
        .profile-info h2 {
            color: #2c3e50;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .profile-info p {
            color: #7f8c8d;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .profile-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }
        .detail-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #3498db;
        }
        .detail-label {
            color: #7f8c8d;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .detail-value {
            color: #2c3e50;
            font-size: 16px;
            font-weight: 500;
        }
        .actions-section {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .section-title {
            color: #2c3e50;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title::before {
            content: "🚀";
            font-size: 20px;
        }
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }
        .action-button {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
            text-decoration: none;
            color: inherit;
            position: relative;
            overflow: hidden;
        }
        .action-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        .action-button:hover::before {
            left: 100%;
        }
        .action-button:hover {
            transform: translateY(-5px);
            border-color: #3498db;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            box-shadow: 0 10px 30px rgba(52, 152, 219, 0.3);
        }
        .action-icon {
            font-size: 40px;
            margin-bottom: 20px;
            display: block;
        }
        .action-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .action-button:hover .action-title,
        .action-button:hover .action-description {
            color: white;
        }
        .action-description {
            font-size: 14px;
            color: #7f8c8d;
            line-height: 1.5;
        }
        @media (max-width: 768px) {
            .settings-content {
                padding: 20px;
            }
            .profile-header {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
            .profile-details {
                grid-template-columns: 1fr;
            }
            .actions-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
<div class="settings-content">
    <!-- Admin Profile Section -->
    <div class="profile-section">
        <div class="profile-header">
            <div class="profile-avatar">
                @if(isset($admin) && $admin->profile_picture)
                    <img src="{{ asset('storage/' . $admin->profile_picture) }}" alt="{{ $admin->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 20px;">
                @else
                    {{ isset($admin) ? substr($admin->name, 0, 2) : 'MA' }}
                @endif
            </div>
            <div class="profile-info">
                <h2>{{ isset($admin) ? $admin->name : 'Mohammed Ahmed' }}</h2>
                <p>System Administrator</p>
                <p>Member since {{ isset($admin) ? $admin->created_at->format('F Y') : 'January 2024' }}</p>
            </div>
        </div>
        @if(session('success'))
            <div class="alert-success" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin: 20px 0;">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert-error" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 10px; margin: 20px 0;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Editable Profile Form -->
        <div style="margin-top: 30px;">
            <h3 style="margin-bottom: 20px; font-size: 20px; color: #2c3e50;">Edit Profile Information</h3>
            <form action="{{ route('admin.updateProfile') }}" method="POST" enctype="multipart/form-data" class="edit-profile-form">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
                    <div class="form-group">
                        <label for="name" style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 600;">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ isset($admin) ? $admin->name : 'Mohammed Ahmed' }}" style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 10px; font-size: 16px;" required>
                    </div>
                    <div class="form-group">
                        <label for="email" style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 600;">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ isset($admin) ? $admin->email : 'admin@inquira.com' }}" style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 10px; font-size: 16px;" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_number" style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 600;">Contact Number</label>
                        <input type="text" id="contact_number" name="contact_number" value="{{ isset($admin) ? $admin->contact_number : '+60 12-345-6789' }}" style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 10px; font-size: 16px;">
                    </div>
                    <div class="form-group">
                        <label for="profile_picture" style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 600;">Profile Picture</label>
                        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 10px; font-size: 16px; background: white;">
                        <p style="margin-top: 5px; font-size: 12px; color: #7f8c8d;">Maximum file size: 2MB. Supported formats: JPG, PNG, GIF</p>
                    </div>
                </div>
                <div>
                    <button type="submit" style="background: linear-gradient(135deg, #3498db, #2980b9); color: white; padding: 12px 30px; border-radius: 10px; border: none; font-size: 16px; font-weight: 600; cursor: pointer;">Save Changes</button>
                </div>
            </form>
        </div>
        <!-- Static Profile Information -->
        <div style="margin-top: 40px;">
            <h3 style="margin-bottom: 20px; font-size: 20px; color: #2c3e50;">Account Information</h3>
            <div class="profile-details">
                <div class="detail-item">
                    <div class="detail-label">Admin Role</div>
                    <div class="detail-value">System Administrator</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Last Login</div>
                    <div class="detail-value">{{ isset($admin) && $admin->last_login_at ? $admin->last_login_at->format('M d, Y - h:i A') : now()->format('M d, Y - h:i A') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Account Status</div>
                    <div class="detail-value">✅ Active & Verified</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Admin Action Buttons -->
    <div class="actions-section">
        <h2 class="section-title">Administrative Actions</h2>
        <div class="actions-grid">
            <a href="{{ route('mcmc.register_agency') }}" class="action-button">
                <span class="action-icon">🏢</span>
                <div class="action-title">Register New Agency</div>
                <div class="action-description">Add new government agencies to the Inquira system for inquiry management</div>
            </a>
            <a href="{{ route('admin.reports') }}" class="action-button">
                <span class="action-icon">📈</span>
                <div class="action-title">View System Reports</div>
                <div class="action-description">Access comprehensive analytics and performance reports for the platform</div>
            </a>
            <a href="{{ url('/admin/recover-password') }}" class="action-button">
                <span class="action-icon">🔑</span>
                <div class="action-title">Recovery Password</div>
                <div class="action-description">Update or recover your admin account password</div>
            </a>
        </div>
    </div>
    <!-- Test Navigation Section -->
    <div style="margin-top: 30px; text-align: center; padding: 20px; background: #f8f9fa; border-radius: 10px;">
        <p>Test Navigation Links:</p>
        <div style="margin-top: 10px;">
            <a href="{{ route('admin.home') }}" style="display: inline-block; background: #3498db; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; margin: 0 10px;">Back to Dashboard</a>
            <a href="{{ route('admin.settings') }}" style="display: inline-block; background: #2ecc71; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; margin: 0 10px;">Refresh Settings Page</a>
        </div>
    </div>
</div>
@endsection

@section('additional_scripts')
<script>
    // Navigation Functions
    function showAssignInquiries() {
        alert('Assign Inquiries page - Feature coming soon!');
    }
    function showMonitorStatus() {
        alert('Monitor Status page - Feature coming soon!');
    }
</script>
@endsection