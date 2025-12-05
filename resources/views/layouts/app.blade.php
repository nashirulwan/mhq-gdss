<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Penilaian MHQ')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <style>
        .navbar-brand {
            font-weight: bold;
        }
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
        }
        .sidebar .nav-link {
            color: #495057;
            padding: 0.75rem 1rem;
        }
        .sidebar .nav-link:hover {
            color: #007bff;
            background-color: #e9ecef;
        }
        .sidebar .nav-link.active {
            color: #007bff;
            background-color: #e3f2fd;
            font-weight: 600;
        }
        .main-content {
            padding: 2rem;
        }
        .stats-card {
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-2px);
        }
        .table-actions {
            white-space: nowrap;
        }
        .nilai-input {
            width: 80px;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                min-height: auto;
            }
            .main-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <!-- Left side: Brand + Admin Info -->
            <div class="d-flex align-items-center">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <i class="bi bi-award-fill me-2"></i>Sistem Penilaian MHQ
                </a>

                @auth
                <span class="text-white ms-3 d-none d-md-inline-block">
                    <i class="bi bi-person-circle me-1"></i>
                    <small>{{ ucfirst(auth()->user()->role) }}</small>
                </span>
                @endauth
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- User Info & Logout - Right side -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><h6 class="dropdown-header">
                                <i class="bi bi-person me-1"></i>
                                {{ auth()->user()->name }}
                            </h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><span class="dropdown-item-text">
                                <small class="text-muted">
                                    <i class="bi bi-envelope me-1"></i>{{ auth()->user()->email }}
                                </small>
                            </span></li>
                            <li><span class="dropdown-item-text">
                                <small class="text-muted">
                                    <i class="bi bi-shield-check me-1"></i>{{ ucfirst(auth()->user()->role) ?? 'User' }}
                                </small>
                            </span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>

              </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    @auth
                    <ul class="nav flex-column">
                        <!-- Admin Menu -->
                        @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                                <i class="bi bi-people-fill me-2"></i> Manage User
                            </a>
                        </li>
                        @endif

                        <!-- Input Penilaian Menu -->
                        @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('penilaian.*') ? 'active' : '' }}" href="{{ route('penilaian.index') }}">
                                <i class="bi bi-clipboard-data me-2"></i> Input Penilaian
                            </a>
                        </li>
                        @endif

                        <!-- Hasil Analisis Menu -->
                        @if(auth()->user()->isAdmin() || auth()->user()->isJuri())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hasil.*') ? 'active' : '' }}" href="{{ route('hasil.index') }}">
                                <i class="bi bi-graph-up me-2"></i> Hasil & Analisis
                            </a>
                        </li>
                        @endif

                        <!-- Data Peserta Menu - hanya untuk admin -->
                        @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('peserta.index') ? 'active' : '' }}" href="{{ route('peserta.index') }}">
                                <i class="bi bi-person-lines-fill me-2"></i> Data Peserta
                            </a>
                        </li>
                        @endif

                        <!-- Data Juri Menu - hanya untuk admin -->
                        @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('juri.index') ? 'active' : '' }}" href="{{ route('juri.index') }}">
                                <i class="bi bi-gavel me-2"></i> Data Juri
                            </a>
                        </li>
                        @endif
                    </ul>
                    @endauth

                    <hr>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Dashboard</span>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <!-- Dashboard Juri -->
                        @if(auth()->user()->isAdmin() || auth()->user()->isJuri())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('juri.dashboard') ? 'active' : '' }}" href="{{ route('juri.dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard Juri
                            </a>
                        </li>
                        @endif

                        <!-- Dashboard Peserta -->
                        @if(auth()->user()->isAdmin() || auth()->user()->isPeserta())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('peserta.dashboard') ? 'active' : '' }}" href="{{ route('peserta.dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard Peserta
                            </a>
                        </li>
                        @endif
                    </ul>

                    <hr>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Pengguna</span>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <span class="nav-link text-primary">
                                <small>
                                    <i class="bi bi-person-circle me-1"></i>
                                    <strong>{{ auth()->user()->name }}</strong><br>
                                    <span class="text-muted">{{ ucfirst(auth()->user()->role) }}</span>
                                </small>
                            </span>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="px-3">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content Area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page Header -->
                @if(request()->routeIs('dashboard'))
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Dashboard</h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group me-2">
                                <a href="{{ route('hasil.index') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-graph-up me-1"></i> Lihat Hasil
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">@yield('title', 'Page')</h1>
                        @yield('page-actions')
                    </div>
                @endif

                <!-- Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    @yield('scripts')

    <script>
        // Initialize DataTables
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
                }
            });
        });
    </script>
</body>
</html>