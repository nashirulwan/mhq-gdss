<?php $__env->startSection('title', 'Data Penilaian'); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('penilaian.create')); ?>" class="btn btn-success">
        <i class="bi bi-plus-circle me-2"></i>Input Penilaian
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="bi bi-clipboard-data me-2"></i>Data Penilaian MHQ
                </h6>
                <div>
                    <span class="badge bg-info"><?php echo e($penilaians->total()); ?> Penilaian</span>
                </div>
            </div>
            <div class="card-body">
                <?php if($penilaians->count() > 0): ?>
                    <!-- Quick Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select form-select-sm" id="filterJuri">
                                <option value="">Semua Juri</option>
                                <?php $__currentLoopData = $juris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $juri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($juri->id); ?>"><?php echo e($juri->nama_lengkap); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-sm" id="filterPeserta">
                                <option value="">Semua Peserta</option>
                                <?php $__currentLoopData = $pesertas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $peserta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($peserta->id); ?>"><?php echo e($peserta->nama_lengkap); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-sm" id="filterKriteria">
                                <option value="">Semua Kriteria</option>
                                <?php $__currentLoopData = $kriterias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kriteria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($kriteria->id); ?>"><?php echo e($kriteria->nama_kriteria); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-sm btn-outline-primary" onclick="applyFilters()">
                                <i class="bi bi-funnel me-1"></i>Terapkan Filter
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="clearFilters()">
                                <i class="bi bi-x-circle me-1"></i>Reset
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped datatable" id="penilaianTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Juri</th>
                                    <th>Peserta</th>
                                    <th>Kriteria</th>
                                    <th>Nilai</th>
                                    <th>Bobot</th>
                                    <th>Nilai Terbobot</th>
                                    <th>Catatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $penilaians; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $penilaian): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr data-juri="<?php echo e($penilaian->juri_id); ?>" data-peserta="<?php echo e($penilaian->peserta_id); ?>" data-kriteria="<?php echo e($penilaian->kriteria_id); ?>">
                                    <td>
                                        <small><?php echo e($penilaian->created_at->format('d M Y')); ?></small><br>
                                        <small class="text-muted"><?php echo e($penilaian->created_at->format('H:i')); ?></small>
                                    </td>
                                    <td>
                                        <strong><?php echo e($penilaian->juri->nama_lengkap); ?></strong>
                                    </td>
                                    <td>
                                        <strong><?php echo e($penilaian->peserta->nama_lengkap); ?></strong><br>
                                        <small class="text-muted"><?php echo e($penilaian->peserta->nomor_peserta); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?php echo e($penilaian->kriteria->nama_kriteria); ?></span>
                                        <br><small class="text-muted">Bobot: <?php echo e($penilaian->kriteria->bobot); ?></small>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <strong class="<?php echo e($penilaian->nilai >= ($penilaian->kriteria->max * 0.8) ? 'text-success' : ($penilaian->nilai <= ($penilaian->kriteria->max * 0.6) ? 'text-danger' : 'text-warning')); ?>">
                                                <?php echo e($penilaian->nilai); ?>

                                            </strong>
                                            <br><small class="text-muted">(<?php echo e($penilaian->kriteria->min); ?>-<?php echo e($penilaian->kriteria->max); ?>)</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="badge bg-primary"><?php echo e($penilaian->kriteria->bobot); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <strong class="text-primary"><?php echo e(number_format($penilaian->nilai * $penilaian->kriteria->bobot / 100, 2)); ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?php echo e($penilaian->catatan ? Str::limit($penilaian->catatan, 30) : '-'); ?></small>
                                    </td>
                                    <td class="table-actions">
                                        <button class="btn btn-sm btn-info" title="Detail" onclick="showDetails(<?php echo e($penilaian->id); ?>)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <a href="<?php echo e(route('penilaian.edit', $penilaian->id)); ?>" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="<?php echo e(route('penilaian.destroy', $penilaian->id)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                                    onclick="return confirm('Yakin ingin menghapus penilaian ini?')">
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
                            Menampilkan <?php echo e($penilaians->firstItem()); ?> - <?php echo e($penilaians->lastItem()); ?>

                            dari <?php echo e($penilaians->total()); ?> penilaian
                        </div>
                        <?php echo e($penilaians->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-clipboard-x fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Belum Ada Data Penilaian</h4>
                        <p class="text-muted">Silakan input penilaian untuk memulai proses penilaian MHQ</p>
                        <a href="<?php echo e(route('penilaian.create')); ?>" class="btn btn-success">
                            <i class="bi bi-plus-circle me-2"></i>Input Penilaian Pertama
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<?php if($penilaians->count() > 0): ?>
<div class="row">
    <div class="col-md-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Rata-rata Nilai
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo e(number_format($penilaians->avg('nilai'), 2)); ?>

                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-calculator fa-2x text-gray-300"></i>
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
                            Penilaian Hari Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo e(\App\Models\Penilaian::whereDate('created_at', today())->count()); ?>

                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-calendar-check fa-2x text-gray-300"></i>
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
                            Juri Aktif
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo e($penilaians->pluck('juri_id')->unique()->count()); ?>

                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people-fill fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Peserta Dinilai
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo e($penilaians->pluck('peserta_id')->unique()->count()); ?>

                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-person-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Penilaian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .table-actions {
        white-space: nowrap;
    }
    .table-actions .btn {
        margin: 0 2px;
    }
    .border-left-success { border-left: 4px solid #1cc88a !important; }
    .border-left-info { border-left: 4px solid #36b9cc !important; }
    .border-left-warning { border-left: 4px solid #f6c23e !important; }
    .border-left-primary { border-left: 4px solid #4e73df !important; }
    .text-gray-300 { color: #dddfeb !important; }
    .text-gray-800 { color: #5a5c69 !important; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function showDetails(id) {
    fetch(`/api/penilaian/${id}`)
        .then(response => response.json())
        .then(data => {
            const content = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Penilaian</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Tanggal:</strong></td><td>${data.created_at}</td></tr>
                            <tr><td><strong>Juri:</strong></td><td>${data.juri.nama_lengkap}</td></tr>
                            <tr><td><strong>Peserta:</strong></td><td>${data.peserta.nama_lengkap}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Detail Kriteria</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Kriteria:</strong></td><td>${data.kriteria.nama_kriteria}</td></tr>
                            <tr><td><strong>Bobot:</strong></td><td>${data.kriteria.bobot}</td></tr>
                            <tr><td><strong>Rentang:</strong></td><td>${data.kriteria.min} - ${data.kriteria.max}</td></tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Hasil Penilaian</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Nilai:</strong></td><td class="text-primary">${data.nilai}</td></tr>
                            <tr><td><strong>Nilai Terbobot:</strong></td><td class="text-success">${(data.nilai * data.kriteria.bobot / 100).toFixed(2)}</td></tr>
                            <tr><td><strong>Catatan:</strong></td><td>${data.catatan || '-'}</td></tr>
                        </table>
                    </div>
                </div>
            `;
            document.getElementById('detailContent').innerHTML = content;
            new bootstrap.Modal(document.getElementById('detailModal')).show();
        })
        .catch(error => {
            console.error('Error fetching detail:', error);
        });
}

function applyFilters() {
    const juriFilter = document.getElementById('filterJuri').value;
    const pesertaFilter = document.getElementById('filterPeserta').value;
    const kriteriaFilter = document.getElementById('filterKriteria').value;

    const rows = document.querySelectorAll('#penilaianTable tbody tr');
    let visibleCount = 0;

    rows.forEach(row => {
        const showRow = (!juriFilter || row.dataset.juri == juriFilter) &&
                       (!pesertaFilter || row.dataset.peserta == pesertaFilter) &&
                       (!kriteriaFilter || row.dataset.kriteria == kriteriaFilter);

        row.style.display = showRow ? '' : 'none';
        if (showRow) visibleCount++;
    });

    // Update pagination info or show message if no results
    const tbody = document.querySelector('#penilaianTable tbody');
    if (visibleCount === 0) {
        if (!document.getElementById('noResultsMessage')) {
            const message = document.createElement('tr');
            message.id = 'noResultsMessage';
            message.innerHTML = '<td colspan="9" class="text-center text-muted py-4">Tidak ada data yang cocok dengan filter yang dipilih</td>';
            tbody.appendChild(message);
        }
    } else {
        const message = document.getElementById('noResultsMessage');
        if (message) message.remove();
    }
}

function clearFilters() {
    document.getElementById('filterJuri').value = '';
    document.getElementById('filterPeserta').value = '';
    document.getElementById('filterKriteria').value = '';

    const rows = document.querySelectorAll('#penilaianTable tbody tr');
    rows.forEach(row => {
        row.style.display = '';
    });

    const message = document.getElementById('noResultsMessage');
    if (message) message.remove();
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inuma/Developments/laravel/tahfidz-decision-support-system/laravel-app/resources/views/penilaian/index.blade.php ENDPATH**/ ?>