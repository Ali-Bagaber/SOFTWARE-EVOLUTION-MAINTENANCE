<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inquira')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
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
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            padding: 32px 0 24px 0;
            z-index: 1000;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .logo {
            padding: 0 32px 40px 32px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 40px;
            width: 100%;
        }

        .logo h1 {
            color: white;
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(135deg, #60a5fa, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-menu {
            list-style: none;
            padding: 0 0 0 16px;
            width: 100%;
        }

        .nav-item {
            margin-bottom: 20px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 14px 24px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 17px;
            gap: 14px;
        }

        .nav-link i {
            width: 22px;
            text-align: center;
            font-size: 18px;
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            transform: translateX(6px);
        }

        .nav-item form {
            width: 100%;
        }

        .nav-item form .nav-link {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 14px 24px;
            color: rgba(255, 255, 255, 0.8);
            background: none;
            border: none;
            border-radius: 12px;
            font-size: 17px;
            font-weight: 500;
            gap: 14px;
            text-align: left;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .nav-item form .nav-link:hover,
        .nav-item form .nav-link.active {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            transform: translateX(6px);
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: white;
            padding: 32px 40px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            min-height: 90px;
        }

        .header h2 {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 8px;
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #60a5fa, #3b82f6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .user-info h4 {
            color: #1e293b;
            font-weight: 600;
            font-size: 16px;
            margin: 0 0 4px 0;
        }

        .user-info p {
            color: #64748b;
            font-size: 14px;
            margin: 0;
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
        }
    </style>
    @yield('additional_css')
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <h1>Inquira</h1>
        </div>

        <nav>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('home') }}"
                        class="nav-link {{ request()->routeIs('home') || request()->routeIs('publicuser.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('module4.dashboard') }}"
                        class="nav-link {{ request()->routeIs('module4.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i>
                        Status Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('inquiry.dashboard') }}"
                        class="nav-link {{ request()->routeIs('inquiry.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-list"></i>
                        My Inquiries
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('inquiry.create') }}"
                        class="nav-link  {{ request()->routeIs('inquiry.create') ? 'active' : '' }}">
                        <i class="fas fa-edit"></i>
                        Submit Inquiry
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('inquiry.browse') }}"
                        class="nav-link {{ request()->routeIs('inquiry.browse') ? 'active' : '' }}">
                        <i class="fas fa-search"></i>
                        Browse Inquiries
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('publicuser.view.assigned') }}"
                        class="nav-link {{ request()->routeIs('publicuser.view.assigned') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        View Assigned
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('publicuser.agency.info') }}"
                        class="nav-link {{ request()->routeIs('publicuser.agency.info') ? 'active' : '' }}">
                        <i class="fas fa-info-circle"></i>
                        Agency Information
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('publicuser.profile') }}"
                        class="nav-link {{ request()->routeIs('publicuser.profile') ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        Profile Menu
                    </a>
                </li>



                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="header">
            <h2>@yield('page_title', 'Welcome to Inquira')</h2>
            <div class="user-profile">
                <div class="user-avatar">
                    @if(auth()->user()->profile_picture && file_exists(public_path('storage/' . auth()->user()->profile_picture)))
                        <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Profile Picture"
                            style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                    @else
                        {{ collect(explode(' ', auth()->user()->name))->map(fn($n) => strtoupper(substr($n, 0, 1)))->join('') }}
                    @endif
                </div>
                <div class="user-info">
                    <h4>{{ auth()->user()->name }}</h4>
                    <p>{{ ucfirst(auth()->user()->user_role) }}</p>
                </div>
            </div>
        </header>

        <!-- Content Section -->
        <div class="content">
            @yield('content')
        </div>
    </main>

    <script>
        // Add smooth scrolling for navigation links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function (e) {
                // Remove active class from all links
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                // Add active class to clicked link
                this.classList.add('active');
            });
        });
    </script>
    @yield('additional_scripts')
</body>

</html>