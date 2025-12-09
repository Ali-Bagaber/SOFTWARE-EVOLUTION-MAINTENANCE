@extends('components.side_headr')

@section('title', 'Browse Public Inquiries - Inquira')

@section('additional_css')
<style>
        .content-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 10px 24px;
        }

        .search-filter-card {
            background: white;
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }

        .search-form {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .search-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 20px;
            align-items: end;
        }

        .date-filter-row {
            display: grid;
            grid-template-columns: 1fr 1fr 2fr;
            gap: 20px;
            align-items: end;
        }

        .search-field, .filter-field, .date-field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .search-field label, .filter-field label, .date-field label {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }

        .search-input-group {
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            z-index: 1;
        }

        .search-input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            background: white;
        }

        .filter-select, .date-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            background: #f8fafc;
            transition: all 0.3s ease;
        }

        .filter-select:focus, .date-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            background: white;
        }

        .search-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn-search, .btn-clear {
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .btn-search {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }

        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);
        }

        .btn-clear {
            background: #f3f4f6;
            color: #6b7280;
            border: 2px solid #e5e7eb;
        }

        .btn-clear:hover {
            background: #e5e7eb;
            color: #374151;
        }

        .results-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .results-info {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6b7280;
            font-weight: 500;
        }

        .filter-indicator {
            color: #3b82f6;
        }

        .active-filters {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-tag {
            background: #e0f2fe;
            color: #0369a1;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remove-filter {
            color: #0369a1;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            line-height: 1;
        }

        .remove-filter:hover {
            color: #dc2626;
        }

        .inquiries-grid {
            display: grid;
            gap: 24px;
            margin-bottom: 32px;
        }

        .inquiry-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .inquiry-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
            border-color: #3b82f6;
        }

        .inquiry-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
            gap: 16px;
        }

        .inquiry-title-section {
            flex: 1;
        }

        .inquiry-title {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            line-height: 1.4;
        }

        .inquiry-title i {
            color: #3b82f6;
            font-size: 18px;
        }

        .inquiry-meta {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .inquiry-date, .inquiry-category {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            color: #6b7280;
        }

        .inquiry-status {
            flex-shrink: 0;
        }

        .status-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-in-progress {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-resolved {
            background: #d1fae5;
            color: #065f46;
        }

        .status-closed {
            background: #fee2e2;
            color: #991b1b;
        }

        .inquiry-content {
            margin-bottom: 16px;
            padding: 16px 0;
            border-top: 1px solid #f3f4f6;
            border-bottom: 1px solid #f3f4f6;
        }

        .inquiry-content p {
            color: #4b5563;
            line-height: 1.6;
            margin: 0;
        }

        .inquiry-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .inquiry-attachments {
            display: flex;
            gap: 16px;
        }

        .attachment-indicator, .evidence-indicator {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #6b7280;
            background: #f3f4f6;
            padding: 4px 10px;
            border-radius: 12px;
        }        .privacy-note {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #9ca3af;
            font-style: italic;
        }

        .view-details-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #3b82f6;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin-top: 12px;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .view-details-btn:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .view-details-btn i {
            font-size: 12px;
        }

        .no-results {
            text-align: center;
            padding: 64px 32px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        }

        .no-results-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 24px;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #9ca3af;
        }

        .no-results h3 {
            font-size: 24px;
            color: #374151;
            margin-bottom: 12px;
        }

        .no-results p {
            color: #6b7280;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 24px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 32px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .search-row {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .date-filter-row {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .search-actions {
                justify-content: stretch;
            }

            .btn-search, .btn-clear {
                flex: 1;
                justify-content: center;
            }

            .results-summary {
                flex-direction: column;
                align-items: flex-start;
            }

            .inquiry-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .inquiry-footer {
                flex-direction: column;
                align-items: flex-start;
            }

            .page-title {
                font-size: 28px;
            }

            .page-subtitle {
                font-size: 16px;
            }

            .content-container {
                padding: 0 16px;
            }

            .search-filter-card {
                padding: 24px 20px;
            }
        }
    </style>
@endsection

@section('page_title', 'Browse Public Inquiries')

