@extends('components.admin_side_headr')

@section('title', 'Inquira - Admin Dashboard')

@section('page_title', 'Welcome Back, Admin Dashboard')

@section('additional_styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {

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
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.1);
            padding :32px 0 24px 0;
            display : flex;
            flex-direction: column;
            transition: all 0.3s ease;
            align-items: flex-start;
            overflow-y: auto;
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
            position: relative;
        }

        .top-right-profile {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            background: white;
            padding: 8px 15px;
            border-radius: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
        }

        .top-profile-avatar {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
        }

        .top-profile-info h4 {
            color: #2c3e50;
            font-size: 14px;
            font-weight: 600;
            margin: 0;
        }

        .top-profile-info p {
            color: #7f8c8d;
            font-size: 11px;
            margin: 0;
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
            content: "📊";
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

        /* Dashboard Content */
        .dashboard-content {
            padding: 40px;
            margin-left : -280px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-left: 5px solid;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            opacity: 0.1;
            background: currentColor;
            border-radius: 50%;
            transform: translate(30px, -30px);
        }

        .stat-card.users {
            border-left-color: #3498db;
            color: #3498db;
        }

        .stat-card.inquiries {
            border-left-color: #e74c3c;
            color: #e74c3c;
        }

        .stat-card.pending {
            border-left-color: #f39c12;
            color: #f39c12;
        }

        .stat-card.completed {
            border-left-color: #27ae60;
            color: #27ae60;
        }

        .stat-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stat-card.users .stat-icon {
            background: linear-gradient(135deg, #3498db, #2980b9);
        }

        .stat-card.inquiries .stat-icon {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }

        .stat-card.pending .stat-icon {
            background: linear-gradient(135deg, #f39c12, #e67e22);
        }

        .stat-card.completed .stat-icon {
            background: linear-gradient(135deg, #27ae60, #229954);
        }

        .stat-number {
            font-size: 36px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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

            .dashboard-content {
                padding: 20px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
       
        <!-- Main Content -->
        <main class="main-content">
           

            <div class="dashboard-content">
                <!-- Welcome Banner -->                <div style="background: linear-gradient(135deg, #3498db, #2980b9); color: white; padding: 30px; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);">
                    <h2 style="font-size: 28px; margin-bottom: 10px; display: flex; align-items: center; gap: 15px;">
                        👋 Hi {{ $admin->name }}, Welcome to Inquira Control Center
                    </h2>
                    <p style="font-size: 16px; opacity: 0.9;">
                        You have <strong>{{ $stats['pending_inquiries'] }} pending inquiries</strong> to review and <strong>{{ $stats['total_users'] }} active users</strong> in the system. 
                        Manage your platform efficiently with complete administrative control.
                    </p>
                    <div style="margin-top: 15px;">
                        <a href="{{ route('admin.settings') }}" style="display: inline-block; background: white; color: #3498db; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">Configure System Settings</a>
                    </div>
                </div>
                <!-- Statistics Cards -->
                <div class="stats-grid">
                    <div class="stat-card users">
                        <div class="stat-header">
                            <div class="stat-icon">👥</div>
                        </div>
                        <div class="stat-number">{{ $stats['total_users'] }}</div>
                        <div class="stat-label">Active Users</div>
                    </div>

                    <div class="stat-card inquiries">
                        <div class="stat-header">
                            <div class="stat-icon">📋</div>
                        </div>
                        <div class="stat-number">{{ $stats['total_inquiries'] }}</div>
                        <div class="stat-label">Inquiries Submitted</div>
                    </div>

                    <div class="stat-card pending">
                        <div class="stat-header">
                            <div class="stat-icon">⏳</div>
                        </div>
                        <div class="stat-number">{{ $stats['pending_inquiries'] }}</div>
                        <div class="stat-label">Pending Reviews</div>
                    </div>

                    <div class="stat-card completed">
                        <div class="stat-header">
                            <div class="stat-icon">✅</div>
                        </div>
                        <div class="stat-number">{{ $stats['completed_inquiries'] }}</div>
                        <div class="stat-label">Completed</div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Navigation Functions
        function showDashboard() {
            // Update active state
            document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
            event.target.classList.add('active');
            
            // Show dashboard content
            console.log('Navigating to Dashboard');
            // In a real application, this would load the dashboard view
        }

        function showMonitorStatus() {
            console.log('Navigating to Monitor Status');
            alert('Monitor Status page - Feature coming soon!');
        }

        // Quick Action Functions
        function showUserManagement() {
            console.log('Opening User Management');
            alert('User Management - Feature coming soon!');
        }

        function showSecuritySettings() {
            console.log('Opening Security Settings');
            alert('Security Settings - Feature coming soon!');
        }

        function showNotifications() {
            console.log('Opening Notifications');
            alert('Notifications Management - Feature coming soon!');
        }

        // Mobile responsiveness
        function toggleMobileSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('mobile-open');
        }

        // Initialize page        document.addEventListener('DOMContentLoaded', function() {
            console.log('Inquira Admin Dashboard loaded successfully');
            
            // Add click handlers for navigation
            document.querySelectorAll('.nav-item').forEach(item => {
                // Skip direct links that should navigate normally
                if (item.classList.contains('js-direct-link') || item.tagName === 'BUTTON') {
                    return;
                }
                
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Remove active class from all items
                    document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                    // Add active class to clicked item
                    this.classList.add('active');
                });
            });
        });
    </script>
@endsection

