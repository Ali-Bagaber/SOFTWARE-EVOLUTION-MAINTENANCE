<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Agency Inquiries Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
        }

        .main-container {
            background: white;
            margin: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-title {
            margin: 0;
        }

        .agency-name {
            font-size: 1.5rem;
            margin-top: 0.5rem;
            opacity: 0.9;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            text-decoration: none;
        }

        .stats-row {
            padding: 2rem;
            background: var(--light-bg);
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-card h3 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            margin: 0;
            color: var(--dark-text);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .stat-total {
            color: var(--primary-color);
        }

        .stat-pending {
            color: var(--warning-color);
        }

        .stat-accepted {
            color: var(--success-color);
        }

        .stat-progress {
            color: #17a2b8;
        }

        .stat-resolved {
            color: #28a745;
        }

        .stat-rejected {
            color: var(--danger-color);
        }

        .content-section {
            padding: 2rem;
        }

        .search-filter-section {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .inquiry-table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background: var(--secondary-color);
            color: white;
        }

        .table tbody tr {
            transition: background-color 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-accepted {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-in-progress {
            background: #cff4fc;
            color: #055160;
        }

        .status-resolved {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .status-under-investigation {
            background: #e7f3ff;
            color: #004085;
        }

        .btn-action {
            margin: 0 0.25rem;
            border-radius: 20px;
            padding: 0.4rem 1rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .btn-view {
            background: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-view:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
        }

        .btn-accept {
            background: var(--success-color);
            color: white;
            border: none;
        }

        .btn-accept:hover {
            background: #229954;
            color: white;
        }

        .btn-reject {
            background: var(--danger-color);
            color: white;
            border: none;
        }

        .btn-reject:hover {
            background: #c0392b;
            color: white;
        }

        .no-inquiries {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        .no-inquiries i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .pagination {
            margin-top: 1.5rem;
            justify-content: center;
        }

        .pagination .page-link {
            color: var(--primary-color);
            border-radius: 5px;
            margin: 0 0.25rem;
        }

        .pagination .page-item.active .page-link {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }

        .btn-filter {
            background: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-filter:hover {
            background: var(--secondary-color);
            color: white;
        }

        .btn-reset {
            background: #6c757d;
            color: white;
            border: none;
        }

        .btn-reset:hover {
            background: #5a6268;
            color: white;
        }

        .btn-export {
            background: var(--success-color);
            color: white;
            border: none;
        }

        .btn-export:hover {
            background: #229954;
            color: white;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="header-content">
                <div>
                    <h1 class="header-title">
                        <i class="fas fa-clipboard-list"></i> Agency Inquiries Dashboard
                    </h1>
                    @if(isset($agency) && $agency)
                    <p class="agency-name">{{ $agency->agency_name ?? 'Agency Portal' }}</p>
                    @endif
                </div>
                <div>
                    <a href="{{ route('agency.dashboard') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        @if(isset($stats))
        <div class="stats-row">
            <div class="row g-3">
                <div class="col-md-2">
                    <div class="stat-card">
                        <h3 class="stat-total">{{ $stats['total'] ?? 0 }}</h3>
                        <p>Total Inquiries</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-card">
                        <h3 class="stat-pending">{{ $stats['pending'] ?? 0 }}</h3>
                        <p>Pending</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-card">
                        <h3 class="stat-accepted">{{ $stats['accepted'] ?? 0 }}</h3>
                        <p>Accepted</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-card">
                        <h3 class="stat-progress">{{ $stats['inProgress'] ?? 0 }}</h3>
                        <p>In Progress</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-card">
                        <h3 class="stat-resolved">{{ $stats['resolved'] ?? 0 }}</h3>
                        <p>Resolved</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-card">
                        <h3 class="stat-rejected">{{ $stats['rejected'] ?? 0 }}</h3>
                        <p>Rejected</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Main Content Section -->
        <div class="content-section">
            <!-- Search and Filter Section -->
            <div class="search-filter-section">
                <form method="GET" action="{{ route('agency.inquiry.list') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-search"></i> Search</label>
                            <input type="text" class="form-control" name="search"
                                value="{{ $search ?? '' }}"
                                placeholder="Search by ID, title, or description...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label"><i class="fas fa-tag"></i> Status</label>
                            <select class="form-select" name="status">
                                <option value="">All Statuses</option>
                                @if(isset($statuses))
                                @foreach($statuses as $statusOption)
                                <option value="{{ $statusOption }}"
                                    {{ (isset($status) && $status == $statusOption) ? 'selected' : '' }}>
                                    {{ $statusOption }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label"><i class="fas fa-folder"></i> Category</label>
                            <select class="form-select" name="category">
                                <option value="">All Categories</option>
                                @if(isset($categories))
                                @foreach($categories as $cat)
                                <option value="{{ $cat }}"
                                    {{ (isset($category) && $category == $cat) ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label"><i class="fas fa-calendar"></i> From Date</label>
                            <input type="date" class="form-control" name="date_from"
                                value="{{ $dateFrom ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label"><i class="fas fa-calendar"></i> To Date</label>
                            <input type="date" class="form-control" name="date_to"
                                value="{{ $dateTo ?? '' }}">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-filter w-100">
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 d-flex gap-2">
                            <a href="{{ route('agency.inquiry.list') }}" class="btn btn-reset">
                                <i class="fas fa-undo"></i> Reset Filters
                            </a>
                            <button type="submit" name="export" value="csv" class="btn btn-export">
                                <i class="fas fa-download"></i> Export to CSV
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Inquiries Table -->
            @if(isset($inquiries) && $inquiries->count() > 0)
            <div class="inquiry-table">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Inquiry ID</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Submitted Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inquiries as $inquiry)
                            <tr>
                                <td><strong>#{{ $inquiry->inquiry_id }}</strong></td>
                                <td>{{ Str::limit($inquiry->title ?? 'N/A', 50) }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $inquiry->category ?? 'General' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $inquiry->status)) }}">
                                        {{ $inquiry->status }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($inquiry->date_submitted)->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('agency.inquiry.view', $inquiry->inquiry_id) }}"
                                            class="btn btn-sm btn-view">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @if(in_array($inquiry->status, ['Pending', 'Under Investigation']))
                                        <a href="{{ route('agency.inquiry.accept', $inquiry->inquiry_id) }}"
                                            class="btn btn-sm btn-accept">
                                            <i class="fas fa-check"></i> Accept
                                        </a>
                                        <a href="{{ route('agency.inquiry.reject', $inquiry->inquiry_id) }}"
                                            class="btn btn-sm btn-reject">
                                            <i class="fas fa-times"></i> Reject
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center p-3">
                    {{ $inquiries->links() }}
                </div>
            </div>
            @else
            <div class="inquiry-table">
                <div class="no-inquiries">
                    <i class="fas fa-inbox"></i>
                    <h3>No Inquiries Found</h3>
                    <p>There are no inquiries matching your criteria.</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>