@section('content')
    <div class="content-container">
    <!-- Search and Filter Section -->
    <div class="search-filter-card">
        <form method="GET" action="{{ route('inquiry.browse') }}" class="search-form">
            <div class="search-row">
                <div class="search-field">
                    <label for="search">Search Inquiries</label>
                    <div class="search-input-group">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               placeholder="Search by title, content, or category..." 
                               value="{{ request('search') }}"
                               class="search-input">
                    </div>
                </div>
                
                <div class="filter-field">
                    <label for="category">Category</label>
                    <select name="category" id="category" class="filter-select">
                        <option value="all">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ ucfirst($category) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-field">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="filter-select">
                        <option value="all">All Statuses</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
            </div>
            
            <div class="date-filter-row">
                <div class="date-field">
                    <label for="date_from">From Date</label>
                    <input type="date" 
                           id="date_from" 
                           name="date_from" 
                           value="{{ request('date_from') }}"
                           class="date-input">
                </div>
                
                <div class="date-field">
                    <label for="date_to">To Date</label>
                    <input type="date" 
                           id="date_to" 
                           name="date_to" 
                           value="{{ request('date_to') }}"
                           class="date-input">
                </div>
                
                <div class="search-actions">
                    <button type="submit" class="btn-search">
                        <i class="fas fa-search"></i>
                        Search
                    </button>
                    <a href="{{ route('inquiry.browse') }}" class="btn-clear">
                        <i class="fas fa-times"></i>
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Results Summary -->
    <div class="results-summary">
        <div class="results-info">
            <i class="fas fa-info-circle"></i>
            <span>Found {{ $inquiries->total() }} inquiries</span>
            @if(request()->hasAny(['search', 'category', 'status', 'date_from', 'date_to']))
                <span class="filter-indicator">• Filters Applied</span>
            @endif
        </div>
        
        @if(request()->hasAny(['search', 'category', 'status', 'date_from', 'date_to']))
            <div class="active-filters">
                @if(request('search'))
                    <span class="filter-tag">
                        Search: "{{ request('search') }}"
                        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="remove-filter">×</a>
                    </span>
                @endif
                @if(request('category') && request('category') !== 'all')
                    <span class="filter-tag">
                        Category: {{ ucfirst(request('category')) }}
                        <a href="{{ request()->fullUrlWithQuery(['category' => 'all']) }}" class="remove-filter">×</a>
                    </span>
                @endif
                @if(request('status') && request('status') !== 'all')
                    <span class="filter-tag">
                        Status: {{ request('status') }}
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}" class="remove-filter">×</a>
                    </span>
                @endif
            </div>
        @endif
    </div>

    <!-- Inquiries List -->
    <div class="inquiries-grid">
        @forelse($inquiries as $inquiry)
            <div class="inquiry-card">
                <div class="inquiry-header">
                    <div class="inquiry-title-section">
                        <h3 class="inquiry-title">
                            <i class="fas fa-question-circle"></i>
                            {{ $inquiry->title }}
                        </h3>
                        <div class="inquiry-meta">
                            <span class="inquiry-date">
                                <i class="fas fa-calendar-alt"></i>
                                {{ \Carbon\Carbon::parse($inquiry->date_submitted)->format('M d, Y') }}
                            </span>
                            @if($inquiry->category)
                                <span class="inquiry-category">
                                    <i class="fas fa-tag"></i>
                                    {{ ucfirst($inquiry->category) }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="inquiry-status">
                        <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $inquiry->status)) }}">
                            @switch($inquiry->status)
                                @case('Pending')
                                    <i class="fas fa-clock"></i>
                                    @break
                                @case('In Progress')
                                    <i class="fas fa-spinner"></i>
                                    @break
                                @case('Resolved')
                                    <i class="fas fa-check-circle"></i>
                                    @break
                                @case('Closed')
                                    <i class="fas fa-times-circle"></i>
                                    @break
                                @default
                                    <i class="fas fa-question"></i>
                            @endswitch
                            {{ $inquiry->status }}
                        </span>
                    </div>
                </div>
                
                @if($inquiry->content)
                    <div class="inquiry-content">
                        <p>{{ \Illuminate\Support\Str::limit($inquiry->content, 200) }}</p>
                    </div>
                @endif
                
                <div class="inquiry-footer">
                    <div class="inquiry-attachments">
                        @if($inquiry->media_attachment)
                            <span class="attachment-indicator">
                                <i class="fas fa-paperclip"></i>
                                Has Attachment
                            </span>
                        @endif
                        @if($inquiry->evidence_url)
                            <span class="evidence-indicator">
                                <i class="fas fa-link"></i>
                                Evidence Link
                            </span>
                        @endif
                    </div>
                      <div class="inquiry-privacy">
                        <span class="privacy-note">
                            <i class="fas fa-user-shield"></i>
                            Submitted by User (Privacy Protected)
                        </span>
                    </div>
                    
                    <a href="{{ route('inquiry.show', $inquiry->inquiry_id) }}" class="view-details-btn">
                        <i class="fas fa-eye"></i>
                        View Details
                    </a>
                </div>
            </div>
        @empty
            <div class="no-results">
                <div class="no-results-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>No Inquiries Found</h3>
                <p>
                    @if(request()->hasAny(['search', 'category', 'status', 'date_from', 'date_to']))
                        No inquiries match your current search criteria. Try adjusting your filters or search terms.
                    @else
                        No public inquiries are available at the moment. Be the first to submit an inquiry!
                    @endif
                </p>
                @if(request()->hasAny(['search', 'category', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('inquiry.browse') }}" class="btn-clear">
                        <i class="fas fa-times"></i>
                        Clear All Filters
                    </a>
                @endif
            </div>
        @endforelse
    </div>        <!-- Pagination -->
        @if($inquiries->hasPages())
            <div class="pagination-wrapper">
                {{ $inquiries->links() }}
            </div>
        @endif
        </div>
@endsection