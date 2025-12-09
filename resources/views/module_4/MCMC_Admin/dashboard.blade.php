@extends('components.admin_side_headr')

@section('title', 'MCMC Admin Dashboard')

@section('page_title', 'Dashboard Overview')

@section('additional_styles')
<style>
    .container {
        padding: 20px;
        max-width: 1400px;
    }
    
    /* Status Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        text-align: center;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .stat-card h5 {
        color: #2c3e50;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .stat-card .display-6 {
        font-size: 42px;
        font-weight: 700;
        margin: 0;
    }
    
    .stat-card .status-icon {
        font-size: 24px;
        margin-bottom: 15px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        color: white;
    }
    
    .investigation .status-icon {
        background: #3498db;
    }
    
    .verified .status-icon {
        background: #2ecc71;
    }
    
    .fake .status-icon {
        background: #e74c3c;
    }
    
    .rejected .status-icon {
        background: #95a5a6;
    }
    
    .pending .status-icon {
        background: #f39c12;
    }
    
    /* Charts Section */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .chart-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 20px;
        height: 350px;
    }
    
    .chart-card h5 {
        color: #2c3e50;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }
    
    .chart-card h5 i {
        margin-right: 8px;
    }
    
    .chart-container {
        height: 280px;
        position: relative;
    }
    
    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 20px;
        margin-bottom: 20px;

    }
    
    .filter-section .row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .filter-section label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
    }
    
    .filter-section .form-select,
    .filter-section .form-control {
        border-radius: 8px;
        border: 1px solid #e9ecef;
        padding: 10px 15px;
    }
    
    .filter-section .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
    }
    
    /* Recent Inquiries Table */
    .inquiries-table {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    
    .inquiries-table .card-header {
        background: #f8f9fa;
        padding: 18px 20px;
        border-bottom: 1px solid #e9ecef;
        font-weight: 600;
        font-size: 18px;
        color: #2c3e50;
        display: flex;
        align-items: center;
    }
    
    .inquiries-table .card-header i {
        margin-right: 10px;
    }
    
    .table {
        margin-bottom: 0;
        width: 100%;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        color: #2c3e50;
        padding: 15px 20px;
        background: #f8f9fa;
    }
    
    .table td {
        padding: 15px 20px;
        vertical-align: middle;
    }
    
    .table tr {
        border-bottom: 1px solid #f1f1f1;
    }
    
    .table tr:last-child {
        border-bottom: none;
    }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    
    .status-pending {
        background: #fff8e1;
        color: #f39c12;
    }
    
    .status-investigation {
        background: #e3f2fd;
        color: #3498db;
    }
    
    .status-verified {
        background: #e8f5e9;
        color: #2ecc71;
    }
    
    .status-fake {
        background: #ffebee;
        color: #e74c3c;
    }
    
    .status-rejected {
        background: #f5f5f5;
        color: #95a5a6;
    }
    
    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 400px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<!-- Add Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
@endsection

@section('content')
<div class="container">
    <!-- Status Cards -->
    <div class="stats-grid">
        <div class="stat-card pending">
            <div class="status-icon">
                <i class="fas fa-clock"></i>
            </div>
            <h5>Pending</h5>
            <p class="display-6 text-warning">{{ $statusCounts['Pending'] ?? 0 }}</p>
        </div>
        
        <div class="stat-card investigation">
            <div class="status-icon">
                <i class="fas fa-search"></i>
            </div>
            <h5>Under Investigation</h5>
            <p class="display-6 text-primary">{{ $statusCounts['Under Investigation'] ?? 0 }}</p>
        </div>
        
        <div class="stat-card verified">
            <div class="status-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h5>Verified as True</h5>
            <p class="display-6 text-success">{{ $statusCounts['Verified as True'] ?? 0 }}</p>
        </div>
        
        <div class="stat-card fake">
            <div class="status-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <h5>Identified as Fake</h5>
            <p class="display-6 text-danger">{{ $statusCounts['Identified as Fake'] ?? 0 }}</p>
        </div>
        
        <div class="stat-card rejected">
            <div class="status-icon">
                <i class="fas fa-ban"></i>
            </div>
            <h5>Rejected</h5>
            <p class="display-6 text-secondary">{{ $statusCounts['Rejected'] ?? 0 }}</p>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="charts-grid">
        <div class="chart-card">
            <h5><i class="fas fa-chart-pie"></i> Status Distribution</h5>
            <div class="chart-container">
                <canvas id="statusDistributionChart"></canvas>
            </div>
        </div>
        
        <div class="chart-card">
            <h5><i class="fas fa-chart-line"></i> Monthly Trends</h5>
            <div class="chart-container">
                <canvas id="monthlyTrendsChart"></canvas>
            </div>
        </div>
    </div>
    
   
    
    <!-- Recent Inquiries Table -->
    <div class="inquiries-table">
        <div class="card-header">
            <i class="fas fa-list"></i> Recent Inquiries
        </div>


 <!-- Filter Section -->
 <div class="filter-section">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="status" class="form-label">Filter by Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="time-period" class="form-label">Time Period</label>
                <select id="time-period" class="form-select">
                    <option value="all-time">All Time</option>
                    <option value="this-month">This Month</option>
                    <option value="last-month">Last Month</option>
                    <option value="last-90-days">Last 90 Days</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </div>
        </form>
    </div>


        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Submitted Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentInquiries as $inquiry)
                    <tr>
                        <td>{{ $inquiry->title }}</td>
                        <td>{{ $inquiry->category ?? 'Uncategorized' }}</td>
                        <td>{{ $inquiry->date_submitted instanceof \DateTime ? $inquiry->date_submitted->format('M d, Y') : $inquiry->date_submitted }}</td>
                        <td>
                            @php
                                $statusClass = 'status-pending';
                                if($inquiry->status == 'Under Investigation') $statusClass = 'status-investigation';
                                elseif($inquiry->status == 'Verified as True') $statusClass = 'status-verified';
                                elseif($inquiry->status == 'Identified as Fake') $statusClass = 'status-fake';
                                elseif($inquiry->status == 'Rejected') $statusClass = 'status-rejected';
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $inquiry->status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">No inquiries found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status Distribution Chart
    const statusDistributionCtx = document.getElementById('statusDistributionChart').getContext('2d');
    
    // Status chart data
    const statusLabels = [
        'Under Investigation', 
        'Verified as True', 
        'Identified as Fake', 
        'Rejected'
    ];
    
    const statusData = [
        {{ $statusCounts['Under Investigation'] ?? 0 }},
        {{ $statusCounts['Verified as True'] ?? 0 }},
        {{ $statusCounts['Identified as Fake'] ?? 0 }},
        {{ $statusCounts['Rejected'] ?? 0 }}
    ];
    
    new Chart(statusDistributionCtx, {
        type: 'pie',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusData,
                backgroundColor: [
                    '#3498db', // Under Investigation - Blue
                    '#2ecc71', // Verified as True - Green
                    '#e74c3c', // Identified as Fake - Red
                    '#95a5a6'  // Rejected - Gray
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        boxWidth: 15,
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });
    
    // Monthly Trends Chart
    const monthlyTrendsCtx = document.getElementById('monthlyTrendsChart').getContext('2d');
    
    // Use real data from the backend
    const months = [
        @foreach($monthlyLabels ?? [] as $label)
            "{{ $label }}",
        @endforeach
    ];
    
    const inquiryData = [
        @foreach($monthlyTrends ?? [] as $count)
            {{ $count }},
        @endforeach
    ];
    
    new Chart(monthlyTrendsCtx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Total Inquiries',
                data: inquiryData,
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#3498db',
                pointBorderWidth: 2,
                pointRadius: 5,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    },
                    ticks: {
                        precision: 0
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 15,
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection 