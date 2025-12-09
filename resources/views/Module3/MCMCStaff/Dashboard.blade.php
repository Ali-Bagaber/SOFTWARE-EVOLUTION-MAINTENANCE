@extends('layouts.app')

@section('title', 'MCMC Staff Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h2><i class="fas fa-tachometer-alt me-2"></i>MCMC Staff Dashboard</h2>
                    <p class="mb-0">Manage inquiry assignments and track performance metrics</p>
                </div>
            </div>
        </div>
    </div>    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Assignments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Assignments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Completion Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completion_rate'] ?? 0 }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                In Progress</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['in_progress'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Pending Inquiries -->
    <div class="row">
        <!-- Assignment Trends Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Assignment Trends (Last 6 Months)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="assignmentTrendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agency Distribution -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Top Agencies by Assignments</h6>
                </div>
                <div class="card-body">
                    @if(isset($agencyStats) && $agencyStats->count() > 0)
                        @foreach($agencyStats as $stat)
                        <div class="mb-3">
                            <div class="small">{{ $stat->agency->agency_name ?? 'Unknown Agency' }}</div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ ($stat->count / $agencyStats->first()->count) * 100 }}%">
                                    {{ $stat->count }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">No assignment data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions and Recent Assignments -->
    <div class="row">
        <!-- Quick Actions -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('mcmc.review.assign') }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-plus me-2"></i>Assign New Inquiries
                    </a>
                    <a href="{{ route('mcmc.reports') }}" class="btn btn-info btn-block mb-2">
                        <i class="fas fa-chart-bar me-2"></i>View Reports
                    </a>
                    <a href="{{ route('mcmc.history') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-history me-2"></i>Assignment History
                    </a>
                    @if(isset($pendingCount) && $pendingCount > 0)
                    <div class="alert alert-warning mt-3">
                        <strong>{{ $pendingCount }}</strong> inquiries awaiting assignment
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Assignments -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Assignments</h6>
                </div>
                <div class="card-body">
                    @if(isset($recentAssignments) && $recentAssignments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Inquiry ID</th>
                                        <th>Agency</th>
                                        <th>Priority</th>
                                        <th>Assigned By</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentAssignments as $assignment)
                                    <tr>
                                        <td>{{ $assignment->inquiry->inquiry_id ?? 'N/A' }}</td>
                                        <td>{{ $assignment->agency->agency_name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $assignment->priority_color }}">
                                                {{ ucfirst($assignment->priority_level) }}
                                            </span>
                                        </td>
                                        <td>{{ $assignment->assigned_by_name ?? 'N/A' }}</td>
                                        <td>{{ $assignment->formatted_date }}</td>
                                        <td>
                                            <span class="badge badge-{{ $assignment->status_color }}">
                                                {{ ucfirst($assignment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No recent assignments found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Assignment Trends Chart
const ctx = document.getElementById('assignmentTrendsChart').getContext('2d');
const monthlyTrends = @json($monthlyTrends ?? []);

new Chart(ctx, {
    type: 'line',
    data: {
        labels: monthlyTrends.map(item => item.month),
        datasets: [{
            label: 'Assignments',
            data: monthlyTrends.map(item => item.count),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
@endsection
