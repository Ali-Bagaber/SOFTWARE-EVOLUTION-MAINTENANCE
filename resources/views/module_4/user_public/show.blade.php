@extends('components.side_headr')

@section('title', 'Inquiry Details - Module 4')

@section('page_title', 'Inquiry Details')

@section('additional_css')
<style>
    .details-container {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .details-card {
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .card-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .card-subtitle {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-item {
        padding: 1rem;
        background: #f9fafb;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }

    .info-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .info-value {
        font-size: 1rem;
        color: #111827;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
        margin-top: 0.5rem;
    }

    .status-investigation { background: #dbeafe; color: #1e40af; }
    .status-verified { background: #d1fae5; color: #065f46; }
    .status-fake { background: #fee2e2; color: #991b1b; }
    .status-rejected { background: #f3f4f6; color: #374151; }

    .content-section {
        margin-top: 1.5rem;
        padding: 1.5rem;
        background: #f9fafb;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }

    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1rem;
    }

    .evidence-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #2563eb;
        text-decoration: none;
        font-size: 0.875rem;
        margin-top: 1rem;
    }

    .evidence-link:hover {
        text-decoration: underline;
    }

    .back-btn {
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
        margin-bottom: 1rem;
        transition: background-color 0.2s;
    }

    .back-btn:hover {
        background: #1d4ed8;
    }

    @media (max-width: 768px) {
        .details-container {
            padding: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="details-container">
    <a href="{{ route('module4.dashboard') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i>
        Back to Dashboard
    </a>

    <div class="details-card">
        <div class="card-header">
            <h1 class="card-title">{{ $inquiry->title }}</h1>
            <p class="card-subtitle">Submitted on {{ $inquiry->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>

        <div class="card-body">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $inquiry->status)) }}">
                            <i class="fas fa-circle fa-sm"></i>
                            {{ $inquiry->status }}
                        </span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Category</div>
                    <div class="info-value">{{ $inquiry->category ?? 'Not Specified' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Last Updated</div>
                    <div class="info-value">{{ $inquiry->updated_at->format('M d, Y \a\t h:i A') }}</div>
                </div>
            </div>

            <div class="content-section">
                <h2 class="section-title">Inquiry Content</h2>
                <p>{{ $inquiry->content }}</p>
                @if($inquiry->evidence_url)
                <a href="{{ $inquiry->evidence_url }}" target="_blank" class="evidence-link">
                    <i class="fas fa-external-link-alt"></i>
                    View Evidence
                </a>
                @endif
            </div>

            @if($inquiry->history->count() > 0)
            <div class="content-section">
                <h2 class="section-title">Status History</h2>
                <div class="timeline">
                    @foreach($inquiry->history->sortByDesc('created_at') as $history)
                    <div class="timeline-item">
                        <div class="info-item" style="margin-bottom: 1rem;">
                            <div class="info-label">{{ $history->created_at->format('M d, Y \a\t h:i A') }}</div>
                            <div class="info-value">
                                Status changed from "{{ $history->previous_status }}" to "{{ $history->status }}"
                            </div>
                            @if($history->notes)
                            <div class="card-subtitle" style="margin-top: 0.5rem;">
                                {{ $history->notes }}
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
