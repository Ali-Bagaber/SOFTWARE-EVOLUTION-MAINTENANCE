@php
    use Illuminate\Support\Facades\Auth;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquira - Generate Reports</title>
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

        /* Report Content */
        .report-content {
            padding: 40px;
        }

        /* Filter Section */
        .filter-section {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .filter-section::before {
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

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-label {
            color: #2c3e50;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-input, .filter-select {
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            color: #2c3e50;
            background: white;
            transition: all 0.3s ease;
        }

        .filter-input:focus, .filter-select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-pdf {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .btn-excel {
            background: linear-gradient(135deg, #27ae60, #229954);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        /* Summary Section */
        .summary-section {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .summary-title {
            color: #2c3e50;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .summary-title::before {
            content: "📈";
            font-size: 20px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .summary-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            border-left: 4px solid #3498db;
            transition: all 0.3s ease;
        }

        .summary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .summary-card:nth-child(2) {
            border-left-color: #e74c3c;
        }

        .summary-card:nth-child(3) {
            border-left-color: #f39c12;
        }

        .summary-card:nth-child(4) {
            border-left-color: #27ae60;
        }

        .summary-number {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .summary-label {
            color: #7f8c8d;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Table Section */
        .table-section {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .table-title {
            color: #2c3e50;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-title::before {
            content: "👥";
            font-size: 20px;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .data-table th {
            background: linear-gradient(135deg, #34495e, #2c3e50);
            color: white;
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table td {
            padding: 15px 20px;
            border-bottom: 1px solid #f8f9fa;
            color: #2c3e50;
            font-size: 14px;
        }

        .data-table tr:hover {
            background: #f8f9fa;
        }
        
        /* Search Container Styles */
        .search-container {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .search-container:focus-within {
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.15);
        }
        
        #searchInput {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        #searchInput:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.1);
        }
        
        #searchButton {
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        #searchButton:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.2);
        }

        .role-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .role-admin {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .role-public {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .role-agency {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
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

            .report-content {
                padding: 20px;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                justify-content: center;
            }
        }
        
        /* User Profile Card Styles */
        .profile-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .profile-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            border-top: 4px solid #3498db;
        }
        
        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        }
        
        .profile-header {
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .profile-name {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .profile-role {
            display: flex;
            gap: 5px;
        }
        
        .profile-body {
            padding: 20px;
        }
        
        .profile-field {
            margin-bottom: 12px;
            display: flex;
            align-items: baseline;
        }
        
        .profile-label {
            font-size: 14px;
            color: #7f8c8d;
            width: 100px;
            flex-shrink: 0;
        }
        
        .profile-value {
            font-size: 15px;
            color: #2c3e50;
            font-weight: 500;
        }
        
        .profile-value a {
            color: #3498db;
            text-decoration: none;
        }
        
        .profile-value a:hover {
            text-decoration: underline;
        }
        
        .no-profiles {
            grid-column: 1 / -1;
            text-align: center;
            padding: 40px;
            background: #f8f9fa;
            border-radius: 12px;
            color: #7f8c8d;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>Inquira</h2>
            </div>
            <div class="sidebar-nav">
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
                <h1>Generate Reports</h1>
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

            <div class="report-content">
                <!-- Filter Section -->                <div class="filter-section">
                    <h2 class="section-title">Generate Reports</h2>                      <form id="reportForm" method="GET" action="{{ route('admin.reports') }}">
                        @csrf
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="filter-grid">                            <div class="filter-group">
                                <label class="filter-label">Report Type</label>
                                <select name="report_type" class="filter-select" required id="reportType">
                                    <option value="detailed" {{ isset($filters['report_type']) && $filters['report_type'] == 'detailed' ? 'selected' : '' }}>Detailed Report</option>
                                    <option value="summary" {{ isset($filters['report_type']) && $filters['report_type'] == 'summary' ? 'selected' : '' }}>Summary Report</option>
                                    <option value="analytics" {{ isset($filters['report_type']) && $filters['report_type'] == 'analytics' ? 'selected' : '' }}>Analytics Report</option>
                                    <option value="user_profile" {{ isset($filters['report_type']) && $filters['report_type'] == 'user_profile' ? 'selected' : '' }}>View User Profile</option>
                                </select>
                            </div><div class="filter-group">
                                <label class="filter-label">Date From</label>
                                <input type="date" name="date_from" class="filter-input" value="{{ $filters['date_from'] ?? date('Y-m-d', strtotime('-30 days')) }}" required max="{{ date('Y-m-d') }}">
                            </div>

                            <div class="filter-group">
                                <label class="filter-label">Date To</label>
                                <input type="date" name="date_to" class="filter-input" value="{{ $filters['date_to'] ?? date('Y-m-d') }}" required max="{{ date('Y-m-d') }}">
                            </div>                            <div class="filter-group">
                                <label class="filter-label">User Type</label>
                                <select name="user_role" class="filter-select" id="userRoleSelect">
                                    <option value="all" {{ isset($filters['user_role']) && $filters['user_role'] == 'all' ? 'selected' : '' }}>All Users</option>
                                    <option value="admin" {{ isset($filters['user_role']) && $filters['user_role'] == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="publicuser" {{ isset($filters['user_role']) && $filters['user_role'] == 'publicuser' ? 'selected' : '' }}>Public User</option>
                                    <option value="agency" {{ isset($filters['user_role']) && $filters['user_role'] == 'agency' ? 'selected' : '' }}>Agency</option>
                                </select>
                            </div>
                        </div>                        <div class="button-group">
                            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #3498db, #2980b9); color: white;">
                                🔍 Generate Report
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Summary Section -->                <div class="summary-section">
                    <h2 class="summary-title">Report Summary</h2>                    <!-- Export Buttons - Centralized here -->
                    <div style="display: flex; justify-content: center; margin-bottom: 20px; gap: 10px;">
                        <a href="{{ route('admin.exportToPDF') }}?user_role={{ $filters['user_role'] ?? 'all' }}&date_from={{ $filters['date_from'] ?? date('Y-m-d', strtotime('-30 days')) }}&date_to={{ $filters['date_to'] ?? date('Y-m-d') }}&report_type={{ $filters['report_type'] ?? 'detailed' }}{{ isset($filters['search']) ? '&search='.$filters['search'] : '' }}" 
                           class="btn btn-pdf" 
                           style="text-decoration: none; background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; box-shadow: 0 4px 6px rgba(231, 76, 60, 0.3); transition: all 0.3s ease;"
                           onclick="alert('Generating PDF report. Please wait...')">
                            📄 Download PDF
                        </a>
                        <a href="{{ route('admin.exportToExcel') }}?user_role={{ $filters['user_role'] ?? 'all' }}&date_from={{ $filters['date_from'] ?? date('Y-m-d', strtotime('-30 days')) }}&date_to={{ $filters['date_to'] ?? date('Y-m-d') }}&report_type={{ $filters['report_type'] ?? 'detailed' }}{{ isset($filters['search']) ? '&search='.$filters['search'] : '' }}" 
                           class="btn btn-excel" 
                           style="text-decoration: none; background: linear-gradient(135deg, #27ae60, #229954); color: white; box-shadow: 0 4px 6px rgba(39, 174, 96, 0.3); transition: all 0.3s ease;"
                           onclick="alert('Generating Excel report. Please wait...')">
                            📊 Download Excel
                        </a>
                    </div>
                    <div class="summary-grid">
                        <div class="summary-card">
                            <div class="summary-number" id="totalUsers">{{ isset($summary['total_users']) ? number_format($summary['total_users']) : '0' }}</div>
                            <div class="summary-label">Total Users</div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-number" id="publicUsers">{{ isset($summary['public_users']) ? number_format($summary['public_users']) : '0' }}</div>
                            <div class="summary-label">Public Users</div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-number" id="agencyUsers">{{ isset($summary['agency_users']) ? number_format($summary['agency_users']) : '0' }}</div>
                            <div class="summary-label">Agency Users</div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-number" id="adminUsers">{{ isset($summary['admin_users']) ? number_format($summary['admin_users']) : '0' }}</div>
                            <div class="summary-label">Admin Users</div>
                        </div>
                    </div>
                </div><!-- User Table Section -->
                <div class="table-section">
                    <h2 class="table-title" id="tableTitle">
                        {{ isset($filters['search']) ? 'Search Results for "' . $filters['search'] . '"' : 'User Details' }}
                    </h2>
                    
                    <!-- Search Form -->
                    <div class="search-container" style="margin-bottom: 20px; display: flex; gap: 10px; max-width: 500px;">
                        <form id="searchForm" style="display:flex; width:100%; gap:10px;" method="GET" action="{{ route('admin.reports') }}">
                            <input type="hidden" name="report_type" value="{{ $filters['report_type'] ?? 'detailed' }}">
                            <input type="hidden" name="date_from" value="{{ $filters['date_from'] ?? date('Y-m-d', strtotime('-30 days')) }}">
                            <input type="hidden" name="date_to" value="{{ $filters['date_to'] ?? date('Y-m-d') }}">
                            <input type="hidden" name="user_role" value="{{ $filters['user_role'] ?? 'all' }}">
                            <input type="text" id="searchInput" name="search" placeholder="Search by name or email..." 
                                class="filter-input" style="flex-grow: 1;" 
                                value="{{ $filters['search'] ?? '' }}">
                            <button type="submit" id="searchButton" class="btn" 
                                    style="background: linear-gradient(135deg, #3498db, #2980b9); color: white;">
                                🔍 Search
                            </button>
                        </form>
                    </div>
                    
                    @if(isset($filters['report_type']) && $filters['report_type'] == 'user_profile')
                        <!-- User Profile Card View -->
                        <div class="profile-grid">
                            @forelse($users as $user)
                                <div class="profile-card">
                                    <div class="profile-header">
                                        <div class="profile-name">{{ $user->name }}</div>
                                        <div class="profile-role">
                                            @if($user->user_role == 'admin')
                                                <span class="role-badge role-admin">Admin</span>
                                            @elseif($user->user_role == 'agency')
                                                <span class="role-badge role-agency">Agency</span>
                                            @elseif($user->user_role == 'publicuser')
                                                <span class="role-badge role-public">Public</span>
                                            @else
                                                <span class="role-badge">{{ ucfirst($user->user_role) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="profile-body">
                                        <div class="profile-field">
                                            <div class="field-label">ID</div>
                                            <div class="field-value">{{ $user->user_id ?? $user->id }}</div>
                                        </div>
                                        <div class="profile-field">
                                            <div class="field-label">Email</div>
                                            <div class="field-value">{{ $user->email }}</div>
                                        </div>
                                        <div class="profile-field">
                                            <div class="field-label">Phone</div>
                                            <div class="field-value">{{ $user->contact_number ?? 'N/A' }}</div>
                                        </div>
                                        <div class="profile-field">
                                            <div class="field-label">Created Date</div>
                                            <div class="field-value">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</div>
                                        </div>
                                        <div class="profile-field">
                                            <div class="field-label">Last Login</div>
                                            <div class="field-value">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y - h:i A') : 'Never' }}</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="no-profiles">No user profiles found matching your criteria</div>
                            @endforelse
                        </div>
                        
                        <style>
                            .profile-grid {
                                display: grid;
                                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                                gap: 20px;
                            }
                            
                            .profile-card {
                                background: white;
                                border-radius: 10px;
                                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                                overflow: hidden;
                                transition: transform 0.3s ease, box-shadow 0.3s ease;
                            }
                            
                            .profile-card:hover {
                                transform: translateY(-5px);
                                box-shadow: 0 5px 15px rgba(0,0,0,0.15);
                            }
                            
                            .profile-header {
                                padding: 15px 20px;
                                background: linear-gradient(135deg, #f5f7fa, #e5e9f2);
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                                border-bottom: 1px solid #eaedf3;
                            }
                            
                            .profile-name {
                                font-size: 18px;
                                font-weight: 600;
                                color: #2c3e50;
                            }
                            
                            .profile-body {
                                padding: 20px;
                            }
                            
                            .profile-field {
                                margin-bottom: 15px;
                            }
                            
                            .field-label {
                                font-size: 12px;
                                text-transform: uppercase;
                                color: #7f8c8d;
                                margin-bottom: 4px;
                                letter-spacing: 0.5px;
                            }
                            
                            .field-value {
                                color: #2c3e50;
                                font-size: 15px;
                            }
                            
                            .no-profiles {
                                grid-column: 1 / -1;
                                padding: 30px;
                                text-align: center;
                                background: #f8f9fa;
                                border-radius: 10px;
                                color: #7f8c8d;
                                font-size: 16px;
                            }
                            
                            @media (max-width: 768px) {
                                .profile-grid {
                                    grid-template-columns: 1fr;
                                }
                            }
                        </style>
                    @else
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Role</th>
                                        <th>Created Date</th>
                                    </tr>
                                </thead>                           
                                <tbody id="userTableBody">
                                    @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->user_id ?? $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->contact_number ?? 'N/A' }}</td>
                                        <td>
                                            @if($user->user_role == 'admin')
                                                <span class="role-badge role-admin">Admin</span>
                                            @elseif($user->user_role == 'agency')
                                                <span class="role-badge role-agency">Agency</span>
                                            @elseif($user->user_role == 'publicuser')
                                                <span class="role-badge role-public">Public</span>
                                            @else
                                                <span class="role-badge">{{ ucfirst($user->user_role) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No users found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <script>        // Navigation Functions
        function showAssignInquiries() {
            console.log('Navigating to Assign Inquiries');
            alert('Assign Inquiries page - Feature coming soon!');
        }

        function showMonitorStatus() {
            console.log('Navigating to Monitor Status');
            alert('Monitor Status page - Feature coming soon!');
        }// Filter function to submit the form when filters change
        function applyFilters() {
            // Show loading message
            const userRole = document.querySelector('select[name="user_role"]').value;
            let message = 'Generating report';
            
            if (userRole !== 'all') {
                const userTypeText = document.querySelector(`select[name="user_role"] option[value="${userRole}"]`).textContent;
                message += ` for ${userTypeText}`;
            }
            
            // Update table title to reflect the filter being applied
            const tableTitle = document.getElementById('tableTitle');
            if (userRole !== 'all') {
                const userTypeText = document.querySelector(`select[name="user_role"] option[value="${userRole}"]`).textContent;
                tableTitle.textContent = `${userTypeText} Details`;
            } else {
                tableTitle.textContent = "User Details";
            }
            
            alert(message + '...');
            document.getElementById('reportForm').submit();
        }        // Function to update UI based on report type
        function updateUIForReportType(reportType) {
            const tableTitle = document.getElementById('tableTitle');
            const userRoleSelect = document.getElementById('userRoleSelect');
            const dateFromInput = document.querySelector('input[name="date_from"]');
            const dateToInput = document.querySelector('input[name="date_to"]');
            const generateButton = document.querySelector('.btn-primary');
            const pdfBtn = document.querySelector('.btn-pdf');
            const excelBtn = document.querySelector('.btn-excel');
            
            switch(reportType) {
                case 'user_profile':
                    tableTitle.textContent = "User Profile Details";
                    tableTitle.previousElementSibling.textContent = "👤";  // Change icon
                    
                    // Update generate button text for profile view
                    if (userRoleSelect.value !== 'all') {
                        const userTypeText = document.querySelector(`select[name="user_role"] option[value="${userRoleSelect.value}"]`).textContent;
                        generateButton.innerHTML = `🔍 View ${userTypeText} Profiles`;
                    } else {
                        generateButton.innerHTML = `🔍 View User Profiles`;
                    }
                    
                    break;
                case 'summary':
                    tableTitle.textContent = "Summary Report";
                    tableTitle.previousElementSibling.textContent = "📊";  // Change icon
                    updateGenerateButtonText(); // Update button text based on selected user role
                    break;
                case 'analytics':
                    tableTitle.textContent = "Analytics Report";
                    tableTitle.previousElementSibling.textContent = "📈";  // Change icon
                    updateGenerateButtonText(); // Update button text based on selected user role
                    break;
                default: // detailed
                    tableTitle.textContent = "User Details";
                    tableTitle.previousElementSibling.textContent = "👥";  // Change icon
                    updateGenerateButtonText(); // Update button text based on selected user role
            }
            
            // Update the PDF and Excel button text based on report type
            if (reportType === 'user_profile') {
                if (pdfBtn) pdfBtn.innerHTML = '📄 Download Profiles PDF';
                if (excelBtn) excelBtn.innerHTML = '📊 Download Profiles Excel';
            } else {
                if (pdfBtn) pdfBtn.innerHTML = '📄 Download PDF';
                if (excelBtn) excelBtn.innerHTML = '📊 Download Excel';
            }
            
            // Update export buttons href to include the current report type
            if (pdfBtn && excelBtn) {
                // Extract base URL and parameters
                const pdfUrl = new URL(pdfBtn.href);
                const excelUrl = new URL(excelBtn.href);
                
                // Update report_type parameter
                pdfUrl.searchParams.set('report_type', reportType);
                excelUrl.searchParams.set('report_type', reportType);
                
                // Update href attributes
                pdfBtn.href = pdfUrl.toString();
                excelBtn.href = excelUrl.toString();
            }
        }// Update the Generate Report button text
        function updateGenerateButtonText() {
            const userRole = document.querySelector('select[name="user_role"]').value;
            const reportType = document.querySelector('select[name="report_type"]').value;
            const generateButton = document.querySelector('.btn-primary');
            
            if (!generateButton) return;
            
            // Special handling for User Profile report type
            if (reportType === 'user_profile') {
                if (userRole !== 'all') {
                    const userTypeText = document.querySelector(`select[name="user_role"] option[value="${userRole}"]`).textContent;
                    generateButton.innerHTML = `🔍 View ${userTypeText} Profiles`;
                } else {
                    generateButton.innerHTML = `🔍 View User Profiles`;
                }
                return;
            }
            
            // Default handling for other report types
            if (userRole !== 'all') {
                const userTypeText = document.querySelector(`select[name="user_role"] option[value="${userRole}"]`).textContent;
                generateButton.innerHTML = `🔍 Show ${userTypeText} Report`;
            } else {
                generateButton.innerHTML = `🔍 Generate Report`;
            }
        }
        
        // Update the table title based on selected user type
        function updateTableTitle() {
            const userRole = document.querySelector('select[name="user_role"]').value;
            const tableTitle = document.getElementById('tableTitle');
            
            if (!tableTitle) return;
            
            if (userRole !== 'all') {
                const userTypeText = document.querySelector(`select[name="user_role"] option[value="${userRole}"]`).textContent;
                tableTitle.textContent = `${userTypeText} Details`;
            } else {
                tableTitle.textContent = "User Details";
            }
        }
          // Update export URLs to match current filter selections
        function updateExportUrls() {
            const pdfBtn = document.querySelector('.btn-pdf');
            const excelBtn = document.querySelector('.btn-excel');
            
            if (!pdfBtn || !excelBtn) return;
            
            const userRole = document.querySelector('select[name="user_role"]').value;
            const reportType = document.querySelector('select[name="report_type"]').value;
            const dateFrom = document.querySelector('input[name="date_from"]').value;
            const dateTo = document.querySelector('input[name="date_to"]').value;
            const searchValue = document.getElementById('searchInput').value.trim();
            
            // Update PDF URL
            const pdfUrl = new URL(pdfBtn.href);
            pdfUrl.searchParams.set('user_role', userRole);
            pdfUrl.searchParams.set('report_type', reportType);
            pdfUrl.searchParams.set('date_from', dateFrom);
            pdfUrl.searchParams.set('date_to', dateTo);
            
            // Include search parameter if it has a value
            if (searchValue) {
                pdfUrl.searchParams.set('search', searchValue);
            } else {
                pdfUrl.searchParams.delete('search');
            }
            
            pdfBtn.href = pdfUrl.toString();
            
            // Update Excel URL
            const excelUrl = new URL(excelBtn.href);
            excelUrl.searchParams.set('user_role', userRole);
            excelUrl.searchParams.set('report_type', reportType);
            excelUrl.searchParams.set('date_from', dateFrom);
            excelUrl.searchParams.set('date_to', dateTo);
            
            // Include search parameter if it has a value
            if (searchValue) {
                excelUrl.searchParams.set('search', searchValue);
            } else {
                excelUrl.searchParams.delete('search');
            }
            
            excelBtn.href = excelUrl.toString();
            
            // Update button text based on report type
            if (reportType === 'user_profile') {
                pdfBtn.innerHTML = '📄 Download Profiles PDF';
                excelBtn.innerHTML = '📊 Download Profiles Excel';
            } else {
                pdfBtn.innerHTML = '📄 Download PDF';
                excelBtn.innerHTML = '📊 Download Excel';
            }
        }
        
        // Mobile responsiveness
        function toggleMobileSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('mobile-open');
        }// Initialize page        document.addEventListener('DOMContentLoaded', function() {
            console.log('Inquira Generate Reports page loaded successfully');
              // Update the Generate Report button text based on selection
            document.querySelector('#userRoleSelect').addEventListener('change', function() {
                updateGenerateButtonText();
                updateTableTitle();
                updateExportUrls();
            });
            
            document.querySelector('select[name="report_type"]').addEventListener('change', function() {
                updateUIForReportType(this.value);
            });
            
            // Update date inputs to also update export URLs
            document.querySelectorAll('input[name="date_from"], input[name="date_to"]').forEach(input => {
                input.addEventListener('change', updateExportUrls);
            });
              // Initialize the button text and update table title based on current selection
            updateGenerateButtonText();
            updateTableTitle();
            updateExportUrls();
            
            // Set max date for date inputs to today
            const today = new Date().toISOString().split('T')[0];
            document.querySelector('input[name="date_from"]').setAttribute('max', today);
            document.querySelector('input[name="date_to"]').setAttribute('max', today);
            
            // Initial UI setup based on report type
            updateUIForReportType(document.querySelector('select[name="report_type"]').value);
            
            // Add click handlers for navigation
            // Add search functionality
            document.getElementById('searchButton').addEventListener('click', function() {
                performSearch();
            });
            
            // Allow search on Enter key press
            document.getElementById('searchInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
            
            // Function to handle search
            function performSearch() {
                const searchValue = document.getElementById('searchInput').value.trim();
                
                // Get the form where we'll add the search parameter
                const reportForm = document.getElementById('reportForm');
                
                if (!reportForm) {
                    console.error('Report form not found!');
                    return;
                }
                
                // Create the URL with the current form values plus search
                const currentUrl = new URL(window.location.href);
                
                // Get all the current parameters from the report form
                const userRole = document.querySelector('select[name="user_role"]').value;
                const reportType = document.querySelector('select[name="report_type"]').value;
                const dateFrom = document.querySelector('input[name="date_from"]').value;
                const dateTo = document.querySelector('input[name="date_to"]').value;
                
                // Set the parameters in the URL
                currentUrl.searchParams.set('user_role', userRole);
                currentUrl.searchParams.set('report_type', reportType);
                currentUrl.searchParams.set('date_from', dateFrom);
                currentUrl.searchParams.set('date_to', dateTo);
                
                // Add search parameter if not empty
                if (searchValue) {
                    currentUrl.searchParams.set('search', searchValue);
                } else {
                    currentUrl.searchParams.delete('search');
                }
                
                // Update PDF and Excel export URLs to include search parameter
                updateExportUrlsWithSearch(searchValue);
                
                // Redirect to the URL with all parameters
                window.location.href = currentUrl.toString();
            }
            
            // Update export URLs to include search parameter
            function updateExportUrlsWithSearch(searchValue) {
                const pdfBtn = document.querySelector('.btn-pdf');
                const excelBtn = document.querySelector('.btn-excel');
                
                if (!pdfBtn || !excelBtn) return;
                
                // Add search parameter to PDF URL if it has a value
                const pdfUrl = new URL(pdfBtn.href);
                const excelUrl = new URL(excelBtn.href);
                
                if (searchValue) {
                    pdfUrl.searchParams.set('search', searchValue);
                    excelUrl.searchParams.set('search', searchValue);
                } else {
                    pdfUrl.searchParams.delete('search');
                    excelUrl.searchParams.delete('search');
                }
                
                pdfBtn.href = pdfUrl.toString();
                excelBtn.href = excelUrl.toString();
            }
            }
            
            // Initialize search parameter in export URLs if it exists
            const searchValue = document.getElementById('searchInput').value.trim();
            if (searchValue) {
                updateExportUrlsWithSearch(searchValue);
            }
            
            document.querySelectorAll('.nav-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    // Don't prevent default for form submissions and actual links
                    if (this.tagName !== 'BUTTON' && !this.href.includes('#')) {
                        return;
                    }
                    
                    if (this.onclick) {
                        e.preventDefault();
                    }
                    
                    // Remove active class from all items
                    document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                    // Add active class to clicked item
                    this.classList.add('active');
                });
            });
        });
    </script>
</body>
</html>