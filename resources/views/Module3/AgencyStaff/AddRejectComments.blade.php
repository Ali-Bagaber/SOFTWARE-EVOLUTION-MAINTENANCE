<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reject Inquiry - Add Comments</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            color: #2d3748;
            min-height: 100vh;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .form-control,
        .form-select {
            border: 2px solid rgba(102, 126, 234, 0.1);
            border-radius: 12px;
            padding: 15px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
            background: rgba(255, 255, 255, 1);
        }

        .btn {
            padding: 15px 30px;
            border-radius: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            font-size: 14px;
        }

        .btn-danger {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(229, 62, 62, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(229, 62, 62, 0.4);
            background: linear-gradient(135deg, #c53030 0%, #9c2b2b 100%);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #718096 0%, #4a5568 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(113, 128, 150, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(113, 128, 150, 0.4);
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
        }

        .alert {
            border: none;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fef5e7 0%, #fdf2e9 100%);
            color: #744210;
            border-left: 5px solid #ed8936;
        }

        .inquiry-info {
            background: linear-gradient(135deg, #edf2f7 0%, #e2e8f0 100%);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border-left: 5px solid #667eea;
        }

        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .required {
            color: #e53e3e;
        }

        .form-text {
            color: #718096;
            font-size: 14px;
            margin-top: 8px;
        }

        .back-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #5a67d8;
            transform: translateX(-5px);
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .spinner-border {
            display: none;
        }

        .loading .spinner-border {
            display: inline-block;
        }

        .loading .btn-text {
            display: none;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .header,
            .card {
                padding: 20px;
                margin-bottom: 20px;
            }

            .btn {
                padding: 12px 24px;
                font-size: 13px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-2">
                        <i class="fas fa-times-circle text-danger me-3"></i>
                        Reject Inquiry Assignment
                    </h1>
                    <p class="text-muted mb-0">Provide detailed rejection comments and reasons</p>
                </div>
                <a href="{{ route('agency.display.inquiry') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    Back to Inquiries
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="card">
            <!-- Warning Alert -->
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Important:</strong> By rejecting this inquiry, you are indicating that it falls outside your
                agency's jurisdiction.
                MCMC will be notified and the inquiry will be reassigned to the appropriate agency.
            </div>

            @if(isset($inquiry))
                <!-- Inquiry Information -->
                <div class="inquiry-info">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-file-alt me-2"></i>
                        Inquiry Information
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Inquiry ID:</strong> #{{ $inquiry->inquiry_id }}</p>
                            <p><strong>Title:</strong> {{ $inquiry->title }}</p>
                            <p><strong>Submitted By:</strong> {{ $inquiry->user ? $inquiry->user->name : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Category:</strong> {{ $inquiry->category ?? 'General' }}</p>
                            <p><strong>Date Submitted:</strong>
                                {{ \Carbon\Carbon::parse($inquiry->date_submitted)->format('M d, Y') }}</p>
                            <p><strong>Current Status:</strong>
                                <span class="badge bg-warning">{{ $inquiry->status }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p><strong>Description:</strong></p>
                        <p class="text-muted">{{ $inquiry->content ?? 'No description provided.' }}</p>
                    </div>
                </div>
            @endif

            <!-- Rejection Form -->
            <form id="rejectInquiryForm"
                action="{{ route('agency.inquiry.reject', isset($inquiry) ? $inquiry->inquiry_id : 0) }}" method="POST">
                @csrf

                <!-- Rejection Reason -->
                <div class="mb-4">
                    <label for="rejectionReason" class="form-label">
                        <strong>Reason for Rejection <span class="required">*</span></strong>
                    </label>
                    <select class="form-select" id="rejectionReason" name="rejection_reason" required>
                        <option value="">Select a reason...</option>
                        <option value="fake_news">Identified as Fake News</option>
                        <option value="outside_jurisdiction">Outside Agency Jurisdiction</option>
                        <option value="incomplete_information">Incomplete Information</option>
                        <option value="duplicate_inquiry">Duplicate Inquiry</option>
                        <option value="requires_different_agency">Requires Different Agency</option>
                        <option value="technical_issue">Technical Issue</option>
                        <option value="insufficient_documentation">Insufficient Documentation</option>
                        <option value="other">Other (Please specify below)</option>
                    </select>
                    @error('rejection_reason')
                        <div class="text-danger form-text">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Detailed Comments -->
                <div class="mb-4">
                    <label for="rejectionComments" class="form-label">
                        <strong>Detailed Comments <span class="required">*</span></strong>
                    </label>
                    <textarea class="form-control" id="rejectionComments" name="rejection_comments" rows="6"
                        placeholder="Please provide detailed comments explaining why this inquiry is being rejected. This information will help MCMC reassign the inquiry to the correct agency. Include specific reasons, missing information, or suggest which agency might be more appropriate."
                        required minlength="20" maxlength="1000">{{ old('rejection_comments') }}</textarea>
                    <div class="form-text">
                        <span id="charCount">0</span>/1000 characters (minimum 20 characters required)
                    </div>
                    @error('rejection_comments')
                        <div class="text-danger form-text">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Suggested Agency -->
                <div class="mb-4">
                    <label for="suggestedAgency" class="form-label">
                        <strong>Suggested Agency (Optional)</strong>
                    </label>
                    <input type="text" class="form-control" id="suggestedAgency" name="suggested_agency"
                        placeholder="If you know which agency should handle this inquiry, please specify (e.g., Department of Energy, Ministry of Health, etc.)"
                        value="{{ old('suggested_agency') }}">
                    <div class="form-text">Help MCMC by suggesting the most appropriate agency for this inquiry</div>
                    @error('suggested_agency')
                        <div class="text-danger form-text">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Priority Level -->
                <div class="mb-4">
                    <label for="priorityLevel" class="form-label">
                        <strong>Urgency Level</strong>
                    </label>
                    <select class="form-select" id="priorityLevel" name="priority_level">
                        <option value="normal">Normal - Standard processing time</option>
                        <option value="high">High - Requires faster reassignment</option>
                        <option value="urgent">Urgent - Immediate reassignment needed</option>
                    </select>
                    <div class="form-text">Help MCMC prioritize the reassignment of this inquiry</div>
                </div>

                <!-- Additional Notes -->
                <div class="mb-4">
                    <label for="additionalNotes" class="form-label">
                        <strong>Additional Notes (Optional)</strong>
                    </label>
                    <textarea class="form-control" id="additionalNotes" name="additional_notes" rows="3"
                        placeholder="Any additional information that might help with the reassignment process"
                        maxlength="500">{{ old('additional_notes') }}</textarea>
                    <div class="form-text">Maximum 500 characters</div>
                </div>

                <!-- Confirmation Checkbox -->
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmRejection" required>
                        <label class="form-check-label" for="confirmRejection">
                            <strong>I confirm that this inquiry falls outside my agency's jurisdiction and should be
                                rejected for reassignment.</strong>
                        </label>
                    </div>
                    @error('confirmation')
                        <div class="text-danger form-text">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('agency.display.inquiry') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-danger" id="submitBtn">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        <span class="btn-text">
                            <i class="fas fa-times-circle me-2"></i>
                            Reject Inquiry
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Character counter for rejection comments
            const commentsTextarea = document.getElementById('rejectionComments');
            const charCount = document.getElementById('charCount');

            function updateCharCount() {
                const count = commentsTextarea.value.length;
                charCount.textContent = count;

                if (count < 20) {
                    charCount.style.color = '#e53e3e';
                } else if (count > 950) {
                    charCount.style.color = '#ed8936';
                } else {
                    charCount.style.color = '#48bb78';
                }
            }

            commentsTextarea.addEventListener('input', updateCharCount);
            updateCharCount(); // Initial count

            // Form submission handling
            const form = document.getElementById('rejectInquiryForm');
            const submitBtn = document.getElementById('submitBtn');

            form.addEventListener('submit', function (e) {
                // Validate comments length
                const comments = commentsTextarea.value.trim();
                if (comments.length < 20) {
                    e.preventDefault();
                    alert('Please provide detailed comments (minimum 20 characters).');
                    commentsTextarea.focus();
                    return;
                }

                // Show loading state
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;

                // Confirm submission
                if (!confirm('Are you sure you want to reject this inquiry? This action cannot be undone.')) {
                    e.preventDefault();
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                    return;
                }
            });

            // Auto-resize textareas
            function autoResize(textarea) {
                textarea.style.height = 'auto';
                textarea.style.height = (textarea.scrollHeight) + 'px';
            }

            const textareas = document.querySelectorAll('textarea');
            textareas.forEach(textarea => {
                textarea.addEventListener('input', () => autoResize(textarea));
                autoResize(textarea); // Initial resize
            });
        });
    </script>
</body>

</html>