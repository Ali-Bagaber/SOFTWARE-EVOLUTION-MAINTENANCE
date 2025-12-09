<!DOCTYPE html>
<html>
<head>
    <title>Users PDF Export</title>    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
            line-height: 1.6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
            border-bottom: 2px solid #dee2e6;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
        }
        .filters {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #dee2e6;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .filters h2 {
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
            font-size: 18px;
        }
        .filters p {
            margin: 8px 0;
            font-size: 13px;
        }
        .summary {
            margin-bottom: 30px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        .summary-card {
            background: #fff;
            border: 1px solid #dee2e6;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
        }
        .summary-number {
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .summary-label {
            color: #666;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        h1, h2 {
            color: #333;
        }
        .admin {
            background-color: #e74c3c;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .agency {
            background-color: #f39c12;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .publicuser {
            background-color: #3498db;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .user-profile {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .user-profile-header {
            display: flex;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .user-profile-role {
            font-weight: bold;
            margin-left: auto;
        }
        .user-profile-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .user-profile-field {
            margin-bottom: 8px;
        }
        .user-profile-label {
            font-weight: bold;
            color: #7f8c8d;
            font-size: 10px;
            text-transform: uppercase;
        }
        .user-profile-value {
            color: #2c3e50;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ isset($report_type) ? ucfirst(str_replace('_', ' ', $report_type)) : 'Detailed' }} Users Report</h1>
        <p>Inquira - Admin Report System</p>
        <p>Generated on: {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>
    
    <div class="filters">
        <h2>Report Summary</h2>
        <p><strong>Report Type:</strong> {{ isset($report_type) ? ucfirst(str_replace('_', ' ', $report_type)) : 'Detailed' }}</p>
        <p><strong>Date Range:</strong> {{ $date_from ?? 'All time' }} to {{ $date_to ?? 'Present' }}</p>
        <p><strong>User Type Filter:</strong> 
            @if(isset($report_type) && $report_type == 'all')
                All Users
            @elseif(isset($user_role) && $user_role == 'admin')
                Admin Users
            @elseif(isset($user_role) && $user_role == 'agency')
                Agency Users
            @elseif(isset($user_role) && $user_role == 'publicuser')
                Public Users
            @else
                All Users
            @endif
        </p>
        <p><strong>Total Users:</strong> {{ isset($summary['total_users']) ? number_format($summary['total_users']) : '0' }}</p>
        <p><strong>Public Users:</strong> {{ isset($summary['public_users']) ? number_format($summary['public_users']) : '0' }}</p>
        <p><strong>Agencies:</strong> {{ isset($summary['agency_users']) ? number_format($summary['agency_users']) : '0' }}</p>
        <p><strong>Generated By:</strong> {{ $admin_name ?? 'Administrator' }}</p>
    </div>
      <!-- Custom section for agencies display -->
    @if(isset($user_role) && $user_role == 'agency')
    <h2>Agencies ({{ number_format($summary['agency_users'] ?? 0) }})</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Agency Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Created Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $user->user_id ?? $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->username ?? $user->email }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->contact_number ?? 'N/A' }}</td>
                <td>{{ $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No agencies found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <p style="font-style: italic; text-align: center; margin-top: 30px; color: #666; font-size: 12px;">
        This report was generated automatically by the AuthenticityHub Admin System.<br>
        Report contains {{ number_format($summary['agency_users'] ?? 0) }} user(s) for the period {{ $date_from ?? 'all time' }} to {{ $date_to ?? 'present' }}.
    </p>
    @else
    <!-- Standard summary display for other user types -->
    <div class="summary">
        <div class="summary-card">
            <div class="summary-number">{{ number_format($summary['total_users'] ?? 0) }}</div>
            <div class="summary-label">Total Users</div>
        </div>
        <div class="summary-card">
            <div class="summary-number">{{ number_format($summary['public_users'] ?? 0) }}</div>
            <div class="summary-label">Public Users</div>
        </div>
        <div class="summary-card">
            <div class="summary-number">{{ number_format($summary['agency_users'] ?? 0) }}</div>
            <div class="summary-label">Agency Users</div>
        </div>
        <div class="summary-card">
            <div class="summary-number">{{ number_format($summary['admin_users'] ?? 0) }}</div>
            <div class="summary-label">Admin Users</div>
        </div>
    </div>
    @endif
    
    <!-- Check if the report type is user_profile for different display -->
    @if(isset($report_type) && $report_type == 'user_profile')
        <h2>User Profiles</h2>
        
        @forelse($users as $user)
            <div class="user-profile">
                <div class="user-profile-header">
                    <h3>{{ $user->name }}</h3>
                    <div class="user-profile-role">
                        @if($user->user_role == 'admin')
                            <span class="admin">ADMIN</span>
                        @elseif($user->user_role == 'agency')
                            <span class="agency">AGENCY</span>
                        @elseif($user->user_role == 'publicuser')
                            <span class="publicuser">PUBLIC</span>
                        @else
                            {{ $user->user_role }}
                        @endif
                    </div>
                </div>
                
                <div class="user-profile-details">
                    <div class="user-profile-field">
                        <div class="user-profile-label">User ID</div>
                        <div class="user-profile-value">{{ $user->user_id ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="user-profile-field">
                        <div class="user-profile-label">Email Address</div>
                        <div class="user-profile-value">{{ $user->email }}</div>
                    </div>
                    
                    <div class="user-profile-field">
                        <div class="user-profile-label">Contact Number</div>
                        <div class="user-profile-value">{{ $user->contact_number ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="user-profile-field">
                        <div class="user-profile-label">Registration Date</div>
                        <div class="user-profile-value">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</div>
                    </div>
                    
                    <div class="user-profile-field">
                        <div class="user-profile-label">Last Login</div>
                        <div class="user-profile-value">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y h:i A') : 'Never' }}</div>
                    </div>
                    
                    <div class="user-profile-field">
                        <div class="user-profile-label">Account Status</div>
                        <div class="user-profile-value">{{ $user->active ? 'Active' : 'Inactive' }}</div>
                    </div>
                </div>
            </div>
        @empty
            <p style="text-align: center; padding: 20px; background: #f8f9fa; border-radius: 5px;">No user profiles found matching the criteria</p>
        @endforelse
        
    @else
        <h2>User Details</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Role</th>
                    <th>Created Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->user_id ?? 'N/A' }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->contact_number ?? 'N/A' }}</td>
                        <td>
                            @if($user->user_role == 'admin')
                                <span class="admin">ADMIN</span>
                            @elseif($user->user_role == 'agency')
                                <span class="agency">AGENCY</span>
                            @elseif($user->user_role == 'publicuser')
                                <span class="publicuser">PUBLIC</span>
                            @else
                                {{ $user->user_role }}
                            @endif
                        </td>
                        <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">No users found matching the criteria</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif
</body>
</html>
