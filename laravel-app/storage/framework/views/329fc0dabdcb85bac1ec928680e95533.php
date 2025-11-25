<?php $__env->startSection('title', 'Tambah Peserta'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0">
                    <i class="bi bi-person-plus me-2"></i>Tambah Peserta Baru
                </h6>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('peserta.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama_lengkap" class="form-label">
                                <i class="bi bi-person me-1"></i>Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="nama_lengkap"
                                   name="nama_lengkap" value="<?php echo e(old('nama_lengkap')); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nomor_peserta" class="form-label">
                                <i class="bi bi-hash me-1"></i>Nomor Peserta <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="nomor_peserta"
                                   name="nomor_peserta" value="<?php echo e(old('nomor_peserta')); ?>" required
                                   placeholder="Contoh: P001">
                            <small class="form-text text-muted">Nomor unik untuk setiap peserta</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="instansi" class="form-label">
                                <i class="bi bi-building me-1"></i>Instansi <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="instansi"
                                   name="instansi" value="<?php echo e(old('instansi')); ?>" required
                                   placeholder="Contoh: SDIT Al-Hikmah">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="usia" class="form-label">
                                <i class="bi bi-calendar-date me-1"></i>Usia <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" id="usia"
                                   name="usia" value="<?php echo e(old('usia')); ?>" required
                                   min="6" max="100">
                            <small class="form-text">Tahun</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kategori" class="form-label">
                                <i class="bi bi-book me-1"></i>Kategori MHQ <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="1 Juz" <?php echo e(old('kategori') == '1 Juz' ? 'selected' : ''); ?>>1 Juz</option>
                                <option value="3 Juz" <?php echo e(old('kategori') == '3 Juz' ? 'selected' : ''); ?>>3 Juz</option>
                                <option value="5 Juz" <?php echo e(old('kategori') == '5 Juz' ? 'selected' : ''); ?>>5 Juz</option>
                                <option value="10 Juz" <?php echo e(old('kategori') == '10 Juz' ? 'selected' : ''); ?>>10 Juz</option>
                                <option value="15 Juz" <?php echo e(old('kategori') == '15 Juz' ? 'selected' : ''); ?>>15 Juz</option>
                                <option value="20 Juz" <?php echo e(old('kategori') == '20 Juz' ? 'selected' : ''); ?>>20 Juz</option>
                                <option value="30 Juz" <?php echo e(old('kategori') == '30 Juz' ? 'selected' : ''); ?>>30 Juz</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kontak" class="form-label">
                                <i class="bi bi-telephone me-1"></i>Kontak
                            </label>
                            <input type="text" class="form-control" id="kontak"
                                   name="kontak" value="<?php echo e(old('kontak')); ?>"
                                   placeholder="Nomor WhatsApp/Telepon">
                            <small class="form-text">Opsional: untuk komunikasi</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="keterangan" class="form-label">
                                <i class="bi bi-chat-text me-1"></i>Keterangan
                            </label>
                            <textarea class="form-control" id="keterangan"
                                      name="keterangan" rows="3" placeholder="Catatan tambahan tentang peserta"><?php echo e(old('keterangan')); ?></textarea>
                            <small class="form-text">Opsional: informasi tambahan tentang peserta</small>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Informasi:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Pastikan data peserta diisi dengan benar sebelum disimpan</li>
                            <li>Data peserta akan digunakan untuk proses penilaian MHQ</li>
                            <li>Kategori MHQ menentukan tingkat kesulitan hafalan yang dinilai</li>
                            <li>Nomor peserta harus unik untuk setiap peserta</li>
                        </ul>
                    </div>

                    <!-- Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Simpan Peserta
                            </button>
                            <a href="<?php echo e(route('peserta.index')); ?>" class="btn btn-secondary ms-2">
                                <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Auto-generate nomor peserta
    document.addEventListener('DOMContentLoaded', function() {
        const nomorInput = document.getElementById('nomor_peserta');
        if (nomorInput) {
            // Generate nomor peserta otomatis
            fetch('/api/peserta/next-number')
                .then(response => response.json())
                .then(data => {
                    if (data.nextNumber) {
                        nomorInput.value = data.nextNumber;
                        nomorInput.readOnly = true;
                    }
                })
                .catch(error => {
                    console.error('Error generating nomor peserta:', error);
                });
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/inuma/Developments/laravel/tahfidz-decision-support-system/laravel-app/resources/views/peserta/create.blade.php ENDPATH**/ ?>