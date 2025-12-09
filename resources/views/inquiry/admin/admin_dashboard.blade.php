@extends('components.admin_side_headr')

@section('title', 'Inquira - Admin Inquiry Management')

@section('page_title', 'Admin Inquiry Management')

@section('additional_styles')
    <style>

        .container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin-left: -240px;

             /* Adjusted for sidebar width */
        }


        .dashboard-content {
            padding: 40px;
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
        }

        .welcome-card p {
            color: #7f8c8d;
            font-size: 16px;
            line-height: 1.6;
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
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-left: 5px solid;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-card.pending {
            border-left-color: #f39c12;
            color: #f39c12;
        }

        .stat-card.serious {
            border-left-color: #e74c3c;
            color: #e74c3c;
        }

        .stat-card.resolved {
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

        .stat-card.pending .stat-icon {
            background: linear-gradient(135deg, #f39c12, #e67e22);
        }

        .stat-card.serious .stat-icon {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }

        .stat-card.resolved .stat-icon {
            background: linear-gradient(135deg, #27ae60, #229954);
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
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            align-items: end;
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

        .filter-group select {
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            background: white;
        }

        .filter-group select:focus {
            outline: none;
            border-color: #3498db;
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
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
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
        }

        .inquiries-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .inquiries-table tr:hover {
            background: #f8f9fa;
        }

        /* Status and Priority Badges */
        .status-badge,
        .priority-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-badge.under-review {
            background: #cce5ff;
            color: #004085;
        }

        .status-badge.assigned {
            background: #f8d7da;
            color: #721c24;
        }

        .status-badge.in-progress {
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

        .status-badge.rejected {
            background: #f8d7da;
            color: #721c24;
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
        }

        .btn-action.view {
            background: #3498db;
            color: white;
        }

        .btn-action.view:hover {
            background: #2980b9;
            transform: scale(1.1);
        }

        .btn-action.discard {
            background: #e74c3c;
            color: white;
        }

        .btn-action.discard:hover {
            background: #c0392b;
            transform: scale(1.1);
        }

        .btn-action.assign {
            background: #27ae60;
            color: white;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
        }

        .btn-action.assign:hover {
            background: #229954;
            transform: scale(1.1);
            color: white;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease;
            max-height: 80vh;
            overflow-y: auto;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 25px 30px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            border-radius: 15px 15px 0 0;
        }

        .modal-header h3 {
            color: #2c3e50;
            font-size: 20px;
            font-weight: 600;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close:hover {
            color: #e74c3c;
        }

        .modal-body {
            padding: 30px;
        }

        .detail-row {
            display: flex;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f8f9fa;
        }

        .detail-label {
            font-weight: 600;
            color: #2c3e50;
            width: 120px;
            flex-shrink: 0;
        }

        .detail-value {
            color: #7f8c8d;
            flex: 1;
        }

        .modal-actions {
            padding: 20px 30px;
            border-top: 1px solid #e9ecef;
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            background: #f8f9fa;
            border-radius: 0 0 15px 15px;
        }

        .hidden {
            display: none;
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
        }
    </style>
@endsection

@section('content')
    <div class="container">
       
        <!-- Main Content -->
        <main class="main-content">
           

            <div class="dashboard-content">
                <!-- Welcome Card -->
                <div class="welcome-card">
                    <h2>Pending Inquiry Management</h2>
                    <p>Review and manage pending inquiries. Take action to forward inquiries to departments or discard
                        non-serious ones.</p>
                </div> <!-- Statistics -->
                <div class="stats-grid">
                    <div class="stat-card pending">
                        <div class="stat-header">
                            <div class="stat-icon">⏳</div>
                        </div>
                        <div class="stat-number">{{ $stats['pending'] ?? 0 }}</div>
                        <div class="stat-label">Pending Review</div>
                    </div>

                    <div class="stat-card serious">
                        <div class="stat-header">
                            <div class="stat-icon">🔍</div>
                        </div>
                        <div class="stat-number">{{ $stats['under_review'] ?? 0 }}</div>
                        <div class="stat-label">Under Review</div>
                    </div>

                    <div class="stat-card resolved">
                        <div class="stat-header">
                            <div class="stat-icon">📊</div>
                        </div>
                        <div class="stat-number">{{ $stats['total'] ?? 0 }}</div>
                        <div class="stat-label">Total Pending</div>
                    </div>
                </div> <!-- Filters Section -->
                <div class="filters-card">
                    <h3>Filter Pending Inquiries</h3>
                    <div class="filters-grid">
                        <div class="filter-group">
                            <label for="statusFilter">Status</label>
                            <select id="statusFilter" onchange="filterInquiries()">
                                <option value="">All Pending Status</option>
                                <option value="pending">Pending</option>
                                <option value="under-review">Under Review</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="priorityFilter">Priority</label>
                            <select id="priorityFilter" onchange="filterInquiries()">
                                <option value="">All Priorities</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="dateFilter">Date Range</label>
                            <select id="dateFilter" onchange="filterInquiries()">
                                <option value="">All Dates</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <button class="btn btn-primary" onclick="resetFilters()">Reset Filters</button>
                        </div>
                    </div>
                </div>

                <!-- Inquiries Table -->
                <div class="inquiries-card">
                    <div class="card-header">
                        <h3>Pending Inquiries</h3>
                        <div class="header-actions">
                            <span id="inquiryCount" class="count-badge">{{ $stats['total'] ?? 0 }} pending
                                inquiries</span>
                            <button class="btn btn-secondary" onclick="exportInquiries()">Export CSV</button>
                        </div>
                    </div>

                    <div class="table-container">
                        <table id="inquiriesTable" class="inquiries-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inquiries ?? [] as $index => $inquiry)
                                    <tr data-status="{{ strtolower(str_replace(' ', '-', $inquiry->status)) }}"
                                        data-priority="{{ $inquiry->category == 'urgent' ? 'high' : ($inquiry->category == 'normal' ? 'medium' : 'low') }}"
                                        data-date="{{ $inquiry->date_submitted->format('Y-m-d') }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ Str::limit($inquiry->title, 50) }}</td>
                                        <td>
                                            <span
                                                class="status-badge {{ strtolower(str_replace(' ', '-', $inquiry->status)) }}">
                                                {{ $inquiry->status }}
                                            </span>
                                        </td>
                                        <td>{{ $inquiry->date_submitted->format('M d, Y') }}</td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-action discard"
                                                    onclick="discardAsNonSerious('{{ $inquiry->inquiry_id }}')"
                                                    title="Discard as Non-Serious">
                                                    🗑️
                                                </button>
                                                <a href="{{ route('admin.assign.form', $inquiry->inquiry_id) }}"
                                                    class="btn-action assign" title="Assign to Agency">
                                                    📝
                                                </a>
                                                <button class="btn-action view"
                                                    onclick="viewInquiry('{{ $inquiry->inquiry_id }}')"
                                                    title="View Details">
                                                    👁️
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 40px; color: #7f8c8d;">
                                            <div style="font-size: 18px; margin-bottom: 10px;">✅</div>
                                            <div>No pending inquiries found</div>
                                            <div style="font-size: 14px; margin-top: 5px;">All inquiries have been
                                                processed!</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal for Inquiry Details -->
    <div id="inquiryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Inquiry Details</h3>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div class="detail-row">
                    <div class="detail-label">ID:</div>
                    <div class="detail-value" id="modalId">#INQ001</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Subject:</div>
                    <div class="detail-value" id="modalSubject">System Login Issues</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Submitter:</div>
                    <div class="detail-value" id="modalSubmitter">John Doe</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Email:</div>
                    <div class="detail-value" id="modalEmail">john.doe@email.com</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Status:</div>
                    <div class="detail-value" id="modalStatus">Pending</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Priority:</div>
                    <div class="detail-value" id="modalPriority">High</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Date:</div>
                    <div class="detail-value" id="modalDate">Dec 20, 2024</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Description:</div>
                    <div class="detail-value" id="modalDescription">
                        Unable to log into the system after password reset. Getting error message "Invalid credentials"
                        even with correct password.
                    </div>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="discardAsNonSerious()">Discard</button>
                <button class="btn btn-secondary" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>
    <script>
        // Dynamic inquiry data from database
        const inquiriesData = {
            @foreach($inquiries ?? [] as $inquiry)
                                'INQ{{ str_pad($inquiry->inquiry_id, 3, "0", STR_PAD_LEFT) }}': {
                    id: '#INQ{{ str_pad($inquiry->inquiry_id, 3, "0", STR_PAD_LEFT) }}',
                    subject: '{{ addslashes($inquiry->title) }}',
                    submitter: '{{ addslashes($inquiry->user->name ?? "Unknown User") }}',
                    email: '{{ addslashes($inquiry->user->email ?? "N/A") }}',
                    status: '{{ addslashes($inquiry->status) }}',
                    priority: '{{ $inquiry->category == "urgent" ? "High" : ($inquiry->category == "normal" ? "Medium" : "Low") }}',
                    date: '{{ $inquiry->date_submitted->format("M d, Y") }}',
                    description: '{{ addslashes(Str::limit($inquiry->content, 200)) }}'
                },
            @endforeach
        };

        // Filter functionality
        function filterInquiries() {
            const statusFilter = document.getElementById('statusFilter').value;
            const priorityFilter = document.getElementById('priorityFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;

            const tableRows = document.querySelectorAll('#inquiriesTable tbody tr');
            let visibleCount = 0;

            tableRows.forEach(row => {
                let showRow = true;

                // Status filter
                if (statusFilter && row.dataset.status !== statusFilter) {
                    showRow = false;
                }

                // Priority filter
                if (priorityFilter && row.dataset.priority !== priorityFilter) {
                    showRow = false;
                }

                // Date filter (simplified for demo)
                if (dateFilter) {
                    const rowDate = new Date(row.dataset.date);
                    const today = new Date();

                    switch (dateFilter) {
                        case 'today':
                            if (rowDate.toDateString() !== today.toDateString()) {
                                showRow = false;
                            }
                            break;
                        case 'week':
                            const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                            if (rowDate < weekAgo) {
                                showRow = false;
                            }
                            break;
                        case 'month':
                            const monthAgo = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);
                            if (rowDate < monthAgo) {
                                showRow = false;
                            }
                            break;
                    }
                }

                if (showRow) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update count badge
            document.getElementById('inquiryCount').textContent = `${visibleCount} pending inquiries`;
        }

        // Reset filters
        function resetFilters() {
            document.getElementById('statusFilter').value = '';
            document.getElementById('priorityFilter').value = '';
            document.getElementById('dateFilter').value = '';
            filterInquiries();
        } function discardAsNonSerious(inquiryId) {
            if (confirm(`Are you sure you want to discard inquiry #${inquiryId} as non-serious?`)) {
                // Send AJAX request to update status
                fetch(`/admin/inquiries/${inquiryId}/discard-non-serious`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({})
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Inquiry has been discarded as non-serious.');
                            location.reload(); // Refresh the page to show updated data
                        } else {
                            alert('Error: ' + (data.message || 'Failed to discard inquiry'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while processing the request.');
                    });
            }
        }

        function viewInquiry(inquiryId) {
            // Redirect to admin inquiry details page
            window.location.href = `/admin/inquiries/${inquiryId}/details`;
        }

        // No assignment modal functions needed - redirecting to separate page
        // Close modal when clicking outside
        window.onclick = function (event) {
            const modal = document.getElementById('inquiryModal');
            if (event.target === modal) {
                closeModal();
            }
        }        // Initialize page
        document.addEventListener('DOMContentLoaded', function () {
            // Set initial count from backend data
            const initialCount = {{ $stats['total'] ?? 0 }};
            document.getElementById('inquiryCount').textContent = `${initialCount} pending inquiries`;

            // Run filter to ensure count is correct
            filterInquiries();
        });
    </script>
@endsection