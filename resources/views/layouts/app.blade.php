<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem Surat Menyurat Bank Lampung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #004080;
            --secondary-color: #f8f9fa;
            --accent-color: #0066cc;
        }

        body {
            background-color: #f5f5f5;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .navbar {
            background-color: var(--primary-color) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            padding: 0.75rem 0;
        }

        .navbar-brand, .navbar-nav .nav-link {
            color: white !important;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 20px;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
            font-size: 1.3rem;
            font-weight: 600;
            letter-spacing: -0.025em;
            position: relative;
        }

        .brand-separator {
            width: 2px;
            height: 35px;
            background: linear-gradient(180deg, transparent 0%, rgba(255,255,255,0.8) 20%, rgba(255,255,255,0.9) 50%, rgba(255,255,255,0.8) 80%, transparent 100%);
            border-radius: 1px;
            margin: 0 5px;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .brand-main {
            font-size: 1.3rem;
            font-weight: 700;
            color: white;
            margin-bottom: -2px;
        }

        .brand-sub {
            font-size: 0.75rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.85);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .navbar-brand:hover {
            color: rgba(255, 255, 255, 0.9) !important;
            transform: translateY(-1px);
        }

        .navbar-brand:hover .brand-separator {
            background: linear-gradient(180deg, transparent 0%, rgba(255,255,255,0.9) 20%, rgba(255,255,255,1) 50%, rgba(255,255,255,0.9) 80%, transparent 100%);
        }

        .logo-icon {
            display: flex;
            align-items: center;
        }

        .logo-icon img {
            height: 45px;
            width: auto;
            object-fit: contain;
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            border-radius: 5px;
        }

        .sidebar {
            background-color: white;
            min-height: calc(100vh - 80px);
            box-shadow: 2px 0 5px rgba(0,0,0,.05);
            padding-top: 20px;
        }

        .sidebar .nav-link {
            color: #333;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            background-color: var(--secondary-color);
            color: var(--accent-color);
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background-color: var(--accent-color);
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            padding: 30px;
            min-height: calc(100vh - 80px);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,.08);
            transition: all 0.3s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0,0,0,.12);
        }

        .badge {
            padding: 5px 10px;
            border-radius: 15px;
        }

        .table {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }

        .btn-primary {
            background-color: var(--accent-color);
            border: none;
            border-radius: 5px;
            padding: 8px 20px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #0052a3;
            transform: translateY(-1px);
        }

        

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .navbar-brand {
                gap: 15px;
            }
            
            .brand-main {
                font-size: 1.1rem;
            }
            
            .brand-sub {
                font-size: 0.7rem;
            }
            
            .brand-separator {
                height: 30px;
            }
            
            .logo-icon img {
                height: 35px;
            }
        }

        @media (max-width: 576px) {
            .navbar-brand {
                gap: 12px;
            }
            
            .brand-main {
                font-size: 1rem;
            }
            
            .brand-sub {
                font-size: 0.65rem;
            }
            
            .brand-separator {
                height: 25px;
                width: 1.5px;
            }
            
            .logo-icon img {
                height: 30px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <div class="logo-icon">
                    <img src="{{ asset('img/BLPUTIH.png') }}" alt="Bank Lampung">
                </div>
                <div class="brand-separator"></div>
                <div class="brand-text">
                    <span class="brand-main">Bank Lampung</span>
                    <span class="brand-sub">Surat Menyurat</span>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        </a>
                       <ul class="dropdown-menu dropdown-menu-end">
    <li><a class="dropdown-item" href="#"><i class="bi bi-building"></i> {{ auth()->user()->kantorCabang->nama_kantor }}</a></li>
    <li><hr class="dropdown-divider"></li>
    <li>
        <a class="dropdown-item" href="{{ route('profile.edit') }}">
            <i class="bi bi-person-gear"></i> Profil
            @if(!auth()->user()->password_changed)
                <span class="badge bg-danger ms-2">Wajib Update</span>
            @endif
        </a>
    </li>
    <li>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Logout</button>
        </form>
    </li>
</ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('surat.create') ? 'active' : '' }}" href="{{ route('surat.create') }}">
                            <i class="bi bi-pencil-square"></i> Kirim Surat
                        </a>
                        <a class="nav-link {{ request()->routeIs('surat.index') ? 'active' : '' }}" href="{{ route('surat.index') }}">
                            <i class="bi bi-inbox"></i> Surat Masuk
                        </a>
                        <a class="nav-link {{ request()->routeIs('surat.keluar') ? 'active' : '' }}" href="{{ route('surat.keluar') }}">
                            <i class="bi bi-send"></i> Surat Keluar
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>