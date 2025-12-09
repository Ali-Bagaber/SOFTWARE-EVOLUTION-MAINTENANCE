@extends('components.side_headr')

@section('title', 'Submit New Inquiry - Inquira')

@section('additional_css')
<style>

        

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
        }

        /* Form Section */
        .form-section {
            padding: 60px 40px;
            background: #f8fafc;
            min-height: calc(100vh - 90px);
            margin-left: -140px;
        }

        .form-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .form-header {
            background: linear-gradient(135deg, #3b82f6 0%, rgb(29, 216, 191) 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }

        .form-header h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .form-header p {
            font-size: 18px;
            opacity: 0.9;
            line-height: 1.5;
        }

        .form-body {
            padding: 50px;
        }

        .form-group {
            margin-bottom: 32px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #1e293b;
            font-size: 16px;
        }

        .required {
            color: #ef4444;
        }

        .form-control {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .form-select {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            font-family: 'Inter', sans-serif;
            background: white;
            transition: all 0.3s ease;
        }

        .form-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 20px;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            background: #f8fafc;
            color: #64748b;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            min-height: 80px;
        }

        .file-label:hover {
            border-color: #3b82f6;
            background: #eff6ff;
            color: #3b82f6;
        }

        .file-label i {
            font-size: 24px;
        }

        .form-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            padding-top: 20px;
        }

        .btn {
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            font-family: 'Inter', sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #64748b;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .form-help {
            margin-top: 8px;
            font-size: 14px;
            color: #64748b;
            line-height: 1.4;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .main-content {
                margin-left: 0;
            }

            .header {
                padding: 20px;
                min-height: 70px;
            }

            .header h2 {
                font-size: 24px;
            }

            .form-section {
                padding: 20px;
            }

            .form-body {
                padding: 30px 20px;
            }

            .form-header {
                padding: 30px 20px;
            }

            .form-header h1 {
                font-size: 24px;
            }

            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }
        }

        @media (max-width: 768px) {
            .user-profile {
                gap: 8px;
            }

            .user-info h4 {
                font-size: 14px;
            }

            .user-info p {
                font-size: 12px;
            }
        }
    </style>
@endsection

@section('page_title', 'Submit Inquiry')

@section('content')
    <!-- Main Content -->
    <main class="main-content">
        

        <!-- Form Section -->
        <section class="form-section">
            <div class="form-container">
                <div class="form-header">
                    <h1>Submit Your Inquiry</h1>
                    <p>Share your concerns, suggestions, or questions with us. We value your feedback and will ensure your inquiry reaches the appropriate authorities.</p>
                </div>

                <div class="form-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <div>
                                <strong>Please correct the following errors:</strong>
                                <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('inquiry.store') }}" enctype="multipart/form-data">
                        @csrf
                          <div class="form-group">
                            <label for="title">Inquiry Title <span class="required">*</span></label>
                            <input 
                                type="text" 
                                id="title" 
                                name="title" 
                                class="form-control" 
                                value="{{ old('title') }}"
                                placeholder="Enter a clear, descriptive title for your inquiry"
                                required
                                maxlength="150"
                            >
                            <div class="form-help">Provide a brief but descriptive title that summarizes your inquiry (max 150 characters)</div>
                        </div>                        <div class="form-group">
                            <label for="category">Category <span class="required">*</span></label>
                            <select id="category" name="category" class="form-select" required>
                                <option value="">Select a category</option>
                                <option value="Health-Related News" {{ old('category') == 'Health-Related News' ? 'selected' : '' }}>Health-Related News</option>
                                <option value="Government & Policy News" {{ old('category') == 'Government & Policy News' ? 'selected' : '' }}>Government & Policy News</option>
                                <option value="Crime & Safety Alerts" {{ old('category') == 'Crime & Safety Alerts' ? 'selected' : '' }}>Crime & Safety Alerts</option>
                                <option value="Natural Disasters & Weather" {{ old('category') == 'Natural Disasters & Weather' ? 'selected' : '' }}>Natural Disasters & Weather</option>
                                <option value="Economic & Financial News" {{ old('category') == 'Economic & Financial News' ? 'selected' : '' }}>Economic & Financial News</option>
                                <option value="Social Issues & Viral Content" {{ old('category') == 'Social Issues & Viral Content' ? 'selected' : '' }}>Social Issues & Viral Content</option>
                            </select>
                            <div class="form-help">Choose the category that best describes your inquiry</div>
                        </div>                        <div class="form-group">
                            <label for="content">Detailed Description <span class="required">*</span></label>
                            <textarea 
                                id="content" 
                                name="content" 
                                class="form-control" 
                                placeholder="Provide a detailed description of your inquiry, including relevant background information, specific issues, and what outcome you are seeking..."
                                required
                            >{{ old('content') }}</textarea>
                            <div class="form-help">Please provide as much detail as possible to help us understand and address your inquiry effectively</div>
                        </div>                        <div class="form-group">
                            <label for="evidence_url">Evidence URL (Optional)</label>
                            <input 
                                type="url" 
                                id="evidence_url" 
                                name="evidence_url" 
                                class="form-control" 
                                value="{{ old('evidence_url') }}"
                                placeholder="Enter URL for any online evidence or documentation"
                            >
                            <div class="form-help">Optional: Link to online evidence, documents, or other relevant resources</div>
                        </div>                        <div class="form-group">
                            <label for="media_attachment">Media Attachment <span class="required">*</span></label>
                            <div class="file-upload">
                                <input 
                                    type="file" 
                                    id="media_attachment" 
                                    name="media_attachment" 
                                    class="file-input"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif"
                                    required
                                >
                                <label for="media_attachment" class="file-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <div>
                                        <strong>Click to upload file</strong><br>
                                        <small>PDF, DOC, Images (Max 10MB)</small>
                                    </div>
                                </label>
                            </div>
                            <div class="form-help">Upload any relevant documents, images, or evidence that support your inquiry</div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                                Submit Inquiry
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>    <script>
        // File upload feedback
        document.getElementById('media_attachment').addEventListener('change', function(e) {
            const label = document.querySelector('.file-label');
            const file = e.target.files[0];
            
            if (file) {
                label.innerHTML = `
                    <i class="fas fa-check-circle" style="color: #10b981;"></i>
                    <div>
                        <strong>File selected: ${file.name}</strong><br>
                        <small>Click to change file</small>
                    </div>
                `;
                label.style.borderColor = '#10b981';
                label.style.backgroundColor = '#ecfdf5';
                label.style.color = '#10b981';
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const title = document.getElementById('title').value;
            const category = document.getElementById('category').value;
            const content = document.getElementById('content').value;
            const mediaAttachment = document.getElementById('media_attachment').files[0];

            if (!title.trim()) {
                e.preventDefault();
                alert('Please enter an inquiry title before submitting.');
                return false;
            }

            if (!category) {
                e.preventDefault();
                alert('Please select a category for your inquiry.');
                return false;
            }

            if (!content.trim()) {
                e.preventDefault();
                alert('Please provide a detailed description of your inquiry.');
                return false;
            }

            if (!mediaAttachment) {
                e.preventDefault();
                alert('Please upload a media attachment to support your inquiry.');
                return false;
            }

            // Show loading state
            const submitBtn = document.querySelector('.btn-primary');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            submitBtn.disabled = true;
        });
    </script>
@endsection