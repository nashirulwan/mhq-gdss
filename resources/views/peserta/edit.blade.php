@extends('layouts.app')

@section('title', 'Edit Peserta')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-warning text-white">
                <h6 class="m-0">
                    <i class="bi bi-pencil-square me-2"></i>Edit Peserta: {{ $peserta->nama_lengkap }}
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('peserta.update', $peserta->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama_lengkap" class="form-label">
                                <i class="bi bi-person me-1"></i>Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="nama_lengkap"
                                   name="nama_lengkap" value="{{ old('nama_lengkap', $peserta->nama_lengkap) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nomor_peserta" class="form-label">
                                <i class="bi bi-hash me-1"></i>Nomor Peserta <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="nomor_peserta"
                                   name="nomor_peserta" value="{{ old('nomor_peserta', $peserta->nomor_peserta) }}" required
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
                                   name="instansi" value="{{ old('instansi', $peserta->instansi) }}" required
                                   placeholder="Contoh: SDIT Al-Hikmah">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="usia" class="form-label">
                                <i class="bi bi-calendar-date me-1"></i>Usia <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" id="usia"
                                   name="usia" value="{{ old('usia', $peserta->usia) }}" required
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
                                <option value="1 Juz" {{ old('kategori', $peserta->kategori) == '1 Juz' ? 'selected' : '' }}>1 Juz</option>
                                <option value="3 Juz" {{ old('kategori', $peserta->kategori) == '3 Juz' ? 'selected' : '' }}>3 Juz</option>
                                <option value="5 Juz" {{ old('kategori', $peserta->kategori) == '5 Juz' ? 'selected' : '' }}>5 Juz</option>
                                <option value="10 Juz" {{ old('kategori', $peserta->kategori) == '10 Juz' ? 'selected' : '' }}>10 Juz</option>
                                <option value="15 Juz" {{ old('kategori', $peserta->kategori) == '15 Juz' ? 'selected' : '' }}>15 Juz</option>
                                <option value="20 Juz" {{ old('kategori', $peserta->kategori) == '20 Juz' ? 'selected' : '' }}>20 Juz</option>
                                <option value="30 Juz" {{ old('kategori', $peserta->kategori) == '30 Juz' ? 'selected' : '' }}>30 Juz</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kontak" class="form-label">
                                <i class="bi bi-telephone me-1"></i>Kontak
                            </label>
                            <input type="text" class="form-control" id="kontak"
                                   name="kontak" value="{{ old('kontak', $peserta->kontak) }}"
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
                                      name="keterangan" rows="3" placeholder="Catatan tambahan tentang peserta">{{ old('keterangan', $peserta->keterangan) }}</textarea>
                            <small class="form-text">Opsional: informasi tambahan tentang peserta</small>
                        </div>
                    </div>

                    <!-- Current Info -->
                    @if($peserta->penilaians_count > 0)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> Peserta ini sudah memiliki {{ $peserta->penilaians_count }} penilaian.
                        Perubahan data tidak akan mempengaruhi penilaian yang sudah ada.
                    </div>
                    @endif

                    <!-- Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save me-2"></i>Update Peserta
                            </button>
                            <a href="{{ route('peserta.show', $peserta->id) }}" class="btn btn-info ms-2">
                                <i class="bi bi-eye me-2"></i>Lihat Detail
                            </a>
                            <a href="{{ route('peserta.index') }}" class="btn btn-secondary ms-2">
                                <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection