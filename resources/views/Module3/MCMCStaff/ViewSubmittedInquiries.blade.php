<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>View Submitted Inquiries - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
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
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-card h3 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .stat-card p {
            margin: 0;
            color: var(--dark-text);
            font-weight: 500;
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

        .table-dark {
            background: var(--secondary-color) !important;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-in-progress {
            background: #cff4fc;
            color: #055160;
        }

        .status-resolved {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-closed {
            background: #f8d7da;
            color: #721c24;
        }

        .priority-high {
            color: var(--danger-color);
        }

        .priority-medium {
            color: var(--warning-color);
        }

        .priority-low {
            color: var(--success-color);
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

        .modal-header {
            background: var(--primary-color);
            color: white;
        }

        .modal-header .btn-close {
            filter: invert(1);
        }

        .timeline-item {
            border-left: 3px solid var(--primary-color);
            padding-left: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
        }

        .btn-action {
            margin: 0 0.25rem;
            border-radius: 20px;
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
        }

        .btn-group .btn-action {
            margin: 0;
            border-radius: 0;
        }

        .btn-group .btn-action:first-child {
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
        }

        .btn-group .btn-action:last-child {
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
        }

        .btn-group .btn-action:only-child {
            border-radius: 20px;
        }

        /* Assignment Modal Styles */
        .inquiry-summary {
            border-left: 4px solid var(--primary-color);
        }

        .content-preview {
            background: white;
            padding: 0.5rem;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }

        .agency-info-section .agency-details {
            border: 1px solid #dee2e6;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-text);
        }

        .form-select:focus,
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        @media (max-width: 768px) {
            .main-container {
                margin: 10px;
            }

            .header-content {
                text-align: center;
            }

            .stats-row .row>div {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="header-content">
                <div>
                    <h1 class="h3 mb-2 header-title">
                        <i class="fas fa-list-alt me-2"></i>
                        View Submitted Inquiries
                    </h1>
                    <p class="mb-0 opacity-75">Monitor and manage all submitted inquiries in the system</p>
                </div>
                <a href="{{ route('admin.home') }}" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="stats-row">
            <div class="row g-4">
                <div class="col-md-2">
                    <div class="stat-card">
                        <h3>{{ $statistics['total'] ?? 0 }}</h3>
                        <p>Total Inquiries</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-card">
                        <h3>{{ $statistics['pending'] ?? 0 }}</h3>
                        <p>Pending</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-card">
                        <h3>{{ $statistics['in_progress'] ?? 0 }}</h3>
                        <p>In Progress</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-card">
                        <h3>{{ $statistics['resolved'] ?? 0 }}</h3>
                        <p>Resolved</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-card">
                        <h3>{{ $statistics['closed'] ?? 0 }}</h3>
                        <p>Closed</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            <!-- Search and Filter -->
            <div class="search-filter-section">
                <form method="GET" action="{{ route('admin.inquiries') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search Inquiries</label>
                            <input type="text" class="form-control" id="search" name="search"
                                value="{{ request('search') }}" placeholder="Search by title, user, ID...">
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status Filter</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">All Statuses</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In
                                    Progress</option>
                                <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved
                                </option>
                                <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="category" class="form-label">Category Filter</label>
                            <select class="form-control" id="category" name="category">
                                <option value="">All Categories</option>
                                <option value="technical" {{ request('category') == 'technical' ? 'selected' : '' }}>
                                    Technical</option>
                                <option value="billing" {{ request('category') == 'billing' ? 'selected' : '' }}>Billing
                                </option>
                                <option value="service" {{ request('category') == 'service' ? 'selected' : '' }}>Service
                                </option>
                                <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>General
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i>
                                Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Inquiries Table -->
            @if(isset($inquiries) && count($inquiries) > 0)
                <div class="inquiry-table">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Submitted By</th>
                                    <th>Date Submitted</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inquiries as $inquiry)
                                    <tr>
                                        <td><strong>#{{ $inquiry->inquiry_id ?? $inquiry->id ?? 'N/A' }}</strong></td>
                                        <td>
                                            <strong>{{ Str::limit($inquiry->title ?? 'No Title', 40) }}</strong>
                                            @if($inquiry->assignment_notes ?? false)
                                                <br><small class="text-muted">
                                                    <i class="fas fa-sticky-note"></i> Has notes
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $inquiry->user->name ?? 'Unknown User' }}<br>
                                            <small class="text-muted">{{ $inquiry->user->email ?? 'N/A' }}</small>
                                        </td>
                                        <td>{{ $inquiry->date_submitted ? $inquiry->date_submitted->format('M d, Y H:i') : ($inquiry->created_at ? $inquiry->created_at->format('M d, Y H:i') : 'N/A') }}
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ ucfirst($inquiry->category ?? 'General') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="status-badge status-{{ strtolower(str_replace(' ', '-', $inquiry->status ?? 'pending')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $inquiry->status ?? 'Pending')) }}
                                            </span>
                                        </td>
                                        <td>{{ $inquiry->updated_at ? $inquiry->updated_at->format('M d, Y H:i') : 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary btn-action" data-bs-toggle="modal"
                                                    data-bs-target="#viewModal{{ $inquiry->id ?? $inquiry->inquiry_id }}"
                                                    title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="{{ route('admin.inquiry.details', $inquiry->inquiry_id ?? $inquiry->id) }}"
                                                    class="btn btn-sm btn-outline-info btn-action" title="Full Details">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                                @if(in_array($inquiry->status ?? 'Pending', ['Pending', 'Under Review']) && $inquiry->verificationProcesses->isEmpty())
                                                    <a href="{{ route('admin.assign.form', $inquiry->inquiry_id ?? $inquiry->id) }}"
                                                        class="btn btn-sm btn-outline-success btn-action" title="Assign to Agency">
                                                        <i class="fas fa-tasks"></i>
                                                    </a>
                                                @elseif($inquiry->verificationProcesses->isNotEmpty())
                                                    <span class="btn btn-sm btn-outline-secondary btn-action disabled"
                                                        title="Already Assigned to {{ $inquiry->assignedAgency->agency_name ?? 'Agency' }}">
                                                        <i class="fas fa-check"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modals for viewing inquiries -->
                @foreach($inquiries as $inquiry)
                    <!-- View Modal -->
                    <div class="modal fade" id="viewModal{{ $inquiry->id ?? $inquiry->inquiry_id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Inquiry #{{ $inquiry->id ?? $inquiry->inquiry_id }} Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Submitted By:</strong> {{ $inquiry->user->name ?? 'Unknown User' }}<br>
                                            <strong>Email:</strong> {{ $inquiry->user->email ?? 'N/A' }}<br>
                                            <strong>Date Submitted:</strong>
                                            {{ $inquiry->date_submitted ? $inquiry->date_submitted->format('M d, Y H:i') : ($inquiry->created_at ? $inquiry->created_at->format('M d, Y H:i') : 'N/A') }}<br>
                                            <strong>Last Updated:</strong>
                                            {{ $inquiry->updated_at ? $inquiry->updated_at->format('M d, Y H:i') : 'N/A' }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Category:</strong> {{ ucfirst($inquiry->category ?? 'General') }}<br>
                                            <strong>Status:</strong>
                                            <span
                                                class="status-badge status-{{ strtolower(str_replace(' ', '-', $inquiry->status ?? 'pending')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $inquiry->status ?? 'Pending')) }}
                                            </span><br>
                                            <strong>Priority:</strong>
                                            <i class="fas fa-circle priority-{{ $inquiry->priority ?? 'medium' }}"></i>
                                            {{ ucfirst($inquiry->priority ?? 'Medium') }}
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="mb-3">
                                        <strong>Title:</strong><br>
                                        <h6>{{ $inquiry->title ?? 'No title provided' }}</h6>
                                    </div>

                                    <div class="mb-3">
                                        <strong>Content:</strong><br>
                                        <div class="bg-light p-3 rounded">{{ $inquiry->content ?? 'No content provided' }}</div>
                                    </div>

                                    @if($inquiry->evidence_url ?? false)
                                        <div class="mb-3">
                                            <strong>Evidence URL:</strong><br>
                                            <a href="{{ $inquiry->evidence_url }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-external-link-alt"></i> View Evidence
                                            </a>
                                        </div>
                                    @endif

                                    @if($inquiry->assignment_notes ?? false)
                                        <div class="mb-3">
                                            <strong>Assignment Notes:</strong><br>
                                            <div class="bg-info bg-opacity-10 p-3 rounded">{{ $inquiry->assignment_notes }}</div>
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-footer"> <a
                                        href="{{ route('admin.inquiry.details', $inquiry->inquiry_id ?? $inquiry->id) }}"
                                        class="btn btn-primary">
                                        <i class="fas fa-external-link-alt"></i> View Full Details
                                    </a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            @else
                <div class="no-inquiries">
                    <i class="fas fa-inbox"></i>
                    <h4>No Inquiries Found</h4>
                    <p class="text-muted">
                        @if(request()->hasAny(['search', 'status', 'category']))
                            No inquiries match your current filter criteria. Try adjusting your search parameters.
                        @else
                            No inquiries have been submitted yet.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'status', 'category']))
                        <a href="{{ route('admin.inquiries') }}" class="btn btn-outline-primary">
                            <i class="fas fa-refresh"></i> Clear Filters
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-focus search input
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search');
            if (searchInput && !searchInput.value) {
                searchInput.focus();
            }
        });

        // Form submission handling for search
        const searchForm = document.querySelector('form[action*="admin/inquiries"]');
        if (searchForm) {
            searchForm.addEventListener('submit', function (e) {
                const searchValue = document.getElementById('search').value.trim();
                const statusValue = document.getElementById('status').value;
                const categoryValue = document.getElementById('category').value;

                if (!searchValue && !statusValue && !categoryValue) {
                    e.preventDefault();
                    window.location.href = "{{ route('admin.inquiries') }}";
                }
            });
        }
    </script>
</body>

</html>