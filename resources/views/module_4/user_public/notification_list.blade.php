@extends('components.side_headr')

@section('title', 'Notifications')

@section('page_title', 'Notifications')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <div>
                <h5 class="mb-0">Your Notifications</h5>
            </div>
        </div>
        <div class="card-body p-0">
            @if($notifications->count() > 0)
                <div class="notification-list">
                    @foreach($notifications as $notification)
                        <div class="notification-item p-4 border-bottom {{ !$notification->is_read ? 'unread' : '' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="mb-2">
                                        <span class="badge bg-primary mb-2">
                                            <i class="fas fa-sync-alt me-1"></i> Status Update
                                        </span>
                                    </div>
                                    <p class="mb-2">{{ $notification->message }}</p>
                                    <div class="text-muted small">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $notification->date_sent->diffForHumans() }}
                                        @if($notification->inquiry)
                                         · <a href="{{ route('module4.inquiry.show', ['inquiry_id' => $notification->inquiry_id]) }}" class="ms-2">
                                            View Inquiry <i class="fas fa-external-link-alt ms-1"></i>
                                        </a>
                                        @endif
                                    </div>
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

<style>
body {
    background: #f6f8fa;
}
.container {
    max-width: 700px;
    margin: 0 auto;
}
.card {
    border-radius: 16px;
    border: none;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
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
    box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    transition: box-shadow 0.2s, background 0.2s;
    border: 1px solid #e5e7eb;
    display: flex;
    align-items: flex-start;
    gap: 16px;
}
.notification-item:hover {
    background: #f0f6ff;
    box-shadow: 0 2px 8px rgba(37,99,235,0.08);
}
.notification-item.unread {
    background: #e8f0fe;
    border-left: 4px solid #2563eb;
}
.notification-item.unread:hover {
    background: #dbeafe;
}
.badge.bg-primary {
    background: #2563eb;
    color: #fff;
    font-size: 0.85rem;
    padding: 0.4em 0.8em;
    border-radius: 8px;
    font-weight: 500;
}
.btn.btn-light {
    border-radius: 8px;
    background: #f3f6fa;
    color: #2563eb;
    border: 1px solid #e3e6ea;
    font-weight: 500;
    transition: background 0.2s, color 0.2s;
}
.btn.btn-light:hover {
    background: #2563eb;
    color: #fff;
}
.text-muted {
    color: #6b7280 !important;
}
.text-center .fa-bell-slash {
    color: #cbd5e1;
}
.btn.btn-link.text-decoration-none {
    background: linear-gradient(90deg, #2563eb 0%, #60a5fa 100%);
    color: #fff !important;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    padding: 8px 18px;
    box-shadow: 0 1px 4px rgba(37,99,235,0.08);
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    text-decoration: none !important;
    outline: none;
    margin-left: 12px;
}
.btn.btn-link.text-decoration-none:hover, .btn.btn-link.text-decoration-none:focus {
    background: linear-gradient(90deg, #1d4ed8 0%, #3b82f6 100%);
    color: #fff !important;
    box-shadow: 0 2px 8px rgba(37,99,235,0.15);
    text-decoration: none !important;
}
.btn.btn-sm.btn-light {
    border-radius: 8px;
    background: #fff;
    color: #2563eb;
    border: 1.5px solid #2563eb;
    font-weight: 500;
    font-size: 0.95rem;
    padding: 4px 14px;
    transition: background 0.2s, color 0.2s, border 0.2s, box-shadow 0.2s;
    box-shadow: 0 1px 3px rgba(37,99,235,0.06);
    outline: none;
}
.btn.btn-sm.btn-light:hover, .btn.btn-sm.btn-light:focus {
    background: #2563eb;
    color: #fff;
    border-color: #2563eb;
    box-shadow: 0 2px 8px rgba(37,99,235,0.13);
}
@media (max-width: 600px) {
    .container {
        padding: 0 4px;
    }
    .card-header {
        padding: 16px 8px 10px 8px;
    }
    .notification-list {
        padding: 0 4px 8px 4px;
    }
    .notification-item {
        margin: 10px 0;
        padding: 12px 8px;
          }
}
</style>
@endsection