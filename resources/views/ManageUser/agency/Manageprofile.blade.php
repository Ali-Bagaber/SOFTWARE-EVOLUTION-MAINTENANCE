@extends('components.agency_side_headr')

@section('title', 'Manage Profile - ' . ($user->name ?? 'Agency'))
@section('page_title', 'Manage Profile')

@section('additional_styles')
    <style>
        /* Profile Form Styles */
        .profile-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(135deg, #6c7ae0 0%, #7b88e8 100%);
            color: #fff;
            padding: 30px;
            text-align: center;
        }

        .profile-header h2 {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .profile-header p {
            opacity: 0.9;
            font-size: 16px;
        }

        .profile-form {
            padding: 40px;
        }

        .form-section {
            margin-bottom: 35px;
        }

        .section-title {
            color: #495057;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #495057;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            background-color: #fff;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #6c7ae0;
            box-shadow: 0 0 0 3px rgba(108, 122, 224, 0.1);
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .password-section {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background-color: #6c7ae0;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #5a67d8;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-outline {
            background-color: transparent;
            color: #6c7ae0;
            border: 2px solid #6c7ae0;
        }

        .btn-outline:hover {
            background-color: #6c7ae0;
            color: #fff;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #e9ecef;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            display: none;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            display: none;
        }

        /* Profile Picture Styles */
        .profile-picture-section {
            background-color: #fff;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 25px;
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-picture-container {
            position: relative;
            display: inline-block;
            margin-bottom: 20px;
        }

        .profile-picture {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #6c7ae0;
            box-shadow: 0 4px 12px rgba(108, 122, 224, 0.3);
        }

        .profile-picture-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6c7ae0 0%, #7b88e8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            border: 4px solid #6c7ae0;
            box-shadow: 0 4px 12px rgba(108, 122, 224, 0.3);
        }

        .profile-picture-upload {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: #6c7ae0;
            color: white;
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }

        .profile-picture-upload:hover {
            background-color: #5a6fd8;
        }

        .profile-picture-upload input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .upload-text {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .file-info {
            color: #6c757d;
            font-size: 12px;
            margin-top: 10px;
        }
    </style>
@endsection

@section('content')
                <div class="profile-container">
                    <div class="profile-header">
                        <h2><i class="fas fa-building"></i> Agency Profile</h2>
                        <p>Update your agency information and settings</p>
        </div>
        <form class="profile-form" id="profileForm" method="POST" action="{{ route('agency.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        
                        @if(session('success'))
                            <div class="success-message" style="display: block;">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                            </div>
                        @endif
                        
                        @if($errors->any())
                            <div class="error-message" style="display: block;">
                                <i class="fas fa-exclamation-circle"></i> 
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif
                        
                        <div class="success-message" id="successMessage">
                            <i class="fas fa-check-circle"></i> Profile updated successfully!
                        </div>
                          <div class="error-message" id="errorMessage">
                            <i class="fas fa-exclamation-circle"></i> Please fill in all required fields.
                        </div>

                        <!-- Profile Picture Section -->
                        <div class="profile-picture-section">
                            <h3 class="section-title"><i class="fas fa-camera"></i> Profile Picture</h3>
                            <div class="profile-picture-container">
                                @if($user->profile_picture && file_exists(public_path('uploads/profile_pictures/' . $user->profile_picture)))
                                    <img src="{{ asset('uploads/profile_pictures/' . $user->profile_picture) }}" alt="Profile Picture" class="profile-picture" id="profilePreview">
                                @else
                                    <div class="profile-picture-placeholder" id="profilePlaceholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <button type="button" class="profile-picture-upload" onclick="document.getElementById('profilePictureInput').click()">
                                    <i class="fas fa-camera"></i>
                                    <input type="file" id="profilePictureInput" name="profile_picture" accept="image/*" onchange="previewProfilePicture(this)">
                                </button>
                            </div>
                            <div class="upload-text">Click the camera icon to upload a new profile picture</div>
                            <div class="file-info">Supported formats: JPG, PNG, GIF (Max size: 2MB)</div>
                        </div>

                        <!-- Personal Information Section -->
                        <div class="form-section">
                            <h3 class="section-title"><i class="fas fa-user"></i> Personal Information</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="fullName">Full Name *</label>
                                    <input type="text" id="fullName" name="name" value="{{ $user->name }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Address *</label>
                                    <input type="email" id="email" name="email" value="{{ $user->email }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="contactNumber">Contact Number</label>
                                <input type="tel" id="contactNumber" name="contact_number" value="{{ $user->contact_number ?? '' }}">
                            </div>
                        </div>

                        <!-- Agency Information Section -->
                        <div class="form-section">
                            <h3 class="section-title"><i class="fas fa-building"></i> Agency Information</h3>
                            <div class="form-group">
                                <label for="agencyName">Agency Name *</label>
                                <input type="text" id="agencyName" name="agency_name" value="{{ $agency->agency_name ?? $user->name }}" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="agencyEmail">Agency Email</label>
                                    <input type="email" id="agencyEmail" name="agency_email" value="{{ $agency->agency_email ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="agencyPhone">Agency Phone</label>
                                    <input type="tel" id="agencyPhone" name="agency_phone" value="{{ $agency->agency_phone ?? '' }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="agencyDescription">Agency Description</label>
                                <textarea id="agencyDescription" name="description" placeholder="Describe your agency's services and expertise...">{{ $agency->description ?? 'We are a leading technology solutions provider specializing in innovative software development, system integration, and digital transformation services. Our team of experienced professionals delivers cutting-edge solutions to help businesses achieve their technological goals.' }}</textarea>
                            </div>
                        </div>

                        <!-- Security Section -->
                        <div class="form-section">
                            <h3 class="section-title"><i class="fas fa-shield-alt"></i> Security Settings</h3>
                              <div class="password-section">
                                <div class="form-group">
                                    <label>Password Management</label>
                                    <p style="color: #6c757d; margin-bottom: 15px; font-size: 14px;">
                                        For security reasons, password changes require additional verification.
                                    </p>
                                    <a href="{{ route('agency.edit_password') }}" class="btn btn-outline">
                                        <i class="fas fa-key"></i> Change Password
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary" id="cancelBtn">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>

    <script>
        // Profile picture preview function
        function previewProfilePicture(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                // Check file size (2MB limit)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    input.value = '';
                    return;
                }
                
                // Check file type
                if (!file.type.match('image.*')) {
                    alert('Please select a valid image file');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const placeholder = document.getElementById('profilePlaceholder');
                    const preview = document.getElementById('profilePreview');
                    
                    if (placeholder) {
                        // Replace placeholder with image
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Profile Picture';
                        img.className = 'profile-picture';
                        img.id = 'profilePreview';
                        placeholder.parentNode.replaceChild(img, placeholder);
                    } else if (preview) {
                        // Update existing image
                        preview.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        }

        // Form handling
        document.addEventListener('DOMContentLoaded', function() {
            const profileForm = document.getElementById('profileForm');
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            const saveBtn = document.getElementById('saveBtn');
            
            profileForm.addEventListener('submit', function(e) {
                // Reset messages
                successMessage.style.display = 'none';
                errorMessage.style.display = 'none';
                
                // Basic client-side validation
                const formData = new FormData(this);
                const name = formData.get('name');
                const email = formData.get('email');
                const agencyName = formData.get('agency_name');
                
                if (!name || !email || !agencyName) {
                    e.preventDefault();
                    errorMessage.style.display = 'block';
                    return;
                }
                
                // Show loading state
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                saveBtn.disabled = true;
            });

            // Cancel button
            document.getElementById('cancelBtn').addEventListener('click', function() {
                if (confirm('Are you sure you want to discard your changes?')) {
                    // Reset form to original values or navigate away
                    profileForm.reset();
                    // Optionally navigate back to dashboard
                    // window.location.href = '{{ route("agency.dashboard") }}';
                }
            });
        });
    </script>
@endsection