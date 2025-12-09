@php
use Illuminate\Support\Facades\Auth;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inquira Admin')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #334155;
            line-height: 1.6;
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
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y:auto;
        }

        .sidebar-header {
            padding: 30px 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            font-weight: 400;
            display: flex;
            align-items: center;
            gap: 12px;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-size: large;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: #3498db;
            transform: translateX(5px);
        }

        .nav-item.active {
            background: rgba(52, 152, 219, 0.2);
            color: #3498db;
            border-left-color: #3498db;
        }

        /* Main Content Area */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;

            display: flex;
            flex-direction: column;
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
        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
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

            font-size: 28px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }        .user-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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

        .notification-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            color: #64748b;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .notification-link:hover {
            background: #f1f5f9;
            color: #3b82f6;
        }

        .notification-icon {
            font-size: 20px;
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: #ef4444;
            color: white;
            font-size: 12px;
            height: 20px;
            min-width: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            border: 2px solid white;
        }

        /* Red dot indicator for unread notifications */
        .notification-dot {
            position: absolute;
            top: 2px;
            right: 2px;
            width: 12px;
            height: 12px;
            background: #ff0000 !important;
            border-radius: 50%;
            border: 2px solid white;
            z-index: 100;
            display: none;
            box-shadow: 0 0 0 2px rgba(255, 0, 0, 0.3);
        }


    </style>
    @yield('additional_styles')
</head>
<body>
    <div class="dashboard-layout">
        <!-- Admin Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>Inquira</h2>
            </div>
            <div class="sidebar-nav">
                <a href="{{ route('admin.home') }}" class="nav-item {{ request()->routeIs('admin.home') ? 'active' : '' }}">
                    <i>🏠</i> Home Page
                </a>
                <a href="{{ route('module4.mcmc.dashboard') }}" class="nav-item {{ request()->routeIs('module4.mcmc.dashboard') ? 'active' : '' }}">
                    <i>📊</i> Dashboard
                </a>
                <!-- Reports Collapsible Menu -->
                <div class="nav-item" id="reportsMenuToggle" style="cursor:pointer;display:flex;align-items:center;justify-content:space-between;">
                    <span><i>📑</i> Reports <i id="reportsCaret" class="fas fa-caret-down" style="margin-left:8px;"></i></span>
                </div>
                <div id="reportsSubMenu" style="display:none;flex-direction:column;margin-left:30px;">
                    <a href="{{ route('module4.mcmc.report') }}" class="nav-item {{ request()->routeIs('module4.mcmc.report') ? 'active' : '' }}" style="font-size:15px;">
                        <i>📑</i> Agency Performance Reports
                    </a>
                    <a href="{{ route('admin.inquiry.reports') }}" class="nav-item {{ request()->routeIs('admin.inquiry.reports') ? 'active' : '' }}" style="font-size:15px;">
                        <i>📊</i> Reports & Analytics
                    </a>
                </div>
                <a href="{{ route('admin.inquiries') }}" class="nav-item {{ request()->routeIs('admin.inquiries') ? 'active' : '' }}">
                    <i>⏳</i> Pending Inquiries
                </a>
                <a href="{{ route('admin.all.inquiries') }}" class="nav-item {{ request()->routeIs('admin.all.inquiries') ? 'active' : '' }}">
                    <i>📋</i> All Inquiries
                </a>

                <a href="{{ route('admin.settings') }}" class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }} js-direct-link">
                    <i>⚙️</i> Settings
                </a>
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="nav-item" style="font-size: 18px;">
                        <i>🚪</i> Logout
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <h1>@yield('page_title', 'Admin Panel')</h1>
                  <div class="header-actions">
                    <!-- Notifications -->
                    @php
                        $unreadCount = 0;
                        if (auth()->check()) {
                            try {
                                $unreadCount = \App\Models\Module4\Notification::where('user_id', auth()->id())
                                    ->where('is_read', 0)
                                    ->count();
                            } catch (\Exception $e) {
                                $unreadCount = 0;
                            }
                        }
                    @endphp
                    <a href="{{ route('admin.notifications') }}" class="notification-link">
                        <i class="fas fa-bell notification-icon"></i>
                        @if($unreadCount > 0)
                            <span id="notificationBadge" class="notification-badge" style="display: flex;">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>

                        @else
                            <span id="notificationBadge" class="notification-badge" style="display: none;">0</span>
                            <span id="notificationDot" class="notification-dot" style="display: none;"></span>
                        @endif
                    </a>                    <!-- User Info -->       
                     @auth
                <div class="user-info">               
                      @php
                        $user = auth()->user(); 
                    @endphp
                    <div class="user-avatar">
                    @if($user && $user->profile_picture && file_exists(public_path('storage/' . $user->profile_picture)))
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                    @else
                        {{ collect(explode(' ', $user->name ?? 'AG'))->map(fn($n) => strtoupper(substr($n,0,1)))->join('') }}
                    @endif
