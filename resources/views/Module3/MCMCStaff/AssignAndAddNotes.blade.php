<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Assign Inquiry to Agency - MCMC System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
        }

        .main-container {
            background: white;
            margin: 20px auto;
            max-width: 1200px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .header-section::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
            pointer-events: none;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-title {
            margin: 0;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            text-decoration: none;
        }

        .content-section {
            padding: 2rem;
        }

        .inquiry-card {
            background: var(--light-bg);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--primary-color);
        }

        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-assigned {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #b6d4fe;
        }

        .status-in-progress {
            background-color: #cce5ff;
            color: #004085;
            border: 1px solid #b3d9ff;
        }

        .status-resolved {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .priority-low {
            color: #28a745;
        }

        .priority-medium {
            color: #ffc107;
        }

        .priority-high {
            color: #fd7e14;
        }

        .priority-urgent {
            color: #dc3545;
        }

        .form-section {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #2980b9);
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .agency-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .agency-card:hover {
            border-color: var(--primary-color);
            background-color: rgba(52, 152, 219, 0.05);
        }

        .agency-card.selected {
            border-color: var(--primary-color);
            background-color: rgba(52, 152, 219, 0.1);
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.5rem;
        }

        .text-truncate-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .main-container {
                margin: 10px;
            }

            .header-content {
                text-align: center;
            }

            .content-section {
                padding: 1rem;
            }

            .form-section {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="header-content">
                <div>
                    <h1 class="h3 mb-2 header-title">
                        <i class="fas fa-tasks me-2"></i>
                        Assign Inquiry to Agency
                    </h1>
                    <p class="mb-0 opacity-75">Review inquiry details and assign to appropriate agency</p>
                </div>
                <a href="{{ route('admin.inquiries') }}" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Inquiries
                </a>
            </div>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            <!-- Display Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Please correct the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Inquiry Details Card -->
            <div class="inquiry-card">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="mb-3">
                            <i class="fas fa-file-alt me-2"></i>
                            Inquiry #{{ $inquiry->inquiry_id }}
                        </h4>
                        <h5 class="text-primary mb-3">{{ $inquiry->title ?? 'No title provided' }}</h5>
                        <div class="mb-3">
                            <strong>Content:</strong>
                            <div class="mt-2 p-3 bg-white rounded text-truncate-3">
                                {{ $inquiry->content ?? 'No content provided' }}
                            </div>
                        </div>
                        @if($inquiry->evidence_url)
                            <div class="mb-3">
                                <strong>Evidence:</strong>
                                <a href="{{ $inquiry->evidence_url }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary ms-2">
                                    <i class="fas fa-external-link-alt"></i> View Evidence
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="bg-white p-3 rounded">
                            <div class="mb-3">
                                <strong>Submitted By:</strong><br>
                                <i class="fas fa-user me-1"></i> {{ $inquiry->user->name ?? 'Unknown User' }}<br>
                                <i class="fas fa-envelope me-1"></i> {{ $inquiry->user->email ?? 'N/A' }}
                            </div>
                            <div class="mb-3">
                                <strong>Date Submitted:</strong><br>
                                <i class="fas fa-calendar me-1"></i>
                                {{ $inquiry->date_submitted ? $inquiry->date_submitted->format('M d, Y H:i') : ($inquiry->created_at ? $inquiry->created_at->format('M d, Y H:i') : 'N/A') }}
                            </div>
                            <div class="mb-3">
                                <strong>Category:</strong><br>
                                <span class="badge bg-secondary">{{ ucfirst($inquiry->category ?? 'General') }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>Current Status:</strong><br>
                                <span
                                    class="status-badge status-{{ strtolower(str_replace(' ', '-', $inquiry->status ?? 'pending')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $inquiry->status ?? 'Pending')) }}
                                </span>
                            </div>
                            @if($inquiry->priority)
                                <div class="mb-3">
                                    <strong>Priority:</strong><br>
                                    <i class="fas fa-circle priority-{{ $inquiry->priority }}"></i>
                                    {{ ucfirst($inquiry->priority) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignment Form -->
            <div class="form-section">
                <h4 class="mb-4">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Assignment Details
                </h4>
                <form method="POST" action="{{ route('admin.assign.inquiry') }}">
                    @csrf
                    <input type="hidden" name="inquiry_id" value="{{ $inquiry->inquiry_id }}">

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Agency Selection -->
                            <div class="mb-4">
                                <label for="agency_id" class="form-label">
                                    <i class="fas fa-building me-2"></i>
                                    Select Agency <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="agency_id" name="agency_id" required>
                                    <option value="">Choose an agency...</option>
                                    @foreach($agencies as $agency)
                                        <option value="{{ $agency->agency_id }}" {{ old('agency_id') == $agency->agency_id ? 'selected' : '' }}>
                                            {{ $agency->agency_name }}
                                            @if($agency->description)
                                                - {{ Str::limit($agency->description, 50) }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Select the most appropriate agency to handle this inquiry.
                                </div>
                            </div>

                            <!-- Priority Selection -->
                            <div class="mb-4">
                                <label for="priority" class="form-label">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Priority Level <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="">Select priority...</option>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>
                                        <i class="fas fa-circle text-success"></i> Low - Standard processing
                                    </option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>
                                        <i class="fas fa-circle text-warning"></i> Medium - Moderate attention
                                    </option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>
                                        <i class="fas fa-circle text-orange"></i> High - Urgent attention
                                    </option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>
                                        <i class="fas fa-circle text-danger"></i> Urgent - Immediate action required
                                    </option>
                                </select>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Set the priority level based on the inquiry's urgency and importance.
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Assignment Notes -->
                            <div class="mb-4">
                                <label for="notes" class="form-label">
                                    <i class="fas fa-sticky-note me-2"></i>
                                    Assignment Notes
                                </label>
                                <textarea class="form-control" id="notes" name="notes" rows="8"
                                    placeholder="Add any special instructions, context, or notes for the assigned agency...">{{ old('notes') }}</textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Optional: Add any context or special instructions for the agency.
                                    <span class="text-muted">(Maximum 1000 characters)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Agency Information Display -->
                    <div id="agency-info" class="mb-4" style="display: none;">
                        <h5 class="text-primary">
                            <i class="fas fa-info-circle me-2"></i>
                            Selected Agency Information
                        </h5>
                        <div class="agency-info-content bg-light p-3 rounded">
                            <!-- Will be populated via JavaScript -->
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.inquiries') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>
                            Assign Inquiry to Agency
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Agency information data
        const agencyData = {
            @foreach($agencies as $agency)
                    {{ $agency->agency_id }}: {
                    name: "{{ $agency->agency_name }}",
                    email: "{{ $agency->agency_email ?? 'N/A' }}",
                    phone: "{{ $agency->agency_phone ?? 'N/A' }}",
                    description: "{{ $agency->description ?? 'No description available' }}"
                },
            @endforeach
        };

        // Show agency information when selected
        document.getElementById('agency_id').addEventListener('change', function () {
            const agencyId = this.value;
            const agencyInfo = document.getElementById('agency-info');
            const agencyInfoContent = document.querySelector('.agency-info-content');

            if (agencyId && agencyData[agencyId]) {
                const agency = agencyData[agencyId];
                agencyInfoContent.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Agency Name:</strong><br>
                            <i class="fas fa-building me-1"></i> ${agency.name}
                        </div>
                        <div class="col-md-6">
                            <strong>Contact Email:</strong><br>
                            <i class="fas fa-envelope me-1"></i> ${agency.email}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <strong>Phone:</strong><br>
                            <i class="fas fa-phone me-1"></i> ${agency.phone}
                        </div>
                        <div class="col-md-6">
                            <strong>Description:</strong><br>
                            <i class="fas fa-info-circle me-1"></i> ${agency.description}
                        </div>
                    </div>
                `;
                agencyInfo.style.display = 'block';
            } else {
                agencyInfo.style.display = 'none';
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function (e) {
            const agencyId = document.getElementById('agency_id').value;
            const priority = document.getElementById('priority').value;

            if (!agencyId) {
                e.preventDefault();
                alert('Please select an agency.');
                return;
            }

            if (!priority) {
                e.preventDefault();
                alert('Please select a priority level.');
                return;
            }

            // Confirm assignment
            const agencyName = agencyData[agencyId] ? agencyData[agencyId].name : 'Selected Agency';
            if (!confirm(`Are you sure you want to assign this inquiry to ${agencyName}?`)) {
                e.preventDefault();
            }
        });
    </script>
</body>

</html>