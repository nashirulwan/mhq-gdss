<?php $__env->startSection('title', 'Hasil & Analisis'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-info text-white">
                <h6 class="m-0">
                    <i class="bi bi-graph-up me-2"></i>Hasil & Analisis MHQ
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="bi bi-graph-up fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Sistem Hasil & Analisis MHQ</h4>
                    <p class="text-muted">Halaman untuk menampilkan hasil perhitungan SMART dan Borda</p>

                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="h5 mb-0 font-weight-bold text-primary">
                                        <i class="bi bi-calculator me-2"></i>SMART
                                    </div>
                                    <div class="text-xs text-primary">Perhitungan Bobot</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="h5 mb-0 font-weight-bold text-success">
                                        <i class="bi bi-bar-chart me-2"></i>Borda
                                    </div>
                                    <div class="text-xs text-success">Perankingan</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="h5 mb-0 font-weight-bold text-info">
                                        <i class="bi bi-layers me-2"></i>Gabungan
                                    </div>
                                    <div class="text-xs text-info">50% + 50%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="h5 mb-0 font-weight-bold text-warning">
                                        <i class="bi bi-trophy me-2"></i>Hasil
                                    </div>
                                    <div class="text-xs text-warning">Final Ranking</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-4">
                            <a href="<?php echo e(route('penilaian.create')); ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Input Penilaian
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="<?php echo e(route('peserta.index')); ?>" class="btn btn-success">
                                <i class="bi bi-person-lines-fill me-2"></i>Data Peserta
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-info">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inuma/Developments/laravel/tahfidz-decision-support-system/laravel-app/resources/views/hasil/index_basic.blade.php ENDPATH**/ ?>