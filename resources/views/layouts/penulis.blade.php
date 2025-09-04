<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Penulis - Penulis BlogSpace')</title>
    
    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Vite Assets for Vue.js -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --penulis-primary: #2563eb; /* biru utama */
            --penulis-secondary: #3b82f6; /* biru terang */
            --sidebar-bg: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%); /* gradient biru */
            --sidebar-text: #e0e7ef;
            --sidebar-active: #fff;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
        }
        
        .penulis-sidebar {
            background: var(--sidebar-bg);
            min-height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            color: #fff;
        }
        
        .penulis-content {
            margin-left: 250px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        .penulis-header {
            background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
            color: #fff;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-bottom: none;
        }
        
        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            text-decoration: none;
            display: block;
        }
        
        .sidebar-brand:hover {
            color: white;
            text-decoration: none;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .sidebar-nav .nav-link {
            color: var(--sidebar-text);
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .sidebar-nav .nav-link i {
            width: 20px;
            text-align: center;
        }
        
        .sidebar-nav .nav-link:hover {
            color: #fff;
            background: rgba(59, 130, 246, 0.2);
            border-left: 3px solid #fff;
        }
        
        .sidebar-nav .nav-link.active {
            color: #fff;
            background: rgba(59, 130, 246, 0.4);
            border-left: 3px solid #fff;
        }
        
        .card-modern {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .btn-penulis-primary {
            background: linear-gradient(135deg, var(--penulis-primary) 0%, var(--penulis-secondary) 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-penulis-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
            color: white;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #fff 0%, #f8fafc 100%);
            border: 1px solid #e2e8f0;
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .sidebar-toggle {
            display: none;
        }
        
        @media (max-width: 768px) {
            .penulis-sidebar {
                transform: translateX(-100%);
            }
            
            .penulis-content {
                margin-left: 0;
            }
            
            .sidebar-toggle {
                display: block;
            }
            
            .penulis-sidebar.show {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="penulis-sidebar" id="sidebar">
            <a href="{{ route('penulis.dashboard') }}" class="sidebar-brand d-flex align-items-center gap-3 p-4" style="display:flex">
                <span class="d-flex align-items-center justify-content-center" style="width:48px; height:48px; background:rgba(255,255,255,0.15); border-radius:12px;">
                    <i class="fas fa-blog" style="font-size:2rem; color:#fff;"></i>
                </span>
                <div class="d-flex flex-column align-items-start justify-content-center lh-1">
                    <span class="fw-bold" style="font-size:1.3rem; letter-spacing:1px;">BlogSpace</span>
                    <span class="fw-semibold" style="font-size:1.05rem; letter-spacing:1px; margin-top:2px;">Penulis</span>
                </div>
            </a>
            
        <ul class="nav nav-pills flex-column sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('penulis.dashboard') ? 'active' : '' }}" 
                   href="{{ route('penulis.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('penulis.posts*') ? 'active' : '' }}" 
                   href="{{ route('penulis.posts') }}">
                    <i class="fas fa-newspaper me-2"></i>Kelola Posts
                </a>
            </li>
            
                 <li class="nav-item">
                <a class="nav-link" href="{{ route('posts.index') }}" target="_blank">
                    <i class="fas fa-external-link-alt me-2"></i>Lihat Website
                </a>
            </li>
                <li class="mt-4">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
        
        <!-- Main Content -->
        <div class="penulis-content flex-grow-1">
            <!-- Header -->
            <header class="penulis-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-light sidebar-toggle me-3" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                   <h5 class="mb-0 text-white">@yield('page-title', 'Dashboard')</h5>
                </div>
                
                <div class="d-flex align-items-center">
                    <span class="me-3 text-white">Welcome, {{ auth()->user()->name }}</span>
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>{{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('penulis.profile') }}">
                                <i class="fas fa-user me-2"></i>Profile
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <main class="p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
    
    @stack('scripts')
</body>
</html>
