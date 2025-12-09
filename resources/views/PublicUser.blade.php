@extends('components.side_headr')

@section('title', 'Dashboard - Inquira')

@section('additional_css')
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
            display: flex;
            flex-direction: column;

            max-width: 100%;
        }

        /* Enhanced Alert System */
        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            min-width: 350px;
            max-width: 500px;
            padding: 16px 20px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            z-index: 9999;
            animation: slideInRight 0.4s ease-out, fadeOut 0.4s ease-in 4.6s forwards;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            backdrop-filter: blur(10px);
        }

        .alert-content {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.4;
        }

        .alert-close {
            position: absolute;
            top: 8px;
            right: 8px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            border-radius: 6px;
            transition: all 0.2s ease;
            opacity: 0.7;
        }

        .alert-close:hover {
            opacity: 1;
            background: rgba(0, 0, 0, 0.1);
        }

        .alert-success {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-success .alert-close {
            color: #166534;
        }

        .alert-error {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border: 1px solid #fecaca;
            color: #dc2626;
        }

        .alert-error .alert-close {
            color: #dc2626;
        }

        .alert-info {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid #93c5fd;
            color: #1d4ed8;
        }

        .alert-info .alert-close {
            color: #1d4ed8;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 1px solid #fde68a;
            color: #d97706;
        }

        .alert-warning .alert-close {
            color: #d97706;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }

        @media (max-width: 768px) {
            .alert {
                position: fixed;
                top: 10px;
                left: 10px;
                right: 10px;
                min-width: auto;
                max-width: none;
            }
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
        }

        

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #3b82f6 0%, rgb(29, 216, 191) 100%);
            color: white;
            padding: 80px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.15"/><circle cx="20" cy="80" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 900px;
            margin: 0 auto;
        }

        .hero-title {
            font-size: 56px;
            font-weight: 800;
            margin-bottom: 24px;
            line-height: 1.1;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .hero-subtitle {
            font-size: 22px;
            opacity: 0.95;
            margin-bottom: 40px;
            font-weight: 400;
            line-height: 1.5;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 40px;
        }

        .cta-button {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            padding: 18px 36px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            margin-top: 8px;
        }

        .cta-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        /* Quick Actions Section */
        .quick-actions-section {
            background: white;
            padding: 80px 40px;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 32px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .action-card {
            background: white;
            padding: 32px;
            border-radius: 16px;
            text-decoration: none;
            color: inherit;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            display: block;
            position: relative;
            overflow: hidden;
        }

        .action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
            border-color: #3b82f6;
        }

        .action-card:hover::before {
            transform: scaleX(1);
        }

        .action-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: white;
            transition: transform 0.3s ease;
        }

        .action-card:hover .action-icon {
            transform: scale(1.1);
        }

        .action-title {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 12px;
        }

        .action-description {
            color: #64748b;
            line-height: 1.6;
            margin: 0;
        }

        /* Stats Section */
        .stats-section {
            background: #f8fafc;
            padding: 80px 40px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .stat-card {
            background: white;
            padding: 40px 32px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
            min-height: 160px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .stat-number {
            font-size: 42px;
            font-weight: 800;
            color: rgb(59, 202, 246);
            margin-bottom: 12px;
            line-height: 1;
        }

        .stat-label {
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.8px;
            line-height: 1.2;
        }

        /* Features Section */
        .features-section {
            padding: 100px 40px;
            background: white;
        }

        .section-header {
            text-align: center;
            margin-bottom: 80px;
        }

        .section-title {
            font-size: 42px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .section-subtitle {
            font-size: 20px;
            color: #64748b;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 40px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .feature-card {
            background: white;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            min-height: 280px;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #60a5fa, #3b82f6);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #60a5fa, #3b82f6);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            color: white;
            font-size: 24px;
        }

        .feature-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 12px;
        }

        .feature-description {
            color: #64748b;
            font-size: 16px;
            line-height: 1.6;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .header {
                padding: 24px 32px;
            }

            .hero-title {
                font-size: 42px;
            }

            .hero-section {
                padding: 60px 32px;
            }

            .features-section, .stats-section {
                padding: 60px 32px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 24px;
            }

            .actions-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 24px;
            }

            .quick-actions-section {
                padding: 60px 32px;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 20px 24px;
                flex-direction: column;
                gap: 16px;
                text-align: center;
                min-height: auto;
            }

            .header h2 {
                font-size: 28px;
            }

            .hero-section {
                padding: 48px 24px;
                min-height: 320px;
            }

            .hero-title {
                font-size: 36px;
                margin-bottom: 20px;
            }

            .hero-subtitle {
                font-size: 18px;
                margin-bottom: 32px;
            }

            .features-section, .stats-section {
                padding: 48px 24px;
            }

            .section-title {
                font-size: 32px;
            }

            .section-header {
                margin-bottom: 48px;
            }

            .feature-card {
                padding: 32px 24px;
                min-height: 240px;
            }

            .stat-card {
                padding: 32px 24px;
                min-height: 140px;
            }

            .action-card {
                padding: 24px;
            }

            .actions-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .quick-actions-section {
                padding: 48px 24px;
            }

            .stat-number {
                font-size: 36px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
        }

        @media (max-width: 480px) {
            .header h2 {
                font-size: 24px;
            }

            .hero-title {
                font-size: 28px;
            }

            .hero-subtitle {
                font-size: 16px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Animation keyframes */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .feature-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .feature-card:nth-child(1) { animation-delay: 0.1s; }
        .feature-card:nth-child(2) { animation-delay: 0.2s; }
        .feature-card:nth-child(3) { animation-delay: 0.3s; }

        .stat-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }
    </style>
@endsection

@section('content')

        <!-- Enhanced Flash Message System -->
        @if(session('success'))
            <div class="alert alert-success" id="successAlert">
                <div class="alert-content">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="alert-close" onclick="closeAlert('successAlert')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error" id="errorAlert">
                <div class="alert-content">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" class="alert-close" onclick="closeAlert('errorAlert')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info" id="infoAlert">
                <div class="alert-content">
                    <i class="fas fa-info-circle"></i>
                    <span>{{ session('info') }}</span>
                </div>
                <button type="button" class="alert-close" onclick="closeAlert('infoAlert')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning" id="warningAlert">
                <div class="alert-content">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>{{ session('warning') }}</span>
                </div>
                <button type="button" class="alert-close" onclick="closeAlert('warningAlert')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Welcome to Inquira</h1>
                <p class="hero-subtitle">
                    Express your thoughts and opinions about the latest news with complete freedom and transparency
                </p>
                <a href="{{ route('inquiry.create') }}" class="cta-button">Submit Your First Inquiry</a>
            </div>
        </section>

        <!-- Quick Actions Section -->
        <section class="quick-actions-section">
            <div class="section-header">
                <h2 class="section-title">Quick Actions</h2>
                <p class="section-subtitle">Access your most used features quickly</p>
            </div>
            <div class="actions-grid">
                <a href="{{ route('inquiry.dashboard') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-list"></i>
                    </div>
                    <h3 class="action-title">My Inquiries</h3>
                    <p class="action-description">View and track all your submitted inquiries</p>
                </a>
                <a href="{{ route('inquiry.create') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-plus"></i>
                    </div>
                    <h3 class="action-title">Submit Inquiry</h3>
                    <p class="action-description">Create a new inquiry or report</p>
                </a>
                <a href="{{ route('inquiry.browse') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="action-title">Browse Inquiries</h3>
                    <p class="action-description">Explore public inquiries from other users</p>
                </a>
                <a href="{{ route('publicuser.profile') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <h3 class="action-title">Profile Settings</h3>
                    <p class="action-description">Update your profile and preferences</p>
                </a>
            </div>
        </section>

        <!-- Stats Section -->
        

        <!-- Features Section -->
     

    <script>
        function closeAlert(alertId) {
            const alert = document.getElementById(alertId);
            if (alert) {
                alert.style.animation = 'fadeOut 0.3s ease-in forwards';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }
        }

        // Auto-close alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert && alert.parentNode) {
                        closeAlert(alert.id);
                    }
                }, 5000);
            });
        });

        // Add smooth scrolling for navigation links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                this.classList.add('active');
            });
        });

        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all feature cards and stat cards
        document.querySelectorAll('.feature-card, .stat-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });

        // Add counter animation for stats
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current).toLocaleString();
            }, 30);
        }

        // Animate counters when they come into view
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const number = entry.target.querySelector('.stat-number');
                    const text = number.textContent;
                    const target = parseInt(text.replace(/[^\d]/g, ''));
                    
                    if (!isNaN(target)) {
                        number.textContent = '0';
                        animateCounter(number, target);
                    }
                    
                    statsObserver.unobserve(entry.target);
                }
            });
        });

        document.querySelectorAll('.stat-card').forEach(card => {
            statsObserver.observe(card);
        });
    </script>
@endsection