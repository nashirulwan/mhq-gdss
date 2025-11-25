<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Sistem Penilaian MHQ'); ?> - GDSS</title>

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
            <a class="navbar-brand" href="<?php echo e(route('dashboard')); ?>">
                <i class="bi bi-award-fill me-2"></i>Sistem Penilaian MHQ
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('dashboard')); ?>">
                            <i class="bi bi-house-fill me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('peserta.index')); ?>">
                            <i class="bi bi-people-fill me-1"></i> Peserta
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('penilaian.index')); ?>">
                            <i class="bi bi-clipboard-check-fill me-1"></i> Penilaian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('hasil.index')); ?>">
                            <i class="bi bi-graph-up me-1"></i> Hasil
                        </a>
                    </li>
                </ul>
                <span class="navbar-text">
                    <small class="text-light">GDSS - SMART & Borda Method</small>
                </span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('peserta.*') ? 'active' : ''); ?>" href="<?php echo e(route('peserta.index')); ?>">
                                <i class="bi bi-person-lines-fill me-2"></i> Data Peserta
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('penilaian.*') ? 'active' : ''); ?>" href="<?php echo e(route('penilaian.index')); ?>">
                                <i class="bi bi-clipboard-data me-2"></i> Input Penilaian
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('hasil.*') ? 'active' : ''); ?>" href="<?php echo e(route('hasil.index')); ?>">
                                <i class="bi bi-bar-chart-fill me-2"></i> Hasil & Analisis
                            </a>
                        </li>
                    </ul>

                    <hr>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Informasi</span>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <span class="nav-link text-muted">
                                <small>
                                    <i class="bi bi-info-circle me-1"></i>
                                    Metode: SMART + Borda<br>
                                    Kriteria: <?php echo e(App\Models\Kriteria::where('is_active', true)->count()); ?><br>
                                    Juri Aktif: <?php echo e(App\Models\Juri::where('is_active', true)->count()); ?>

                                </small>
                            </span>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content Area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Flash Messages -->
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Page Header -->
                <?php if(request()->routeIs('dashboard')): ?>
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Dashboard</h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group me-2">
                                <a href="<?php echo e(route('hasil.index')); ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-graph-up me-1"></i> Lihat Hasil
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2"><?php echo $__env->yieldContent('title', 'Page'); ?></h1>
                        <?php echo $__env->yieldContent('page-actions'); ?>
                    </div>
                <?php endif; ?>

                <!-- Content -->
                <?php echo $__env->yieldContent('content'); ?>
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

    <?php echo $__env->yieldContent('scripts'); ?>

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
</html><?php /**PATH /home/inuma/Developments/laravel/tahfidz-decision-support-system/laravel-app/resources/views/layouts/app.blade.php ENDPATH**/ ?>