@extends('components.side_headr')

@section('title', 'Public User Dashboard - Module 4')

@section('additional_css')
<style>
    /* Dashboard Layout */
    .dashboard-container {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Stats Cards Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .stat-card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .investigation { background: #dbeafe; color: #1e40af; }
    .verified { background: #d1fae5; color: #065f46; }
    .fake { background: #fee2e2; color: #991b1b; }
    .rejected { background: #f3f4f6; color: #374151; }
    .pending { background: #fef3c7; color: #d97706; }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #111827;
        line-height: 1;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    /* Charts Section */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .chart-card {
        background: white;
        padding: 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .chart-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .chart-title i {
        font-size: 1rem;
        color: #6b7280;
    }

    /* Filter Section */
    .filter-section {
        margin-bottom: 1.5rem;
        background: white;
        padding: 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .filter-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-item label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: #374151;
    }

    .filter-item label i {
        color: #2563eb;
    }

    .filter-item input,
    .filter-item select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        color: #374151;
        background-color: #fff;
        transition: all 0.2s;
    }

    .filter-item input:focus,
    .filter-item select:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 1rem;
        align-items: flex-end;
    }

    .filter-apply {
        flex: 1;
        padding: 0.75rem 1.5rem;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .filter-apply:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }

    .filter-clear {
        padding: 0.75rem 1.5rem;
        background: #f3f4f6;
        color: #4b5563;
        border: none;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-clear:hover {
        background: #e5e7eb;
    }

    /* Table Section */
    .table-section {
        background: white;
        padding: 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        overflow-x: auto;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .table-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
    }

    .inquiries-table {
        width: 100%;
        border-collapse: collapse;
    }

    .inquiries-table th,
    .inquiries-table td {
        padding: 0.75rem 1rem;
        text-align: left;
    }

    .inquiries-table th {
        background: #f9fafb;
        font-weight: 600;
        color: #374151;
        white-space: nowrap;
    }

    .inquiries-table tr {
        border-bottom: 1px solid #e5e7eb;
    }

    .inquiries-table tr:hover {
        background: #f9fafb;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
        white-space: nowrap;
    }

    .status-investigation { background: #dbeafe; color: #1e40af; }
    .status-verified { background: #d1fae5; color: #065f46; }
    .status-fake { background: #fee2e2; color: #991b1b; }
    .status-rejected { background: #f3f4f6; color: #374151; }
    .status-pending { background: #fef3c7; color: #d97706; }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #2563eb;
        color: white;
        text-decoration: none;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .action-btn:hover {
        background: #1d4ed8;
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }

        .charts-grid {
            grid-template-columns: 1fr;
        }

        .stat-number {
            font-size: 1.5rem;
        }
    }

    /* Pagination Styles */
    .pagination {
        display: flex;
        justify-content: center;
        margin: 24px 0;
        list-style: none;
        padding: 0;
    }
    .pagination li {
        margin: 0 4px;
    }
    .pagination li a,
    .pagination li span {
        display: block;
        padding: 8px 14px;
        color: #2563eb;
        background: #f3f6fa;
        border-radius: 6px;
        text-decoration: none;
        font-size: 1rem;
        font-weight: 500;
        transition: background 0.2s, color 0.2s;
    }
    .pagination li.active span,
    .pagination li a:hover {
        background: #2563eb;
        color: #fff;
    }
    .pagination svg,
    nav[role="navigation"] svg {
        width: 1em !important;
        height: 1em !important;
        min-width: 1em !important;
        min-height: 1em !important;
        max-width: 1.5em !important;
        max-height: 1.5em !important;
        vertical-align: middle;
    }
</style>
@endsection

@section('content')
<!-- Include Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="dashboard-container">
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon pending">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-number">{{ $pending ?? 0 }}</div>
            <div class="stat-label">Pending</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon investigation">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            <div class="stat-number">{{ $underInvestigation ?? 0 }}</div>
            <div class="stat-label">Under Investigation</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon verified">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-number">{{ $verifiedTrue ?? 0 }}</div>
            <div class="stat-label">Verified as True</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon fake">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
            <div class="stat-number">{{ $identifiedFake ?? 0 }}</div>
            <div class="stat-label">Identified as Fake</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon rejected">
                    <i class="fas fa-ban"></i>
                </div>
            </div>
            <div class="stat-number">{{ $rejected ?? 0 }}</div>
            <div class="stat-label">Rejected</div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="charts-grid">
        <div class="chart-card">
            <h3 class="chart-title">
                <i class="fas fa-chart-pie"></i>
                Status Distribution
            </h3>
            <canvas id="statusChart"></canvas>
        </div>
        
        <div class="chart-card">
            <h3 class="chart-title">
                <i class="fas fa-chart-line"></i>
                Monthly Trends
            </h3>
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-section">
        <div class="table-header">
            <h3 class="table-title">Recent Inquiries</h3>
        </div>

        <!-- Filter Section -->
        <div class="filter-section" style="margin-bottom: 20px;">
            <form method="GET" action="{{ route('module4.dashboard') }}" class="filter-form">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" 
                               name="search" 
                               id="search" 
                               placeholder="Search by title or content" 
                               value="{{ request('search') }}"
                               class="w-full p-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" class="w-full p-2 border border-gray-300 rounded-md">
                            <option value="">All Statuses</option>
                            <option value="Under Investigation" {{ request('status') == 'Under Investigation' ? 'selected' : '' }}>Under Investigation</option>
                            <option value="Verified as True" {{ request('status') == 'Verified as True' ? 'selected' : '' }}>Verified as True</option>
                            <option value="Identified as Fake" {{ request('status') == 'Identified as Fake' ? 'selected' : '' }}>Identified as Fake</option>
                            <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Time Period</label>
                        <select name="date" id="date" class="w-full p-2 border border-gray-300 rounded-md">
                            <option value="">All Time</option>
                            <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="year" {{ request('date') == 'year' ? 'selected' : '' }}>This Year</option>
                        </select>
                    </div>
                    <div style="display: flex; align-items: flex-end;">
                        <button type="submit" 
                                class="w-full p-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-filter mr-2"></i>Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <table class="inquiries-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Submitted Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inquiries ?? [] as $inquiry)
                <tr>
                    <td>{{ $inquiry->title }}</td>
                    <td>{{ $inquiry->category }}</td>
                    <td>{{ $inquiry->created_at->format('M d, Y') }}</td>
                    <td>
                        <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $inquiry->status)) }}">
                            <i class="fas fa-circle fa-sm"></i>
                            {{ $inquiry->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <div class="flex flex-col items-center justify-center text-gray-500">
                            <i class="fas fa-inbox fa-3x mb-2"></i>
                            <p>No inquiries found</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if(isset($inquiries) && $inquiries->hasPages())
        <div class="mt-4">
            {{ $inquiries->links() }}
        </div>
        @endif
    </div>
</div>

<script>
// Status Distribution Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Under Investigation', 'Verified as True', 'Identified as Fake', 'Rejected'],
        datasets: [{
            data: [
                {{ $underInvestigation ?? 0 }},
                {{ $verifiedTrue ?? 0 }},
                {{ $identifiedFake ?? 0 }},
                {{ $rejected ?? 0 }}
            ],
            backgroundColor: [
                '#dbeafe',
                '#d1fae5', 
                '#fee2e2',
                '#f3f4f6'
            ],
            borderColor: [
                '#1e40af',
                '#065f46',
                '#991b1b',
                '#374151'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20
                }
            }
        },
        cutout: '60%'
    }
});

// Monthly Trends Chart
const trendCtx = document.getElementById('trendChart').getContext('2d');
new Chart(trendCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($monthlyLabels ?? []) !!},
        datasets: [{
            label: 'Total Inquiries',
            data: {!! json_encode($monthlyData ?? []) !!},
            borderColor: '#2563eb',
            backgroundColor: '#dbeafe',
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        }
    }
});
</script>
@endsection