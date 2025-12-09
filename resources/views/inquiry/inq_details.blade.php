<!DOCTYPE html>
<html lang="en">
<head>    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $userRole === 'admin' ? 'Admin - ' : '' }}Inquiry Details - {{ $inquiry->title }} - Inquira</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
        }        /* Header */
        .header {
            background: {{ $userRole === 'admin' ? 'linear-gradient(135deg, #2c3e50, #34495e)' : 'linear-gradient(135deg, #1e293b, #0f172a)' }};
            color: white;
            padding: 2rem 0;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .header p {
            opacity: 0.8;
            font-size: 1.1rem;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* Back Button */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #3b82f6;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 2rem;
            transition: background 0.2s;
        }

        .back-btn:hover {
            background: #2563eb;
        }

        /* Main Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }

        .card h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 1rem;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        .status-pending { background: #fef3c7; color: #d97706; }
        .status-under-review { background: #dbeafe; color: #2563eb; }
        .status-assigned { background: #e0e7ff; color: #4f46e5; }
        .status-in-progress { background: #dbeafe; color: #0ea5e9; }
        .status-resolved { background: #d1fae5; color: #059669; }
        .status-closed { background: #f1f5f9; color: #64748b; }
        .status-rejected { background: #fee2e2; color: #dc2626; }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .info-item {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
        }

        .info-label {
            font-weight: 600;
            color: #64748b;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-weight: 500;
            color: #1e293b;
        }

        /* Content Section */
        .content-section {
            background: #fafbfc;
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            margin-bottom: 1.5rem;
        }

        .content-section h4 {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.75rem;
        }

        .content-text {
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        /* Attachments */
        .attachments {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .attachment-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #f0f9ff;
            color: #0369a1;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border: 1px solid #e0f2fe;
            transition: all 0.2s;
        }

        .attachment-item:hover {
            background: #e0f2fe;
            transform: translateY(-1px);
        }

        /* History Timeline */
        .timeline {
            position: relative;
            margin: 1rem 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
            padding-left: 3rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 14px;
            top: 8px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #3b82f6;
            border: 3px solid white;
            box-shadow: 0 0 0 2px #3b82f6;
        }

        .timeline-content {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .timeline-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .timeline-status {
            font-weight: 600;
            color: #1e293b;
        }

        .timeline-date {
            font-size: 0.875rem;
            color: #64748b;
        }

        .timeline-comment {
            color: #475569;
            margin-top: 0.5rem;
        }

        /* Role-based visibility */
        .admin-only, .agency-only, .owner-only {
            display: none;
        }

        /* Admin role styling */
        body.role-admin .admin-only {
            display: block;
        }

        body.role-admin .admin-section {
            border-left: 4px solid #dc2626;
            background: #fef2f2;
        }

        /* Agency role styling */
        body.role-agency .agency-only {
            display: block;
        }

        body.role-agency .agency-section {
            border-left: 4px solid #059669;
            background: #f0fdf4;
        }

        /* Owner role styling */
        body.role-owner .owner-only {
            display: block;
        }

        body.role-owner .owner-section {
            border-left: 4px solid #7c3aed;
            background: #faf5ff;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-success {
            background: #059669;
            color: white;
        }

        .btn-success:hover {
            background: #047857;
        }

        .btn-warning {
            background: #d97706;
            color: white;
        }

        .btn-warning:hover {
            background: #b45309;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }        .btn-danger:hover {
            background: #b91c1c;
        }

        /* Admin Badge */
        .admin-badge {
            background: #e74c3c;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-left: 1rem;
        }

        /* Admin Actions Styling */
        .admin-actions-card {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border: 2px solid #e74c3c;
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.1);
        }

        .admin-actions-card h2 {
            color: #2c3e50;
            border-bottom: 2px solid #e74c3c;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }

        .admin-action-btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .admin-action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .btn-discard-admin {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }        .btn-discard-admin:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
        }

        .btn-back-admin {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
        }

        .btn-back-admin:hover {
            background: linear-gradient(135deg, #4b5563, #374151);
        }

        /* Privacy Protection */
        .privacy-protected {
            background: #fef3c7;
            color: #92400e;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #fbbf24;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .container {
                padding: 1rem;
            }

            .card {
                padding: 1.5rem;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body class="role-{{ $userRole }}">
    <div class="header">
        <h1>
            @if($userRole === 'admin')
                <i class="fas fa-shield-alt"></i>
                Admin Inquiry Details
                <span class="admin-badge">Admin View</span>
            @else
                Inquiry Details
            @endif
        </h1>
        <p>{{ $inquiry->title }}</p>
    </div>

    <div class="container">
        <a href="{{ url()->previous() }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Inquiries
        </a>

        <div class="content-grid">
            <!-- Main Content -->
            <div class="main-content">
                <div class="card">
                    <h2>
                        <i class="fas fa-info-circle"></i>
                        Inquiry Information
                    </h2>

                    <!-- Status Badge -->
                    <div class="status-badge status-{{ strtolower(str_replace(' ', '-', $inquiry->status)) }}">
                        <i class="fas fa-circle"></i>
                        {{ $inquiry->status_label }}
                    </div>                    <!-- Basic Information Grid -->
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Inquiry ID</div>
                            <div class="info-value">
                                @if($userRole === 'admin')
                                    #INQ{{ str_pad($inquiry->inquiry_id, 3, '0', STR_PAD_LEFT) }}
                                @else
                                    #{{ $inquiry->inquiry_id }}
                                @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">{{ $userRole === 'admin' ? 'Priority/Category' : 'Category' }}</div>
                            <div class="info-value">{{ ucfirst($inquiry->category ?? 'Not specified') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Date Submitted</div>
                            <div class="info-value">
                                @if($userRole === 'admin')
                                    {{ $inquiry->date_submitted->format('F j, Y \a\t g:i A') }}
                                @else
                                    {{ $inquiry->date_submitted->format('M d, Y') }}
                                @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Last Updated</div>
                            <div class="info-value">
                                @if($userRole === 'admin')
                                    {{ $inquiry->updated_at->format('F j, Y \a\t g:i A') }}
                                @else
                                    {{ $inquiry->updated_at->format('M d, Y H:i') }}
                                @endif
                            </div>
                        </div>
                    </div>                    <!-- Submitter Information (Enhanced for Admin) -->
                    @if($userRole === 'admin')
                    <div class="content-section admin-section">
                        <h4>
                            <i class="fas fa-user-shield"></i>
                            Submitter Information (Admin View)
                        </h4>
                        @if($inquiry->user)
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Full Name</div>
                                    <div class="info-value">{{ $inquiry->user->name ?? 'Unknown User' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Email Address</div>
                                    <div class="info-value">{{ $inquiry->user->email ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Contact Number</div>
                                    <div class="info-value">{{ $inquiry->contact_number ?? 'Not provided' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">User Role</div>
                                    <div class="info-value">{{ ucfirst($inquiry->user->user_role ?? 'Unknown') }}</div>
                                </div>
                            </div>
                        @else
                            <p style="color: #dc2626; font-weight: 500;">⚠️ User information not available - Account may have been deleted</p>
                        @endif
                    </div>
                    @elseif($userRole === 'agency' || $userRole === 'owner')
                    <div class="content-section {{ $userRole }}-section">
                        <h4>
                            <i class="fas fa-user"></i>
                            Submitted By
                        </h4>
                        @if($inquiry->user)
                            <p><strong>Name:</strong> {{ $inquiry->user->name ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $inquiry->user->email ?? 'N/A' }}</p>
                        @else
                            <p>User information not available</p>
                        @endif
                    </div>
                    @else
                    <div class="privacy-protected">
                        <i class="fas fa-shield-alt"></i>
                        Personal information is protected for privacy reasons
                    </div>
                    @endif

                    <!-- Inquiry Content -->
                    <div class="content-section">
                        <h4>
                            <i class="fas fa-file-text"></i>
                            Inquiry Details
                        </h4>
                        <div class="content-text">{{ $inquiry->content }}</div>
                    </div>

                    <!-- Attachments and Evidence -->
                    @if($inquiry->media_attachment || $inquiry->evidence_url)
                    <div class="content-section">
                        <h4>
                            <i class="fas fa-paperclip"></i>
                            Attachments & Evidence
                        </h4>
                        <div class="attachments">                            @if($inquiry->media_attachment)
                            <a href="{{ asset('storage/' . $inquiry->media_attachment) }}" target="_blank" class="attachment-item">
                                <i class="fas fa-file"></i>
                                {{ basename($inquiry->media_attachment) }}
                            </a>
                            @endif
                            @if($inquiry->evidence_url)
                            <a href="{{ $inquiry->evidence_url }}" target="_blank" class="attachment-item">
                                <i class="fas fa-external-link-alt"></i>
                                Evidence URL
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>                <!-- Admin/Agency Actions -->
                @if($userRole === 'admin')
                <div class="card admin-actions-card">
                    <h2>
                        <i class="fas fa-cogs"></i>
                        Admin Actions & Controls
                    </h2>                    <div class="action-buttons">
                        <button class="admin-action-btn btn-discard-admin" onclick="discardAsNonSerious({{ $inquiry->inquiry_id }})">
                            <i class="fas fa-trash-alt"></i>
                            Discard as Non-Serious
                        </button>
                        <a href="{{ route('admin.inquiries') }}" class="admin-action-btn btn-back-admin">
                            <i class="fas fa-tachometer-alt"></i>
                            Back to Admin Dashboard
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="sidebar-content">
             @include('module_4.user_public.inquiry_history', ['inquiry' => $inquiry, 'userRole' => $userRole])

                <!-- Quick Stats (Admin/Agency only) -->
                <div class="admin-only agency-only">
                    <div class="card">
                        <h3>
                            <i class="fas fa-chart-bar"></i>
                            Quick Stats
                        </h3>
                        <div class="info-item">
                            <div class="info-label">Days Open</div>
                            <div class="info-value">{{ $inquiry->date_submitted->diffInDays(now()) }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Status Changes</div>
                            <div class="info-value">{{ $inquiry->history->count() }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Priority</div>
                            <div class="info-value">
                                @if($inquiry->date_submitted->diffInDays(now()) > 30)
                                    <span style="color: #dc2626;">High</span>
                                @elseif($inquiry->date_submitted->diffInDays(now()) > 14)
                                    <span style="color: #d97706;">Medium</span>
                                @else
                                    <span style="color: #059669;">Normal</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>            </div>
        </div>
    </div>    @if($userRole === 'admin')
    <script>
        // Discard as non-serious function
        function discardAsNonSerious(inquiryId) {
            if (confirm('Are you sure you want to discard this inquiry as non-serious? This will mark it as closed.')) {
                fetch(`/admin/inquiries/${inquiryId}/discard-non-serious`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Inquiry has been discarded as non-serious.');
                        window.location.href = '{{ route('admin.inquiries') }}';
                    } else {
                        alert('Error: ' + (data.message || 'Failed to discard inquiry'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing the request.');
                });
            }
        }
    </script>
    @endif
</body>
</html>