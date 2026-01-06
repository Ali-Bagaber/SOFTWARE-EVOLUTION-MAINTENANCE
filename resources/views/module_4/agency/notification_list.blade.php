@extends('components.agency_side_headr')

@section('title', 'Agency Notifications')

@section('page_title', 'Agency Notifications')

@section('additional_styles')

<style>
    body {
        background: #f6f8fa;
    }

    .container {
        max-width: 900px;
        margin: 0 auto;
    }

    .card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        background: #fff;
        margin-top: 32px;
    }

    .card-header {
        border-radius: 16px 16px 0 0;
        border-bottom: 1px solid #e3e6ea;
        background: #fff;
        color: #222;
        padding: 24px 28px 16px 28px;
    }

    .card-header h5 {
        font-weight: 600;
        letter-spacing: 0.5px;
        font-size: 1.25rem;
    }

    .notification-list {
        margin: 0;
        padding: 0 18px 18px 18px;
    }

    .notification-item {
        border-radius: 10px;
        margin: 18px 0;
        padding: 18px 22px;
        background: #f8fafc;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
        transition: box-shadow 0.2s, background 0.2s;
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .notification-item:hover {
        background: #f0f6ff;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.08);
    }

    .notification-item.unread {
        background: #e8f0fe;
        border-left: 4px solid #667eea;
    }

    .notification-item.unread:hover {
        background: #dbeafe;
    }

    .badge.bg-primary {
        background: #667eea;
        color: #fff;
        font-size: 0.85rem;
        padding: 0.4em 0.8em;
        border-radius: 8px;
        font-weight: 500;
    }

    .btn.btn-light {
        border-radius: 8px;
        background: #f3f6fa;
        color: #667eea;
        border: 1px solid #e3e6ea;
        font-weight: 500;
        transition: background 0.2s, color 0.2s;
    }

    .btn.btn-light:hover {
        background: #667eea;
        color: #fff;
    }

    .text-muted {
        color: #6b7280 !important;
    }

    .text-center .fa-bell-slash {
        color: #cbd5e1;
    }

    .btn.btn-link.text-decoration-none {
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        color: #fff !important;
        border: none;
        border-radius: 8px;
        padding: 0.55rem 1.2rem;
        font-size: 0.9rem;
        transition: transform 0.15s, box-shadow 0.15s;
    }

    .btn.btn-link.text-decoration-none:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .small .far.fa-clock {
        color: #9ca3af;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container {
            padding: 8px;
        }

        .card-header {
            padding: 16px;
        }

        .notification-item {
            padding: 12px 8px;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <div>
                <h5 class="mb-0">Your Notifications</h5>
                <p class="text-muted small mb-0">Inquiry updates and alerts</p>
            </div>
        </div>
        <div class="card-body p-0">
            @if($notifications->count() > 0)
            <div class="notification-list">
                @foreach($notifications as $notification)
                <div class="notification-item p-4 border-bottom {{ !$notification->is_read ? 'unread' : '' }}">
                    <div>
                        <div class="mb-2">
                            <span class="badge bg-primary mb-2">
                                <i class="fas fa-bell me-1"></i> Agency Alert
                            </span>
                        </div>
                        <p class="mb-2">{{ $notification->message }}</p>
                        <div class="text-muted small">
                            <i class="far fa-clock me-1"></i>
                            {{ $notification->date_sent->diffForHumans() }}
                            @if($notification->inquiry)
                            · <a href="{{ route('agency.inquiry.detail', ['id' => $notification->inquiry_id]) }}" class="ms-2">
                                View Inquiry <i class="fas fa-external-link-alt ms-1"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4 px-4 pb-4">
                {{ $notifications->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                <h5 class="mb-2">No Notifications</h5>
                <p class="text-muted mb-0">You're all caught up! No new notifications.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection