<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inquiry Details #{{ $inquiry->inquiry_id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .main-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }

        .content-section {
            padding: 2rem;
        }

        .info-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--primary-color);
        }

        .info-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 0.875rem;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .info-value {
            font-size: 1rem;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
            font-size: 0.875rem;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-under-investigation {
            background: #cff4fc;
            color: #055160;
        }

        .status-in-progress {
            background: #cfe2ff;
            color: #084298;
        }

        .status-accepted {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-resolved {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .priority-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 15px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .priority-urgent {
            background: #dc3545;
            color: white;
        }

        .priority-high {
            background: #fd7e14;
            color: white;
        }

        .priority-medium {
            background: #ffc107;
            color: #000;
        }

        .priority-low {
            background: #28a745;
            color: white;
        }

        .attachment-preview {
            max-width: 200px;
            border-radius: 8px;
            margin: 0.5rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .attachment-preview:hover {
            transform: scale(1.05);
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 0.75rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            border: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-accept {
            background: var(--success-color);
            color: white;
        }

        .btn-accept:hover {
            background: #229954;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }

        .btn-reject {
            background: var(--danger-color);
            color: white;
        }

        .btn-reject:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-color);
        }

        .timeline-item {
            border-left: 3px solid var(--primary-color);
            padding-left: 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .timeline-item::before {
            content: '';
            width: 12px;
            height: 12px;
            background: var(--primary-color);
            border-radius: 50%;
            position: absolute;
            left: -7.5px;
            top: 0;
        }

        .timeline-date {
            font-size: 0.875rem;
            color: #6c757d;
            font-weight: 600;
        }

        .timeline-content {
            margin-top: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Header -->
        <div class="header-section">
            <div class="header-content">
                <div>
                    <h1><i class="fas fa-file-alt"></i> Inquiry Details #{{ $inquiry->inquiry_id }}</h1>
                    <p class="mb-0">{{ $inquiry->title }}</p>
                </div>
                <a href="{{ route('agency.inquiry.list') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="content-section">
            <!-- Status and Priority -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="info-label">Status</div>
                    <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $inquiry->status)) }}">
                        {{ $inquiry->status }}
                    </span>
                </div>
                @if(isset($verificationProcess) && $verificationProcess && $verificationProcess->priority)
                <div class="col-md-6">
                    <div class="info-label">Priority</div>
                    <span class="priority-badge priority-{{ strtolower($verificationProcess->priority) }}">
                        {{ ucfirst($verificationProcess->priority) }}
                    </span>
                </div>
                @endif
            </div>

            <!-- Basic Information -->
            <div class="section-title"><i class="fas fa-info-circle"></i> Basic Information</div>
            <div class="info-card">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-label">Inquiry ID</div>
                        <div class="info-value">#{{ $inquiry->inquiry_id }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Category</div>
                        <div class="info-value">
                            <span class="badge bg-info">{{ $inquiry->category ?? 'General' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Submitted By</div>
                        <div class="info-value">{{ $inquiry->user->name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Submitted Date</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($inquiry->date_submitted)->format('F d, Y h:i A') }}</div>
                    </div>
                </div>
            </div>

            <!-- Inquiry Details -->
            <div class="section-title"><i class="fas fa-file-text"></i> Inquiry Details</div>
            <div class="info-card">
                <div class="info-label">Title</div>
                <div class="info-value">{{ $inquiry->title }}</div>

                <div class="info-label">Description</div>
                <div class="info-value">{{ $inquiry->content }}</div>

                @if($inquiry->evidence_url)
                <div class="info-label">Evidence URL</div>
                <div class="info-value">
                    <a href="{{ $inquiry->evidence_url }}" target="_blank" class="text-primary">
                        <i class="fas fa-external-link-alt"></i> {{ $inquiry->evidence_url }}
                    </a>
                </div>
                @endif

                @if($inquiry->media_attachment)
                <div class="info-label">Media Attachment</div>
                <div class="info-value">
                    @if(Str::contains($inquiry->media_attachment, ['jpg', 'jpeg', 'png', 'gif']))
                    <img src="{{ asset('storage/' . $inquiry->media_attachment) }}"
                        alt="Attachment"
                        class="attachment-preview"
                        data-bs-toggle="modal"
                        data-bs-target="#imageModal">
                    @else
                    <a href="{{ asset('storage/' . $inquiry->media_attachment) }}"
                        target="_blank"
                        class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-download"></i> Download Attachment
                    </a>
                    @endif
                </div>
                @endif
            </div>

            <!-- Assignment Notes -->
            @if(isset($verificationProcess) && $verificationProcess && $verificationProcess->notes)
            <div class="section-title"><i class="fas fa-sticky-note"></i> Assignment Notes</div>
            <div class="info-card">
                <div class="info-label">Notes from MCMC Staff</div>
                <div class="info-value">{{ $verificationProcess->notes }}</div>
                @if($verificationProcess->assigned_at)
                <div class="info-label">Assigned Date</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($verificationProcess->assigned_at)->format('F d, Y h:i A') }}</div>
                @endif
            </div>
            @endif

            <!-- Action Buttons -->
            @if(in_array($inquiry->status, ['Pending', 'Under Investigation']))
            <div class="action-buttons">
                <a href="{{ route('agency.inquiry.accept', $inquiry->inquiry_id) }}"
                    class="btn btn-action btn-accept"
                    onclick="return confirm('Are you sure you want to accept this inquiry?')">
                    <i class="fas fa-check-circle"></i> Accept Inquiry
                </a>
                <a href="{{ route('agency.inquiry.reject.comments', $inquiry->inquiry_id) }}"
                    class="btn btn-action btn-reject">
                    <i class="fas fa-times-circle"></i> Reject Inquiry
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Image Modal -->
    @if($inquiry->media_attachment && Str::contains($inquiry->media_attachment, ['jpg', 'jpeg', 'png', 'gif']))
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attachment Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('storage/' . $inquiry->media_attachment) }}"
                        alt="Attachment"
                        class="img-fluid">
                </div>
            </div>
        </div>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>