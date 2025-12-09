@extends('components.agency_side_headr')

@section('title', 'Agency Dashboard - Inquira')
@section('page_title', 'Agency Dashboard')

@section('content')
    @if(session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i>
                <div>
                <strong>Success!</strong> {{ session('success') }}
            </div>
        </div>
    @endif
                <!-- Welcome Banner -->
                <div class="welcome-banner">
                    <h1>
                        <i class="fas fa-hand-sparkles"></i>
                        Hi {{ $agency->name }}, Welcome to Inquira Control Center
                    </h1>
                    <p>Manage your inquiries efficiently with complete administrative control. Track performance, handle
                        requests, and maintain excellent service quality.</p>
                    <a href="{{ route('agency.profile') }}" class="configure-btn">
                        <i class="fas fa-cog"></i>
                        Configure Agency Settings
                    </a>
                </div>
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card emerald">
                        <div class="stat-header">
                            <div class="stat-icon emerald">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                        </div>
                        <div class="stat-number">{{ $stats['assigned'] }}</div>
                        <div class="stat-label">ASSIGNED INQUIRIES</div>
                    </div>
                    <div class="stat-card orange">
                        <div class="stat-header">
                            <div class="stat-icon orange">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="stat-number">{{ $stats['pending'] }}</div>
                        <div class="stat-label">PENDING REVIEWS</div>
                    </div>
                    <div class="stat-card blue">
                        <div class="stat-header">
                            <div class="stat-icon blue">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="stat-number">{{ $stats['completed'] }}</div>
                        <div class="stat-label">RESOLVED</div>
                    </div>
                    <div class="stat-card red">
                        <div class="stat-header">
                            <div class="stat-icon red">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                        <div class="stat-number">{{ $stats['overdue'] }}</div>
                        <div class="stat-label">OVERDUE</div>
                    </div>
                </div>
                <!-- Quick Actions -->
                <div class="quick-actions">
                    <h2>
                        <i class="fas fa-bolt"></i>
                        Agency Quick Actions
                    </h2>
                    <div class="actions-grid">
                        <a href="{{ route('agency.inquiries') }}" class="action-card">
                            <div class="action-icon emerald">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <h3>Manage Inquiries</h3>
                            <p>Review and process assigned inquiries from citizens</p>
                        </a>
                        <a href="{{ route('agency.display.inquiry') }}" class="action-card">
                            <div class="action-icon teal">
                                <i class="fas fa-search-plus"></i>
                            </div>
                            <h3>Display Inquiry</h3>
                            <p>View detailed inquiry information and status</p>
                        </a>
                        <a href="{{ route('agency.profile') }}" class="action-card">
                            <div class="action-icon blue">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <h3>Update Profile</h3>
                            <p>Manage agency profile and contact information</p>
                        </a>
                        <a href="#" class="action-card">
                            <div class="action-icon orange">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3>View Reports</h3>
                            <p>Generate performance reports and analytics</p>
                        </a>
                        <a href="{{ route('agency.view') }}" class="action-card">
                            <div class="action-icon purple">
                                <i class="fas fa-building"></i>
                            </div>
                            <h3>View Agency</h3>
                            <p>View complete agency information and details</p>
                        </a>
                    </div>
                </div>
@endsection