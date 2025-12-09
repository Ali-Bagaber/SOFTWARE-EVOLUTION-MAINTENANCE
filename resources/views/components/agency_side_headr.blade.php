<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Agency Panel - Inquira')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            color: #2d3748;
            min-height: 100vh;
        }
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
       /* Sidebar Styles */
       .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #1a202c 0%, #2d3748 100%);
            color: white;
            padding: 0;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
        }
        .sidebar-header {
            padding: 32px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 26px;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .logo i {
            font-size: 28px;
            color: #667eea;
        }
        .sidebar-nav {
            padding: 24px 0;
        }
        .nav-item {
            display: block;
            padding: 16px 24px;
            color: #cbd5e1;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            border: none;
            background: none;
            width: 100%;
            cursor: pointer;
            font-weight: 500;
        }
        .nav-item:hover,
        .nav-item.active {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2));
            color: #667eea;
            border-right: 4px solid #667eea;
            transform: translateX(4px);
        }
        .nav-item i {
            width: 20px;
            font-size: 18px;
        }
        /* Main Content */
        .main-content {
            flex: 1;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        .top-header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            padding: 24px 32px;
            border-bottom: 1px solid rgba(102, 126, 234, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .page-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 28px;
            font-weight: 700;
            color: #2d3748;
        }
        .page-title i {
            color: #667eea;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .user-avatar {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .user-details h4 {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .user-details span {
            color: #718096;
            font-size: 14px;
        }
        .content-area {
            padding: 32px;
        }
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
            }
            .content-area {
                padding: 16px;
            }
        }
        /* Dashboard Content Styles */
        .welcome-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 20px;
            margin-bottom: 32px;
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }
        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        .welcome-banner h1 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }
        .welcome-banner p {
            font-size: 18px;
            opacity: 0.95;
            margin-bottom: 28px;
            position: relative;
            z-index: 1;
        }
        .configure-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }
        .configure-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        .stat-card.emerald::before { background: linear-gradient(90deg, #48bb78, #38a169); }
        .stat-card.red::before { background: linear-gradient(90deg, #f56565, #e53e3e); }
        .stat-card.orange::before { background: linear-gradient(90deg, #ed8936, #dd6b20); }
        .stat-card.blue::before { background: linear-gradient(90deg, #4299e1, #3182ce); }
        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .stat-icon.emerald { background: linear-gradient(135deg, #48bb78, #38a169); }
        .stat-icon.red { background: linear-gradient(135deg, #f56565, #e53e3e); }
        .stat-icon.orange { background: linear-gradient(135deg, #ed8936, #dd6b20); }
        .stat-icon.blue { background: linear-gradient(135deg, #4299e1, #3182ce); }
        .stat-number {
            font-size: 42px;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 8px;
        }
        .stat-label {
            color: #718096;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .quick-actions {
            margin-top: 32px;
        }
        .quick-actions h2 {
            color: #2d3748;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .quick-actions h2 i { color: #667eea; }
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
        }
        .action-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 32px 24px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }
        .action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            transition: left 0.5s;
        }
        .action-card:hover::before { left: 100%; }
        .action-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        .action-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 20px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .action-icon.emerald { background: linear-gradient(135deg, #48bb78, #38a169); }
        .action-icon.blue { background: linear-gradient(135deg, #4299e1, #3182ce); }
        .action-icon.teal { background: linear-gradient(135deg, #38b2ac, #319795); }
        .action-icon.orange { background: linear-gradient(135deg, #ed8936, #dd6b20); }
        .action-icon.purple { background: linear-gradient(135deg, #667eea, #764ba2); }
        .action-card h3 {
            color: #2d3748;
            font-weight: 700;
            margin-bottom: 12px;
            font-size: 18px;
        }
        .action-card p {
            color: #718096;
            font-size: 14px;
            line-height: 1.6;
        }
        .alert-success {
            margin: 20px;
            padding: 20px;
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .alert-success i {
            font-size: 24px;
            margin-right: 12px;
        }
        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: 1fr; }
            .welcome-banner { padding: 24px; }
            .welcome-banner h1 { font-size: 24px; }
        }
    </style>
    @yield('additional_styles')
</head>
<body>
<div class="dashboard-container">
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-search"></i>
                Inquira
            </div>
        </div>
        <div class="sidebar-nav">
            <a href="{{ route('agency.dashboard') }}" class="nav-item{{ request()->routeIs('agency.dashboard') ? ' active' : '' }}">
                <i class="fas fa-home"></i> Home
            </a>
            <a href="{{ route('module4.agency.dashboard') }}" class="nav-item{{ request()->routeIs('module4.agency.dashboard') ? ' active' : '' }}">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>

            <a href="{{ route('agency.display.inquiry') }}" class="nav-item{{ request()->routeIs('agency.display.inquiry') ? ' active' : '' }}">
                <i class="fas fa-search-plus"></i> Display & Review Inquiries
            </a>

            <a href="{{ route('agency.inquiry.list') }}" class="nav-item{{ request()->routeIs('agency.inquiry.list') ? ' active' : '' }}">
                <i class="fas fa-file-alt"></i> Agency Inquiry List
            </a>
            
          
            <a href="{{ route('agency.profile') }}" class="nav-item{{ request()->routeIs('agency.profile') ? ' active' : '' }}">
                <i class="fas fa-user"></i> Profile Menu
            </a>

            <form method="POST" action="{{ route('agency.logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="nav-item" style="border:none; background:none; color:inherit; font:inherit; cursor:pointer; width:100%; text-align:left;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </nav>
    <!-- Main Content -->
    <main class="main-content">
        <header class="top-header">
            <div class="page-title">
                <i class="fas fa-chart-bar"></i>
                @yield('page_title', 'Welcome Back!')
            </div>
            <div class="user-info">
                <div class="user-details">
                    <h4>{{ isset($agency) ? $agency->name : (auth()->user()->name ?? 'Agency') }}</h4>
                    <span>Agency Administrator</span>
                </div>
                <div class="user-avatar">
                    @php $user = auth()->user(); @endphp
                    @if($user && $user->profile_picture && file_exists(public_path('uploads/profile_pictures/' . $user->profile_picture)))
                        <img src="{{ asset('uploads/profile_pictures/' . $user->profile_picture) }}" alt="Profile Picture" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                    @else
                        {{ collect(explode(' ', $user->name ?? 'AG'))->map(fn($n) => strtoupper(substr($n,0,1)))->join('') }}
                    @endif
                    </div>
            </div>
        </header>
        <div class="content-area">
                @yield('content')
        </div>
    </main>
</div>
     <script>
        // Add active state to current nav item
        document.addEventListener('DOMContentLoaded', function () {
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.nav-item');

            navItems.forEach(item => {
                if (item.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                }
            });
        });

        // Add smooth hover effects
        const actionCards = document.querySelectorAll('.action-card');
        actionCards.forEach(card => {
            card.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-8px)';
            });

            card.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0)';
            });
        });
</script>
@yield('additional_scripts')
</body>
</html>
