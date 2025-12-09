<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Inquiry Reports - Inquira</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
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

        /* Header */
        .header {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 2rem 0;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* Back Button */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #3498db;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }

        .back-btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .filter-section h2 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .filter-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-item label {
            font-weight: 600;
            color: #64748b;
            font-size: 0.875rem;
        }

        .filter-item select,
        .filter-item input {
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .filter-item select:focus,
        .filter-item input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .filter-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn {
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
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

        /* Statistics Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #64748b;
            font-weight: 500;
        }

        .stat-card.total .stat-icon { color: #3498db; }
        .stat-card.pending .stat-icon { color: #f39c12; }
        .stat-card.progress .stat-icon { color: #9b59b6; }
        .stat-card.resolved .stat-icon { color: #27ae60; }
        .stat-card.closed .stat-icon { color: #95a5a6; }

        /* Charts Section */
        .charts-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .chart-card h3 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        /* Export Section */
        .export-section {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .export-section h2 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .export-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-export {
            padding: 1rem 2rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-pdf {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .btn-excel {
            background: linear-gradient(135deg, #27ae60, #229954);
            color: white;
        }

        .btn-export:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Data Table */
        .data-table-section {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .data-table th,
        .data-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .data-table th {
            background: #f8fafc;
            font-weight: 600;
            color: #2c3e50;
        }

        .data-table tbody tr:hover {
            background: #f8fafc;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending { background: #fef3c7; color: #92400e; }
        .status-in-progress { background: #e0e7ff; color: #3730a3; }
        .status-resolved { background: #d1fae5; color: #065f46; }
        .status-closed { background: #f3f4f6; color: #374151; }

        /* Responsive */
        @media (max-width: 768px) {
            .charts-section {
                grid-template-columns: 1fr;
            }
            
            .filter-grid {
                grid-template-columns: 1fr;
            }
            
            .export-buttons {
                flex-direction: column;
                align-items: center;
            }
        }

        /* Loading Animation */
        .loading {
            display: none;
            text-align: center;
            padding: 2rem;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="container">
            <h1>
                <i class="fas fa-chart-bar"></i>
                Inquiry Reports & Analytics
            </h1>
            <p>Generate comprehensive reports on inquiry statistics and trends</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Back Button -->
        <a href="{{ route('module4.mcmc.dashboard') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Admin Dashboard
        </a>

        <!-- Filter Section -->
        <div class="filter-section">
            <h2>
                <i class="fas fa-filter"></i>
                Report Filters
            </h2>
            <form id="reportForm">
                <div class="filter-grid">                    <div class="filter-item">
                        <label for="date_from">From Date</label>
                        <input type="date" id="date_from" name="date_from" value="{{ $dateFrom }}">
                    </div>
                    <div class="filter-item">
                        <label for="date_to">To Date</label>
                        <input type="date" id="date_to" name="date_to" value="{{ $dateTo }}">
                    </div>
                    <div class="filter-item">
                        <label for="status_filter">Status</label>
                        <select id="status_filter" name="status_filter">
                            <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>All Statuses</option>
                            @if(isset($statuses))
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ $statusFilter == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="filter-item">
                        <label for="category_filter">Category</label>
                        <select id="category_filter" name="category_filter">
                            <option value="all" {{ $categoryFilter == 'all' ? 'selected' : '' }}>All Categories</option>
                            @if(isset($categories))
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ $categoryFilter == $category ? 'selected' : '' }}>{{ ucfirst($category) }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="filter-actions">
                    <button type="button" class="btn btn-primary" onclick="generateReport()">
                        <i class="fas fa-chart-line"></i>
                        Generate Report
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="resetFilters()">
                        <i class="fas fa-undo"></i>
                        Reset Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Loading Animation -->
        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>Generating report...</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid" id="statsGrid">
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-number" id="totalInquiries">{{ $stats['total'] ?? 0 }}</div>
                <div class="stat-label">Total Inquiries</div>
            </div>
            <div class="stat-card pending">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number" id="pendingInquiries">{{ $stats['pending'] ?? 0 }}</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card progress">
                <div class="stat-icon">
                    <i class="fas fa-spinner"></i>
                </div>
                <div class="stat-number" id="progressInquiries">{{ $stats['in_progress'] ?? 0 }}</div>
                <div class="stat-label">In Progress</div>
            </div>
            <div class="stat-card resolved">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number" id="resolvedInquiries">{{ $stats['resolved'] ?? 0 }}</div>
                <div class="stat-label">Resolved</div>
            </div>
            <div class="stat-card closed">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-number" id="closedInquiries">{{ $stats['closed'] ?? 0 }}</div>
                <div class="stat-label">Closed</div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <div class="chart-card">
                <h3>
                    <i class="fas fa-chart-pie"></i>
                    Status Distribution
                </h3>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
            <div class="chart-card">
                <h3>
                    <i class="fas fa-chart-line"></i>
                    Monthly Trends
                </h3>
                <div class="chart-container">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="data-table-section">
            <h2>
                <i class="fas fa-table"></i>
                Inquiry Details
            </h2>
            <div style="overflow-x: auto;">
                <table class="data-table" id="inquiriesTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Category</th>
                            <th>Submitted Date</th>
                            <th>Days Open</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @if(isset($inquiries) && $inquiries->count() > 0)
                            @foreach($inquiries as $index => $inquiry)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $inquiry->title }}</td>
                                <td>
                                    <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $inquiry->status)) }}">
                                        {{ $inquiry->status }}
                                    </span>
                                </td>                                <td>{{ ucfirst($inquiry->category ?? 'N/A') }}</td>
                                <td>{{ $inquiry->date_submitted ? \Carbon\Carbon::parse($inquiry->date_submitted)->format('M d, Y') : 'N/A' }}</td>
                                <td>{{ $inquiry->date_submitted ? \Carbon\Carbon::parse($inquiry->date_submitted)->diffInDays(now()) : 0 }} days</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 2rem; color: #64748b;">
                                    No inquiries found for the selected criteria
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Export Section -->
        <div class="export-section">
            <h2>
                <i class="fas fa-download"></i>
                Export Reports
            </h2>
            <p style="margin-bottom: 1.5rem; color: #64748b;">
                Download reports in PDF or Excel format for further analysis
            </p>
            <div class="export-buttons">
                <button class="btn-export btn-pdf" onclick="exportToPDF()">
                    <i class="fas fa-file-pdf"></i>
                    Export as PDF
                </button>
                <button class="btn-export btn-excel" onclick="exportToExcel()">
                    <i class="fas fa-file-excel"></i>
                    Export as Excel
                </button>
            </div>
        </div>
    </div>

    <script>
        // Chart variables
        let statusChart, trendChart;
        
        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
        });

        // Initialize Charts
        function initializeCharts() {
            // Status Distribution Chart (Pie Chart)
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'In Progress', 'Resolved', 'Closed'],
                    datasets: [{
                        data: [
                            {{ $stats['pending'] ?? 0 }},
                            {{ $stats['in_progress'] ?? 0 }},
                            {{ $stats['resolved'] ?? 0 }},
                            {{ $stats['closed'] ?? 0 }}
                        ],
                        backgroundColor: ['#f39c12', '#9b59b6', '#27ae60', '#95a5a6'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });            // Monthly Trends Chart (Line Chart)
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            trendChart = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_keys($monthlyTrends ?? [])) !!},
                    datasets: [{
                        label: 'Inquiries',
                        data: {!! json_encode(array_values($monthlyTrends ?? [])) !!},
                        borderColor: '#3498db',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }        // Generate Report
        function generateReport() {
            const loading = document.getElementById('loading');
            loading.style.display = 'block';

            const formData = new FormData(document.getElementById('reportForm'));
            const params = new URLSearchParams(formData).toString();

            // Redirect to update the page with new filters
            window.location.href = '{{ route('admin.inquiry.reports') }}?' + params;
        }

        // Reset Filters
        function resetFilters() {
            window.location.href = '{{ route('admin.inquiry.reports') }}';
        }

        // Export to PDF
        function exportToPDF() {
            const dateFrom = document.getElementById('date_from').value;
            const dateTo = document.getElementById('date_to').value;
            const statusFilter = document.getElementById('status_filter').value;
            const categoryFilter = document.getElementById('category_filter').value;
            
            const params = new URLSearchParams({
                date_from: dateFrom,
                date_to: dateTo,
                status_filter: statusFilter,
                category_filter: categoryFilter
            });
            
            window.open('{{ route('admin.inquiry.reports.pdf') }}?' + params.toString(), '_blank');
        }

        // Export to Excel
        function exportToExcel() {
            const dateFrom = document.getElementById('date_from').value;
            const dateTo = document.getElementById('date_to').value;
            const statusFilter = document.getElementById('status_filter').value;
            const categoryFilter = document.getElementById('category_filter').value;
            
            const params = new URLSearchParams({
                date_from: dateFrom,
                date_to: dateTo,
                status_filter: statusFilter,
                category_filter: categoryFilter
            });
            
            window.location.href = '{{ route('admin.inquiry.reports.excel') }}?' + params.toString();
        }
    </script>
</body>
</html>