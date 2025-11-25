<?php $__env->startSection('title', 'Data Peserta'); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('peserta.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Tambah Peserta
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-people-fill me-2"></i>Data Peserta MHQ
                </h6>
                <div>
                    <span class="badge bg-info"><?php echo e($pesertas->total()); ?> Peserta</span>
                </div>
            </div>
            <div class="card-body">
                <?php if($pesertas->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Peserta</th>
                                    <th>Nama Lengkap</th>
                                    <th>Instansi</th>
                                    <th>Kategori</th>
                                    <th>Usia</th>
                                    <th>Jml Penilaian</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $pesertas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $peserta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($pesertas->firstItem() + $index); ?></td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo e($peserta->nomor_peserta); ?></span>
                                    </td>
                                    <td>
                                        <strong><?php echo e($peserta->nama_lengkap); ?></strong>
                                        <?php if($peserta->keterangan): ?>
                                            <br><small class="text-muted"><?php echo e($peserta->keterangan); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($peserta->instansi); ?></td>
                                    <td>
                                        <span class="badge <?php echo e($peserta->kategori == '30 Juz' ? 'bg-danger' : ($peserta->kategori == '20 Juz' ? 'bg-warning' : ($peserta->kategori == '10 Juz' ? 'bg-info' : 'bg-primary'))); ?>">
                                            <?php echo e($peserta->kategori); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($peserta->usia); ?> tahun</td>
                                    <td>
                                        <div class="text-center">
                                            <span class="badge <?php echo e($peserta->penilaians_count > 0 ? 'bg-success' : 'bg-secondary'); ?>">
                                                <?php echo e($peserta->penilaians_count); ?> / <?php echo e(App\Models\Kriteria::where('is_active', true)->count() * App\Models\Juri::where('is_active', true)->count()); ?>

                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($peserta->penilaians_count > 0): ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Dinilai
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">
                                                <i class="bi bi-clock me-1"></i>Belum Dinilai
                                            </span>
                                        <?php endif; ?>
                                        <?php if($peserta->nilai_akhir_smart): ?>
                                            <span class="badge bg-primary ms-1">
                                                <i class="bi bi-calculator me-1"></i><?php echo e(number_format($peserta->nilai_akhir_smart, 3)); ?>

                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="table-actions">
                                        <a href="<?php echo e(route('peserta.show', $peserta->id)); ?>"
                                           class="btn btn-sm btn-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('peserta.edit', $peserta->id)); ?>"
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="<?php echo e(route('peserta.destroy', $peserta->id)); ?>"
                                              method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin ingin menghapus peserta <?php echo e($peserta->nama_lengkap); ?>?')"
                                                    title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Menampilkan <?php echo e($pesertas->firstItem()); ?> - <?php echo e($pesertas->lastItem()); ?>

                            dari <?php echo e($pesertas->total()); ?> peserta
                        </div>
                        <?php echo e($pesertas->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-people fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Belum Ada Data Peserta</h4>
                        <p class="text-muted">Silakan tambahkan peserta untuk memulai proses penilaian MHQ</p>
                        <a href="<?php echo e(route('peserta.create')); ?>" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Peserta Pertama
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if($pesertas->count() > 0): ?>
<!-- Quick Stats -->
<div class="row">
    <div class="col-md-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Peserta
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($pesertas->total()); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people-fill fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Sudah Dinilai
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo e(\App\Models\Peserta::whereHas('penilaians')->count()); ?>

                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-clipboard-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Belum Dinilai
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo e(\App\Models\Peserta::whereDoesntHave('penilaians')->count()); ?>

                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Kategori Tersedia
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo e(App\Models\Peserta::distinct('kategori')->count()); ?>

                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-tags-fill fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .table-actions {
        white-space: nowrap;
    }
    .table-actions .btn {
        margin: 0 2px;
    }
    .border-left-primary { border-left: 4px solid #4e73df !important; }
    .border-left-success { border-left: 4px solid #1cc88a !important; }
    .border-left-info { border-left: 4px solid #36b9cc !important; }
    .border-left-warning { border-left: 4px solid #f6c23e !important; }
    .text-gray-300 { color: #dddfeb !important; }
    .text-gray-800 { color: #5a5c69 !important; }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inuma/Developments/laravel/tahfidz-decision-support-system/laravel-app/resources/views/peserta/index.blade.php ENDPATH**/ ?>