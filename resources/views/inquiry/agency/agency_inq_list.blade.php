@extends('components.agency_side_headr')

@section('title', 'Agency Inquiry Records')
@section('page_title', 'Inquiry Records')

@section('additional_styles')
<style>    .container {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        margin-left: 0;
        padding-left: 0;
    }

    .dashboard-content {
        padding: 40px;
        margin-left: 0;
    }

    .welcome-card {
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        text-align: center;
        margin-bottom: 30px;
    }

    .welcome-card h2 {
        color: #2c3e50;
        font-size: 28px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
    }

    .welcome-card p {
        color: #7f8c8d;
        font-size: 16px;
        line-height: 1.6;
    }

    /* Statistics Cards */
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
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border-left: 5px solid;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-align: center;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-card.total {
        border-left-color: #3498db;
        color: #3498db;
    }

    .stat-card.investigation {
        border-left-color: #f39c12;
        color: #f39c12;
    }

    .stat-card.verified {
        border-left-color: #27ae60;
        color: #27ae60;
    }

    .stat-card.fake {
        border-left-color: #e74c3c;
        color: #e74c3c;
    }

    .stat-card.rejected {
        border-left-color: #95a5a6;
        color: #95a5a6;
    }

    .stat-card.pending {
        border-left-color: #9b59b6;
        color: #9b59b6;
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        justify-content: center;
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

    .stat-card.total .stat-icon {
        background: linear-gradient(135deg, #3498db, #2980b9);
    }

    .stat-card.investigation .stat-icon {
        background: linear-gradient(135deg, #f39c12, #e67e22);
    }

    .stat-card.verified .stat-icon {
        background: linear-gradient(135deg, #27ae60, #229954);
    }

    .stat-card.fake .stat-icon {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
    }

    .stat-card.rejected .stat-icon {
        background: linear-gradient(135deg, #95a5a6, #7f8c8d);
    }

    .stat-card.pending .stat-icon {
        background: linear-gradient(135deg, #9b59b6, #8e44ad);
    }

    .stat-number {
        font-size: 36px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 8px;
    }

    .stat-label {
        color: #7f8c8d;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Filters Card */
    .filters-card {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .filters-card h3 {
        color: #2c3e50;
        font-size: 22px;
        margin-bottom: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filters-card h3 i {
        color: #3498db;
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        align-items: end;
        margin-bottom: 20px;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .filter-group input,
    .filter-group select {
        padding: 12px 16px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s ease;
        background: white;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 15px;
        justify-content: flex-start;
        margin-top: 20px;
    }

    /* Inquiries Card */
    .inquiries-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .card-header {
        padding: 25px 30px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8f9fa;
    }

    .card-header h3 {
        color: #2c3e50;
        font-size: 22px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header h3 i {
        color: #3498db;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .count-badge {
        background: #3498db;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    /* Buttons */
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: #3498db;
        color: white;
    }

    .btn-primary:hover {
        background: #2980b9;
        transform: translateY(-2px);
        color: white;
    }

    .btn-secondary {
        background: #95a5a6;
        color: white;
    }

    .btn-secondary:hover {
        background: #7f8c8d;
        color: white;
    }

    .btn-outline-secondary {
        background: transparent;
        color: #95a5a6;
        border: 2px solid #95a5a6;
    }

    .btn-outline-secondary:hover {
        background: #95a5a6;
        color: white;
    }

    /* Table Styles */
    .table-container {
        overflow-x: auto;
    }

    .inquiries-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .inquiries-table th {
        background: #f8f9fa;
        padding: 15px 12px;
        text-align: left;
        font-weight: 600;
        color: #2c3e50;
        border-bottom: 2px solid #e9ecef;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
    }

    .inquiries-table td {
        padding: 15px 12px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .inquiries-table tr:hover {
        background: #f8f9fa;
        transform: scale(1.001);
        transition: all 0.3s ease;
    }

    /* Status and Priority Badges */
    .status-badge,
    .priority-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
    }

    .status-badge.pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-badge.investigation {
        background: #cce5ff;
        color: #004085;
    }

    .status-badge.verified {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.fake {
        background: #f8d7da;
        color: #721c24;
    }

    .status-badge.rejected {
        background: #e2e3e5;
        color: #383d41;
    }

    .status-badge.progress {
        background: #e2e3e5;
        color: #383d41;
    }

    .status-badge.resolved {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.closed {
        background: #e2e3e5;
        color: #383d41;
    }

    .priority-badge.high {
        background: #f8d7da;
        color: #721c24;
    }

    .priority-badge.medium {
        background: #fff3cd;
        color: #856404;
    }

    .priority-badge.low {
        background: #d4edda;
        color: #155724;
    }

    .priority-badge.urgent {
        background: #f8d7da;
        color: #721c24;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        width: 35px;
        height: 35px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .btn-action.view {
        background: #3498db;
        color: white;
    }

    .btn-action.view:hover {
        background: #2980b9;
        transform: scale(1.1);
        color: white;
    }

    .btn-action.edit {
        background: #f39c12;
        color: white;
    }

    .btn-action.edit:hover {
        background: #e67e22;
        transform: scale(1.1);
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #7f8c8d;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
        color: #bdc3c7;
    }

    .empty-state h4 {
        margin-bottom: 10px;
        color: #2c3e50;
        font-size: 24px;
    }

    .empty-state p {
        margin-bottom: 25px;
        font-size: 16px;
    }

    /* Pagination */
    .pagination-container {
        padding: 20px 30px;
        border-top: 1px solid #e9ecef;
        background: #f8f9fa;
        display: flex;
        justify-content: center;
    }

    .pagination .page-link {
        border: none;
        color: #3498db;
        margin: 0 3px;
        border-radius: 8px;
        padding: 10px 15px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .pagination .page-item.active .page-link {
        background: #3498db;
        border-color: transparent;
        color: white;
    }

    .pagination .page-link:hover {
        background: rgba(52, 152, 219, 0.1);
        color: #3498db;
        transform: translateY(-2px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container {
            margin-left: 0;
        }

        .dashboard-content {
            padding: 20px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .filters-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .stat-card {
            padding: 20px;
        }

        .stat-number {
            font-size: 28px;
        }

        .welcome-card {
            padding: 30px 20px;
        }

        .welcome-card h2 {
            font-size: 24px;
            flex-direction: column;
            gap: 10px;
        }

        .filters-card, .inquiries-card {
            margin: 0 -20px 30px -20px;
            border-radius: 0;
        }

        .inquiries-table {
            font-size: 12px;
        }

        .inquiries-table th,
        .inquiries-table td {
            padding: 10px 8px;
        }

        .btn-action {
            width: 30px;
            height: 30px;
            font-size: 14px;
        }
    }

    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-header {
            flex-direction: column;
            gap: 10px;
        }

        .filter-actions {
            flex-direction: column;
        }

        .card-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="dashboard-content">
        <!-- Welcome Card -->
        <div class="welcome-card">
            <h2>
                <i class="fas fa-file-alt"></i>
                Inquiry Records Management
            </h2>
            <p>
                Comprehensive view of all past inquiries assigned to your agency with advanced filtering and search capabilities
            </p>
        </div>

       <!-- Filter Section -->
        <div class="filters-card">
            <h3>
                <i class="fas fa-filter"></i>
                Advanced Filters & Search
            </h3>
            
            <form method="GET" action="{{ route('agency.inquiry.list') }}" id="filterForm">
                <div class="filters-grid">
                    <!-- Search -->
                    <div class="filter-group">
                        <label for="search">Search Inquiries</label>
                        <input type="text" id="search" name="search" 
                               placeholder="Search by title, content, citizen name, or inquiry ID..." 
                               value="{{ $search }}">
                    </div>

                    <!-- Status Filter -->
                    <div class="filter-group">
                        <label for="status">Inquiry Status</label>
                        <select id="status" name="status">
                            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses</option>
                            <option value="under_investigation" {{ $status === 'under_investigation' ? 'selected' : '' }}>Under Investigation</option>
                            <option value="verified_true" {{ $status === 'verified_true' ? 'selected' : '' }}>Verified as True</option>
                            <option value="identified_fake" {{ $status === 'identified_fake' ? 'selected' : '' }}>Identified as Fake</option>
                            <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="Pending" {{ $status === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="In Progress" {{ $status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Resolved" {{ $status === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="Closed" {{ $status === 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div class="filter-group">
                        <label for="category">Category</label>
                        <select id="category" name="category">
                            <option value="all" {{ $category === 'all' ? 'selected' : '' }}>All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>
                                    {{ ucfirst($cat) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Range Quick Select -->
                    <div class="filter-group">
                        <label for="date_range">Date Range</label>
                        <select id="date_range" name="date_range">
                            <option value="all" {{ $dateRange === 'all' ? 'selected' : '' }}>All Time</option>
                            <option value="today" {{ $dateRange === 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ $dateRange === 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ $dateRange === 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="year" {{ $dateRange === 'year' ? 'selected' : '' }}>This Year</option>
                            <option value="last_30_days" {{ $dateRange === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="last_90_days" {{ $dateRange === 'last_90_days' ? 'selected' : '' }}>Last 90 Days</option>
                        </select>
                    </div>

                    <!-- Custom Date From -->
                    <div class="filter-group">
                        <label for="date_from">From Date</label>
                        <input type="date" id="date_from" name="date_from" value="{{ $dateFrom }}">
                    </div>

                    <!-- Custom Date To -->
                    <div class="filter-group">
                        <label for="date_to">To Date</label>
                        <input type="date" id="date_to" name="date_to" value="{{ $dateTo }}">
                    </div>

                    <!-- Sort By -->
                    <div class="filter-group">
                        <label for="sort_by">Sort By</label>
                        <select id="sort_by" name="sort_by">
                            <option value="date_submitted" {{ $sortBy === 'date_submitted' ? 'selected' : '' }}>Date Submitted</option>
                            <option value="title" {{ $sortBy === 'title' ? 'selected' : '' }}>Title</option>
                            <option value="status" {{ $sortBy === 'status' ? 'selected' : '' }}>Status</option>
                            <option value="category" {{ $sortBy === 'category' ? 'selected' : '' }}>Category</option>
                            <option value="citizen_name" {{ $sortBy === 'citizen_name' ? 'selected' : '' }}>Citizen Name</option>
                        </select>
                    </div>

                    <!-- Sort Order -->
                    <div class="filter-group">
                        <label for="sort_order">Sort Order</label>
                        <select id="sort_order" name="sort_order">
                            <option value="desc" {{ $sortOrder === 'desc' ? 'selected' : '' }}>Newest First</option>
                            <option value="asc" {{ $sortOrder === 'asc' ? 'selected' : '' }}>Oldest First</option>
                        </select>
                    </div>
                </div>

                <!-- Filter Actions -->
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Apply Filters
                    </button>
                    <a href="{{ route('agency.inquiry.list') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-refresh"></i> Reset Filters
                    </a>
                </div>
            </form>
        </div>

        <!-- Inquiries Table -->
        <div class="inquiries-card">
            <div class="card-header">
                <h3>
                    <i class="fas fa-table"></i>
                    Inquiry Records
                </h3>
                <div class="header-actions">
                    <span class="count-badge">{{ $inquiries->total() }} total</span>
                </div>
            </div>

            @if($inquiries->count() > 0)
                <div class="table-container">
                    <table class="inquiries-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Citizen</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Date Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inquiries as $inquiry)
                                <tr>
                                    <td>
                                        <strong>#{{ $inquiry->inquiry_id ?? $inquiry->id }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ Str::limit($inquiry->title, 40) }}</strong>
                                            @if($inquiry->media_attachment)
                                                <br><small style="color: #7f8c8d;"><i class="fas fa-paperclip"></i> Has attachment</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $inquiry->user->name ?? 'N/A' }}</strong>
                                            <br>
                                            <small style="color: #7f8c8d;">{{ $inquiry->user->email ?? 'N/A' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge">{{ ucfirst($inquiry->category ?? 'General') }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($inquiry->status) {
                                                'Pending' => 'pending',
                                                'Under Investigation' => 'investigation',
                                                'Verified as True' => 'verified',
                                                'Identified as Fake' => 'fake',
                                                'Rejected' => 'rejected',
                                                'In Progress' => 'progress',
                                                'Resolved' => 'resolved',
                                                'Closed' => 'closed',
                                                default => 'pending'
                                            };
                                        @endphp
                                        <span class="status-badge {{ $statusClass }}">
                                            {{ $inquiry->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $priority = $inquiry->priority ?? 'medium';
                                            $priorityClass = strtolower($priority);
                                        @endphp
                                        <span class="priority-badge {{ $priorityClass }}">
                                            {{ ucfirst($priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $inquiry->date_submitted ? $inquiry->date_submitted->format('M d, Y') : 'N/A' }}</strong>
                                            <br>
                                            <small style="color: #7f8c8d;">{{ $inquiry->date_submitted ? $inquiry->date_submitted->format('h:i A') : '' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('inquiry.show', $inquiry->inquiry_id ?? $inquiry->id) }}" 
                                               class="btn-action view" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($inquiry->status === 'Pending' || $inquiry->status === 'Assigned')
                                                <button type="button" class="btn-action edit" 
                                                        onclick="showQuickActions({{ $inquiry->inquiry_id ?? $inquiry->id }})" 
                                                        title="Quick Actions">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-container">
                    {{ $inquiries->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h4>No Inquiries Found</h4>
                    <p>There are no inquiries matching your current filters.</p>
                    <a href="{{ route('agency.inquiry.list') }}" class="btn btn-primary">
                        <i class="fas fa-refresh"></i> Reset Filters
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Custom JavaScript -->
<script>
    // Auto-submit form when filters change
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('filterForm');
        const selects = form.querySelectorAll('select[name="date_range"], select[name="status"], select[name="category"]');
        
        selects.forEach(select => {
            select.addEventListener('change', function() {
                // Small delay to allow user to see the change
                setTimeout(() => {
                    form.submit();
                }, 100);
            });
        });

        // Clear custom dates when quick date range is selected
        const dateRangeSelect = document.getElementById('date_range');
        const dateFromInput = document.getElementById('date_from');
        const dateToInput = document.getElementById('date_to');

        dateRangeSelect.addEventListener('change', function() {
            if (this.value !== 'all') {
                dateFromInput.value = '';
                dateToInput.value = '';
            }
        });

        // Clear date range select when custom dates are used
        [dateFromInput, dateToInput].forEach(input => {
            input.addEventListener('change', function() {
                if (this.value) {
                    dateRangeSelect.value = 'all';
                }
            });
        });
    });

    // Quick Actions Modal/Dropdown
    function showQuickActions(inquiryId) {
        // This would open a modal or dropdown with quick action options
        // For now, just redirect to the detail page
        window.location.href = `/inquiry/${inquiryId}`;
    }

    // Loading state for form submission
    document.getElementById('filterForm').addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Applying...';
        submitBtn.disabled = true;
    });

    // Success/Error message handling
    @if(session('success'))
        // Show success toast or notification
        console.log('Success: {{ session('success') }}');
    @endif

    @if(session('error'))
        // Show error toast or notification
        console.log('Error: {{ session('error') }}');
    @endif
</script>
@endsection