</div>
                    <div class="user-details">
                        <h3>{{ $user->name }}</h3>
                        <p>{{ $user->user_role === 'admin' ? 'System Administrator' : ucfirst($user->user_role) }}</p>
                    </div>
                </div>
                    @endauth
                </div>
            </header>

            <!-- Content Area -->
            <div class="content">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Function to handle clicks for nav items
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                // Don't interfere with links that should navigate normally
                if (this.getAttribute('href') && this.getAttribute('href') !== '#') {
                    return;
                }
                
                // Remove active class from all items
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                // Add active class to clicked item
                this.classList.add('active');
            });
        });

        // Functions for onclick handlers
        function showAssignInquiries() {
            console.log('Navigating to Assign Inquiries');
            // You can implement the actual functionality here
            alert('Assign Inquiries - Feature coming soon!');
        }

        function showMonitorStatus() {
            console.log('Navigating to Monitor Status');
            // You can implement the actual functionality here
            alert('Monitor Status - Feature coming soon!');
        }

        // Function to update notification count
        function updateNotificationCount() {
            console.log('🔔 Checking notification count...');
            fetch('/module4/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    console.log('📊 Notification data:', data);
                    const badge = document.getElementById('notificationBadge');
                    const dot = document.getElementById('notificationDot');
                    
                    console.log('🎯 Badge element:', badge);
                    console.log('🎯 Dot element:', dot);
                    
                    if (data.count > 0) {
                        console.log('✅ Showing notifications - count:', data.count);
                        if (badge) {
                            badge.textContent = data.count > 99 ? '99+' : data.count;
                            badge.style.display = 'flex';
                        }
                        if (dot) {
                            dot.style.display = 'block';
                        }
                    } else {
                        console.log('❌ No unread notifications');
                        if (badge) {
                            badge.style.display = 'none';
                        }
                        if (dot) {
                            dot.style.display = 'none';
                        }
                    }
                })
                .catch(error => {
                    console.log('❌ Notification count update failed:', error);
                });
        }

        // Update notification count every minute
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 DOM loaded, initializing notification system...');
            updateNotificationCount();
            setInterval(updateNotificationCount, 60000);
        });

        // Mobile menu toggle
        function toggleMobileSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
        }

        // Reports menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            var reportsMenuToggle = document.getElementById('reportsMenuToggle');
            var reportsSubMenu = document.getElementById('reportsSubMenu');
            var reportsCaret = document.getElementById('reportsCaret');
            // Open submenu if on a report page
            var isReportPage = {{ request()->routeIs('module4.mcmc.report') || request()->routeIs('admin.inquiry.reports') ? 'true' : 'false' }};
            if (isReportPage) {
                reportsSubMenu.style.display = 'flex';
                reportsCaret.classList.remove('fa-caret-down');
                reportsCaret.classList.add('fa-caret-up');
            }
            if(reportsMenuToggle && reportsSubMenu) {
                reportsMenuToggle.addEventListener('click', function() {
                    if(reportsSubMenu.style.display === 'none' || reportsSubMenu.style.display === '') {
                        reportsSubMenu.style.display = 'flex';
                        reportsCaret.classList.remove('fa-caret-down');
                        reportsCaret.classList.add('fa-caret-up');
                    } else {
                        reportsSubMenu.style.display = 'none';
                        reportsCaret.classList.remove('fa-caret-up');
                        reportsCaret.classList.add('fa-caret-down');
                    }
                });
            }
        });
    </script>
    @yield('additional_scripts')
</body>
</html>
