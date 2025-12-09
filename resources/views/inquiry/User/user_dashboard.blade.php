@extends('components.side_headr')

@section('title', 'My Inquiries Dashboard - Inquira')

@section('additional_css')
<style>

      
        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
        }

       
    
        /* Content Area */
        .content {
            padding: 10px;
            margin-left: -130px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 20px;
        }

        .stat-total { background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; }
        .stat-pending { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; }
        .stat-progress { background: linear-gradient(135deg, #06b6d4, #0891b2); color: white; }
        .stat-resolved { background: linear-gradient(135deg, #10b981, #059669); color: white; }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .stat-label {
            color: #64748b;
            font-weight: 500;
        }

        /* Filter Section */
        .filter-section {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            margin-bottom: 24px;
        }

        .filter-form {
            display: flex;
            gap: 16px;
            align-items: end;
        }

        .filter-group {
            flex: 1;
        }

        .filter-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
        }

        .filter-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            background: white;
        }

        .filter-btn {
            padding: 12px 24px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .filter-btn:hover {
            background: #2563eb;
        }

        /* Inquiries Table */
        .inquiries-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .section-header {
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
        }

        .inquiries-table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-header {
            background: #f8fafc;
        }

        .table-header th {
            padding: 16px 24px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-row {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.2s;
        }

        .table-row:hover {
            background: #f8fafc;
        }

        .table-cell {
            padding: 16px 24px;
            vertical-align: top;
        }

        .inquiry-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .inquiry-category {
            font-size: 12px;
            color: #64748b;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-pending { background: #fef3c7; color: #d97706; }
        .status-in-progress { background: #dbeafe; color: #2563eb; }
        .status-resolved { background: #d1fae5; color: #059669; }
        .status-closed { background: #f1f5f9; color: #64748b; }
        .status-rejected { background: #fee2e2; color: #dc2626; }

        .date-text {
            color: #64748b;
            font-size: 14px;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s;
        }

        .action-btn:hover {
            background: #2563eb;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 64px 32px;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 24px;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 32px;
        }

        .empty-title {
            font-size: 24px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .empty-description {
            color: #64748b;
            margin-bottom: 24px;
        }

        .create-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: background 0.2s;
        }

        .create-btn:hover {
            background: #2563eb;
        }

        /* Pagination */
        .pagination-wrapper {
            padding: 24px;
            border-top: 1px solid #e2e8f0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .main-content {
                margin-left: 0;
            }

            .header {
                padding: 16px 20px;
            }

            .content {
                padding: 20px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .table-container {
                overflow-x: auto;
            }
        }
    </style>
@endsection

@section('content')
  
    <!-- Main Content -->
    <div class="main-content">
      
        <!-- Content -->
        <div class="content">
            <!-- Removed Statistics Cards (stats-grid) -->
            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" action="{{ route('inquiry.dashboard') }}" class="filter-form">
                    <div class="filter-group">
                        <label for="status" class="filter-label">Filter by Status</label>
                        <select name="status" id="status" class="filter-select">
                            <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>All Status</option>
                            <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Resolved" {{ request('status') === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="Closed" {{ request('status') === 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <button type="submit" class="filter-btn">
                        <i class="fas fa-filter"></i>
                        Apply Filter
                    </button>
                </form>
            </div>

            <!-- Inquiries Table -->
            <div class="inquiries-section">
                <div class="section-header">
                    <h2 class="section-title">Your Inquiries</h2>
                </div>

                @if($inquiries->count() > 0)
                <div class="table-container">
                    <table class="inquiries-table">
                        <thead class="table-header">
                            <tr>
                                <th>Inquiry Details</th>
                                <th>Status</th>
                                <th>Date Submitted</th>
                                <th>Last Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inquiries as $inquiry)
                            <tr class="table-row">
                                <td class="table-cell">
                                    <div class="inquiry-title">{{ $inquiry->title }}</div>
                                    @if($inquiry->category)
                                    <div class="inquiry-category">{{ $inquiry->category }}</div>
                                    @endif
                                </td>
                                <td class="table-cell">
                                    <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $inquiry->status)) }}">
                                        @switch($inquiry->status)
                                            @case('Pending')
                                                <i class="fas fa-clock"></i>
                                                @break
                                            @case('In Progress')
                                                <i class="fas fa-spinner"></i>
                                                @break
                                            @case('Resolved')
                                                <i class="fas fa-check-circle"></i>
                                                @break
                                            @case('Closed')
                                                <i class="fas fa-times-circle"></i>
                                                @break
                                            @default
                                                <i class="fas fa-question"></i>
                                        @endswitch
                                        {{ $inquiry->status }}
                                    </span>
                                </td>
                                <td class="table-cell">
                                    <div class="date-text">{{ $inquiry->date_submitted->format('M d, Y') }}</div>
                                    <div class="date-text" style="font-size: 12px;">{{ $inquiry->date_submitted->format('H:i') }}</div>
                                </td>
                                <td class="table-cell">
                                    <div class="date-text">{{ $inquiry->updated_at->format('M d, Y') }}</div>
                                    <div class="date-text" style="font-size: 12px;">{{ $inquiry->updated_at->format('H:i') }}</div>
                                </td>
                                <td class="table-cell">
                                    <a href="{{ route('inquiry.show', $inquiry->inquiry_id) }}" class="action-btn">
                                        <i class="fas fa-eye"></i>
                                        View Details
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($inquiries->hasPages())
                <div class="pagination-wrapper">
                    {{ $inquiries->links() }}
                </div>
                @endif

                @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-file-text"></i>
                    </div>
                    <h3 class="empty-title">No Inquiries Found</h3>
                    <p class="empty-description">
                        @if(request('status') && request('status') !== 'all')
                            You don't have any inquiries with "{{ request('status') }}" status.
                        @else
                            You haven't submitted any inquiries yet. Start by creating your first inquiry!
                        @endif
                    </p>
                    @if(request('status') && request('status') !== 'all')
                        <a href="{{ route('inquiry.dashboard') }}" class="create-btn">
                            <i class="fas fa-list"></i>
                            View All Inquiries
                        </a>
                    @else
                        <a href="{{ route('inquiry.create') }}" class="create-btn">
                            <i class="fas fa-plus"></i>
                            Submit Your First Inquiry
                        </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
@endsection