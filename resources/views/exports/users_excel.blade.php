<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>MCMC Users Report</title>
    <style>
        /* Excel-compatible styles */
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Calibri, Arial, sans-serif;
            font-size: 11pt;
        }
        
        .report-title {
            background-color: #2E7D32;
            color: white;
            font-weight: bold;
            font-size: 16pt;
            text-align: center;
            padding: 12px;
            border: 2px solid #1B5E20;
        }
        
        .report-date {
            background-color: #E8F5E8;
            text-align: center;
            padding: 8px;
            font-size: 10pt;
            border: 1px solid #C8E6C9;
        }
        
        .header-cell {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 10px;
            border: 2px solid #388E3C;
            font-size: 11pt;
        }
        
        .data-cell {
            padding: 8px;
            border: 1px solid #BDBDBD;
            vertical-align: middle;
        }
        
        .data-cell-center {
            padding: 8px;
            border: 1px solid #BDBDBD;
            text-align: center;
            vertical-align: middle;
        }
        
        .data-cell-right {
            padding: 8px;
            border: 1px solid #BDBDBD;
            text-align: right;
            vertical-align: middle;
        }
        
        .row-even {
            background-color: #F9F9F9;
        }
        
        .row-odd {
            background-color: white;
        }
        
        .status-active {
            background-color: #C8E6C9;
            color: #1B5E20;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 3px;
        }
        
        .status-inactive {
            background-color: #FFCDD2;
            color: #B71C1C;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 3px;
        }
        
        .role-admin {
            background-color: #FFF3E0;
            color: #E65100;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 3px;
        }
        
        .role-user {
            background-color: #E3F2FD;
            color: #0D47A1;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 3px;
        }
        
        .summary-header {
            background-color: #388E3C;
            color: white;
            font-weight: bold;
            padding: 10px;
            border: 2px solid #2E7D32;
        }
        
        .summary-cell {
            background-color: #E8F5E8;
            padding: 8px;
            border: 1px solid #4CAF50;
            font-weight: bold;
            text-align: right;
        }
        
        .empty-row {
            height: 15px;
        }
        
        .no-data {
            background-color: #FFF3E0;
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #666;
            border: 1px solid #FFB74D;
        }
    </style>
</head>
<body>
    <table>
        <!-- Report Title -->
        <tr>
            <td colspan="8" class="report-title">
                MCMC USERS REPORT
            </td>
        </tr>
        
        <!-- Date -->
        <tr>
            <td colspan="8" class="report-date">
                Generated on: {{ date('F d, Y H:i:s') }}
            </td>
        </tr>
        
        <!-- Empty Row -->
        <tr class="empty-row">
            <td colspan="8">&nbsp;</td>
        </tr>
        
        <!-- Column Headers -->
        <tr>
            <td class="header-cell">ID</td>
            <td class="header-cell">FULL NAME</td>
            <td class="header-cell">EMAIL ADDRESS</td>
            <td class="header-cell">USER ROLE</td>
            <td class="header-cell">CONTACT NUMBER</td>
            <td class="header-cell">STATUS</td>
            <td class="header-cell">DATE CREATED</td>
            <td class="header-cell">LAST LOGIN</td>
        </tr>
        
        <!-- Data Rows -->
        @forelse($users as $index => $user)
        <tr class="{{ $index % 2 == 0 ? 'row-even' : 'row-odd' }}">
            <td class="data-cell-right">{{ $user->id }}</td>
            <td class="data-cell">{{ $user->name ?? 'N/A' }}</td>
            <td class="data-cell">{{ $user->email ?? 'N/A' }}</td>
            <td class="data-cell-center">
                <span class="role-{{ strtolower($user->role ?? 'user') }}">
                    {{ strtoupper($user->role ?? 'USER') }}
                </span>
            </td>
            <td class="data-cell">{{ $user->contact_number ?? 'N/A' }}</td>
            <td class="data-cell-center">
                @if(isset($user->is_active))
                    <span class="status-{{ $user->is_active ? 'active' : 'inactive' }}">
                        {{ $user->is_active ? 'ACTIVE' : 'INACTIVE' }}
                    </span>
                @else
                    <span class="status-active">ACTIVE</span>
                @endif
            </td>
            <td class="data-cell-center">
                {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}
            </td>
            <td class="data-cell-center">
                {{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'NEVER' }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="no-data">
                NO USERS FOUND IN THE SYSTEM
            </td>
        </tr>
        @endforelse
        
        <!-- Empty Rows for Spacing -->
        <tr class="empty-row"><td colspan="8">&nbsp;</td></tr>
        <tr class="empty-row"><td colspan="8">&nbsp;</td></tr>
        
        <!-- Summary Section -->
        <tr>
            <td colspan="6" class="summary-header">TOTAL USERS IN SYSTEM:</td>
            <td colspan="2" class="summary-cell">{{ count($users) }}</td>
        </tr>
        <tr>
            <td colspan="6" class="summary-header">ACTIVE USERS COUNT:</td>
            <td colspan="2" class="summary-cell">{{ $users->where('is_active', true)->count() ?? $users->count() }}</td>
        </tr>
        <tr>
            <td colspan="6" class="summary-header">ADMIN USERS COUNT:</td>
            <td colspan="2" class="summary-cell">{{ $users->where('role', 'admin')->count() }}</td>
        </tr>
        <tr>
            <td colspan="6" class="summary-header">INACTIVE USERS COUNT:</td>
            <td colspan="2" class="summary-cell">{{ $users->where('is_active', false)->count() }}</td>
        </tr>
        
        <!-- Footer Spacing -->
        <tr class="empty-row"><td colspan="8">&nbsp;</td></tr>
        <tr>
            <td colspan="8" class="report-date">
                Report generated by MCMC System | {{ date('Y') }}
            </td>
        </tr>
    </table>
</body>
</html>