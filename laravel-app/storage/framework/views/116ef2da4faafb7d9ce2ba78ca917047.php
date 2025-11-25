<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Peserta
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['total_peserta']); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people-fill fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2 stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Juri Aktif
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['total_juri']); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-person-badge fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2 stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Kriteria
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['total_kriteria']); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-list-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2 stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Penilaian
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['total_penilaian']); ?></div>
                        <div class="text-xs text-muted">/ <?php echo e($stats['total_peserta'] * $stats['total_juri'] * $stats['total_kriteria']); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-clipboard-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Status -->
<div class="row">
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Status Perhitungan SMART</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Completeness</span>
                        <span class="badge <?php echo e($smartStatus['completion_percentage'] >= 100 ? 'bg-success' : 'bg-warning'); ?>">
                            <?php echo e(round($smartStatus['completion_percentage'], 1)); ?>%
                        </span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar <?php echo e($smartStatus['completion_percentage'] >= 100 ? 'bg-success' : 'bg-warning'); ?>"
                             style="width: <?php echo e($smartStatus['completion_percentage']); ?>%"></div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-4">
                        <small class="text-muted">Peserta</small><br>
                        <strong><?php echo e($smartStatus['pesertas']); ?></strong>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">Kriteria</small><br>
                        <strong><?php echo e($smartStatus['kriterias']); ?></strong>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">Penilaian</small><br>
                        <strong><?php echo e($smartStatus['actual_penilaians']); ?>/<?php echo e($smartStatus['expected_penilaians']); ?></strong>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <?php if($smartStatus['valid']): ?>
                        <span class="badge bg-success">✓ Valid untuk SMART</span>
                    <?php else: ?>
                        <span class="badge bg-warning">⚠ Data belum lengkap</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Status Perhitungan Borda</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Completeness</span>
                        <span class="badge <?php echo e($bordaStatus['completion_percentage'] >= 100 ? 'bg-success' : 'bg-warning'); ?>">
                            <?php echo e(round($bordaStatus['completion_percentage'], 1)); ?>%
                        </span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar <?php echo e($bordaStatus['completion_percentage'] >= 100 ? 'bg-success' : 'bg-warning'); ?>"
                             style="width: <?php echo e($bordaStatus['completion_percentage']); ?>%"></div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-4">
                        <small class="text-muted">Peserta</small><br>
                        <strong><?php echo e($bordaStatus['pesertas']); ?></strong>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">Juri</small><br>
                        <strong><?php echo e($bordaStatus['juris']); ?></strong>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">Penilaian</small><br>
                        <strong><?php echo e($bordaStatus['actual_penilaians']); ?>/<?php echo e($bordaStatus['expected_penilaians']); ?></strong>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <?php if($bordaStatus['valid']): ?>
                        <span class="badge bg-success">✓ Valid untuk Borda</span>
                    <?php else: ?>
                        <span class="badge bg-warning">⚠ Data belum lengkap</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Ranking (if available) -->
<?php if($topRanking->isNotEmpty()): ?>
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="bi bi-trophy-fill me-2"></i>Peringkat Sementara (SMART)
                </h6>
                <a href="<?php echo e(route('hasil.index')); ?>" class="btn btn-sm btn-outline-info">Lihat Semua →</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php $__currentLoopData = $topRanking; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $peserta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-4">
                        <div class="text-center p-3 <?php echo e($index === 0 ? 'bg-warning bg-opacity-10' : ($index === 1 ? 'bg-secondary bg-opacity-10' : 'bg-light')); ?>">
                            <div class="display-4 <?php echo e($index === 0 ? 'text-warning' : ($index === 1 ? 'text-secondary' : 'text-muted')); ?>">
                                <?php echo e($index === 1 ? 2 : ($index === 0 ? 1 : 3)); ?>

                            </div>
                            <h6 class="mt-2 mb-1"><?php echo e($peserta->nama_lengkap); ?></h6>
                            <small class="text-muted"><?php echo e($peserta->instansi); ?></small><br>
                            <strong class="text-primary"><?php echo e(number_format($peserta->nilai_akhir_smart, 3)); ?></strong>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Recent Activities -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-secondary">
                    <i class="bi bi-clock-history me-2"></i>Aktivitas Penilaian Terbaru
                </h6>
            </div>
            <div class="card-body">
                <?php if($recentPenilaians->isNotEmpty()): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Juri</th>
                                    <th>Peserta</th>
                                    <th>Kriteria</th>
                                    <th>Nilai</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $recentPenilaians; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $penilaian): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><small><?php echo e($penilaian->created_at->format('d M H:i')); ?></small></td>
                                    <td><?php echo e($penilaian->juri->nama_lengkap); ?></td>
                                    <td><?php echo e($penilaian->peserta->nama_lengkap); ?></td>
                                    <td><span class="badge bg-info"><?php echo e($penilaian->kriteria->nama_kriteria); ?></span></td>
                                    <td><strong><?php echo e($penilaian->nilai); ?></strong></td>
                                    <td><small class="text-muted"><?php echo e($penilaian->catatan ?? '-'); ?></small></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-clipboard-x fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada aktivitas penilaian</p>
                        <a href="<?php echo e(route('penilaian.create')); ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>Mulai Penilaian
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="<?php echo e(route('peserta.create')); ?>" class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-person-plus me-2"></i>Tambah Peserta
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="<?php echo e(route('penilaian.create')); ?>" class="btn btn-outline-success btn-sm w-100">
                            <i class="bi bi-clipboard-plus me-2"></i>Input Penilaian
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="<?php echo e(route('hasil.index')); ?>" class="btn btn-outline-info btn-sm w-100">
                            <i class="bi bi-graph-up me-2"></i>Lihat Hasil
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="#" class="btn btn-outline-warning btn-sm w-100" disabled>
                            <i class="bi bi-table me-2"></i>Matriks SMART
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .border-left-primary { border-left: 4px solid #4e73df !important; }
    .border-left-success { border-left: 4px solid #1cc88a !important; }
    .border-left-info { border-left: 4px solid #36b9cc !important; }
    .border-left-warning { border-left: 4px solid #f6c23e !important; }
    .text-gray-300 { color: #dddfeb !important; }
    .text-gray-800 { color: #5a5c69 !important; }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inuma/Developments/laravel/tahfidz-decision-support-system/laravel-app/resources/views/dashboard/index.blade.php ENDPATH**/ ?>