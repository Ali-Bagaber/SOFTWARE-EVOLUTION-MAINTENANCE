{{-- @extends('components.side_headr')

@section('title', 'Agency Information - Inquira')

@section('additional_css')
<style>
    /* Enhanced styles for agency information */
    .main-content {
        padding: 32px;
        background: #f8fafc;
        min-height: 100vh;
    }

    .agency-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 32px;
        font-size: 14px;
        color: #64748b;
    }

    .breadcrumb a {
        color: #3b82f6;
        text-decoration: none;
        transition: color 0.2s;
    }

    .breadcrumb a:hover {
        color: #1d4ed8;
    }

    .breadcrumb i {
        font-size: 12px;
    }

    .agency-header {
        background: white;
        padding: 32px;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin-bottom: 32px;
        border: 1px solid #e2e8f0;
    }

    .agency-title {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }

    .agency-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #60a5fa, #3b82f6);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
    }

    .agency-title h1 {
        font-size: 36px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .agency-subtitle {
        color: #64748b;
        font-size: 18px;
        margin-bottom: 24px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #dcfce7;
        color: #166534;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }

    .status-badge.available {
        background: #dcfce7;
        color: #166534;
    }

    .status-badge.unavailable {
        background: #fee2e2;
        color: #dc2626;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .info-card {
        background: white;
        padding: 24px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .info-card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .info-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .info-card-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3b82f6;
        font-size: 18px;
    }

    .info-card h3 {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .info-card-content {
        color: #64748b;
        font-size: 16px;
        line-height: 1.6;
    }

    .info-card-content strong {
        color: #1e293b;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 16px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
    }

    .btn-secondary {
        background: white;
        color: #3b82f6;
        border: 2px solid #3b82f6;
    }

    .btn-secondary:hover {
        background: #3b82f6;
        color: white;
        transform: translateY(-2px);
    }

    .no-agencies {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }

    .no-agencies-icon {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .main-content {
            padding: 24px 16px;
        }

        .agency-header {
            padding: 24px;
        }

        .agency-title {
            flex-direction: column;
            text-align: center;
        }

        .agency-title h1 {
            font-size: 28px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <div class="agency-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('publicuser.dashboard') }}">Home</a>
            <i class="fas fa-chevron-right"></i>
            <span>Agency Information</span>
        </div>

        @if($agencies->isNotEmpty())
        @foreach($agencies as $agency)
        <!-- Agency Header -->
        <div class="agency-header">
            <div class="agency-title">
                <div class="agency-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div>
                    <h1>{{ $agency->agency_name }}</h1>
                    <p class="agency-subtitle">{{ $agency->description ?? 'Government Agency' }}</p>
                    <div class="status-badge available">
                        <i class="fas fa-check-circle"></i>
                        Available
                    </div>
                </div>
            </div>
        </div>

        <!-- Agency Information Grid -->
        <div class="info-grid">
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Contact Information</h3>
                </div>
                <div class="info-card-content">
                    <strong>Email:</strong> {{ $agency->agency_email ?? 'Not provided' }}<br>
                    @if($agency->agency_phone)
                    <strong>Phone:</strong> {{ $agency->agency_phone }}<br>
                    @endif
                    <strong>Contact for:</strong> General inquiries and information
                </div>
            </div>

            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3>About Agency</h3>
                </div>
                <div class="info-card-content">
                    {{ $agency->description ?? 'This government agency handles various public services and
                    administrative functions. Please contact them directly for specific information about their services
                    and procedures.' }}
                </div>
            </div>

            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Operating Hours</h3>
                </div>
                <div class="info-card-content">
                    <strong>Monday - Friday:</strong><br>
                    8:00 AM - 5:00 PM<br>
                    <strong>Weekend:</strong> Closed<br>
                    <strong>Public Holidays:</strong> Closed
                </div>
            </div>

            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3>Services</h3>
                </div>
                <div class="info-card-content">
                    <strong>Available Services:</strong><br>
                    • Public Information Requests<br>
                    • Administrative Services<br>
                    • Policy Inquiries<br>
                    • General Public Services
                </div>
            </div>
        </div>

        @if(!$loop->last)
        <hr style="margin: 40px 0; border: none; border-top: 1px solid #e2e8f0;">
        @endif
        @endforeach
        @else
        <div class="no-agencies">
            <div class="no-agencies-icon">🏢</div>
            <h3>No Agency Information Available</h3>
            <p>Agency information is currently being updated. Please check back later.</p>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('publicuser.view.assigned') }}" class="btn btn-primary">
                <i class="fas fa-building"></i>
                View My Assigned Agencies
            </a>
            <a href="{{ route('inquiry.create') }}" class="btn btn-secondary">
                <i class="fas fa-edit"></i>
                Submit New Inquiry
            </a>
            <a href="{{ route('publicuser.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection --}}