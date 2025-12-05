@extends('layouts.app')

@section('title', 'Dashboard Juri')

@section('content')
<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-gradient-primary text-white">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1">
                                <i class="bi bi-hand-thumbs-up me-2"></i>
                                Selamat Datang, {{ auth()->user()->name }}!
                            </h4>
                            <p class="mb-0 opacity-75">
                                <i class="bi bi-trophy me-1"></i>
                                Dashboard Penilaian Musabaqah Hifdzil Qur'an 2025
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="badge bg-white text-primary fs-6">
                                <i class="bi bi-shield-check me-1"></i>Juri
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

      <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Total Peserta -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="bi bi-people-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">{{ $stats['total_peserta'] }}</h3>
                    <p class="text-muted mb-0 small">Total Peserta</p>
                </div>
            </div>
        </div>

        <!-- Completed Penilaian -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="bi bi-check-circle-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">{{ $stats['completed_penilaian'] }}</h3>
                    <p class="text-muted mb-0 small">Slesai Dinilai</p>
                </div>
            </div>
        </div>

        <!-- Pending Penilaian -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="bi bi-clock-history fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">{{ $stats['pending_penilaian'] }}</h3>
                    <p class="text-muted mb-0 small">Belum Dinilai</p>
                </div>
            </div>
        </div>

        <!-- Progress -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="bi bi-graph-up fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">{{ $completionPercentage }}%</h3>
                    <p class="text-muted mb-0 small">Progress</p>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-info" role="progressbar"
                             style="width: {{ $completionPercentage }}%"
                             aria-valuenow="{{ $completionPercentage }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-lightning-fill text-warning me-2"></i>
                        Quick Actions
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="{{ route('juri.pesertas') }}" class="btn btn-outline-primary btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-people fa-2x mb-2"></i>
                                <span>Lihat Semua Peserta</span>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-outline-success btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center" onclick="window.location.href='#continue-evaluation'">
                                <i class="bi bi-play-circle fa-2x mb-2"></i>
                                <span>Lanjutkan Penilaian</span>
                            </button>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('juri.hasil.index') }}" class="btn btn-outline-info btn-lg w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-graph-up-arrow fa-2x mb-2"></i>
                                <span>Lihat Hasil</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Peserta & Recent Activity -->
    <div class="row">
        <!-- Daftar Peserta -->
        <div class="col-lg-8 mb-4" id="continue-evaluation">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-list-check me-2"></i>
                        Daftar Peserta untuk Penilaian
                    </h5>
                </div>
                <div class="card-body">
                    @if($assignedPesertas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Peserta</th>
                                        <th>Instansi</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assignedPesertas as $index => $peserta)
                                    @php
                                    $completedCount = $peserta->penilaians->whereNotNull('nilai')->count();
                                    $totalCount = $peserta->penilaians->count();
                                    $isCompleted = $totalCount > 0 && $completedCount === $totalCount;
                                    $isInProgress = $completedCount > 0 && $completedCount < $totalCount;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $peserta->nama_lengkap }}</strong>
                                        </td>
                                        <td>{{ $peserta->instansi ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $peserta->kategori ?? '-' }}</span>
                                        </td>
                                        <td>
                                            @if($isCompleted)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Selesai
                                                </span>
                                            @elseif($isInProgress)
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-clock me-1"></i>{{ $completedCount }}/{{ $totalCount }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-circle me-1"></i>Belum
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button"
                                                        class="btn btn-sm btn-info"
                                                        onclick="showPesertaDetail({{ $peserta->id }})"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#pesertaDetailModal">
                                                    <i class="bi bi-eye me-1"></i>Detail
                                                </button>
                                                @if(!$isCompleted)
                                                <a href="{{ route('juri.evaluate', $peserta->id) }}"
                                                   class="btn btn-sm btn-primary">
                                                    <i class="bi bi-pencil-square me-1"></i>Nilai
                                                </a>
                                                @else
                                                <button class="btn btn-sm btn-success" disabled>
                                                    <i class="bi bi-check2-circle me-1"></i>Selesai
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-people fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Peserta</h5>
                            <p class="text-muted">Hubungi admin untuk menambahkan peserta ke kompetisi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Aktivitas Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentPenilaians->count() > 0)
                        <div class="timeline">
                            @foreach($recentPenilaians as $penilaian)
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    @if($penilaian->nilai >= 85)
                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 12px;">
                                            {{ $penilaian->nilai }}
                                        </div>
                                    @elseif($penilaian->nilai >= 70)
                                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 12px;">
                                            {{ $penilaian->nilai }}
                                        </div>
                                    @else
                                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 12px;">
                                            {{ $penilaian->nilai }}
                                        </div>
                                    @endif
                                </div>
                                <div class="ms-3">
                                    <div class="fw-bold small">{{ $penilaian->peserta->nama_lengkap }}</div>
                                    <div class="text-muted small">{{ $penilaian->kriteria->nama_kriteria }}</div>
                                    <div class="text-muted small">{{ $penilaian->updated_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-clock fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada aktivitas penilaian</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Peserta -->
<div class="modal fade" id="pesertaDetailModal" tabindex="-1" aria-labelledby="pesertaDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pesertaDetailModalLabel">
                    <i class="bi bi-person-badge me-2"></i>Detail Peserta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="pesertaDetailContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data peserta...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnEvaluateFromDetail" style="display: none;">
                    <i class="bi bi-pencil-square me-1"></i>Nilai Peserta Ini
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showPesertaDetail(pesertaId) {
    // Show loading state
    document.getElementById('pesertaDetailContent').innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat data peserta...</p>
        </div>
    `;

    // Fetch peserta detail via AJAX
    fetch(`{{ route('juri.peserta.detail', 'peserta_id') }}`.replace('peserta_id', pesertaId))
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                document.getElementById('pesertaDetailContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        ${data.error}
                    </div>
                `;
            } else {
                document.getElementById('pesertaDetailContent').innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Informasi Dasar</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="30%"><strong>Nama Lengkap:</strong></td>
                                    <td>${data.nama_lengkap}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nomor Peserta:</strong></td>
                                    <td>${data.nomor_peserta}</td>
                                </tr>
                                <tr>
                                    <td><strong>Instansi:</strong></td>
                                    <td>${data.instansi}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kategori:</strong></td>
                                    <td><span class="badge bg-info">${data.kategori}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Usia:</strong></td>
                                    <td>${data.usia} tahun</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success mb-3">Status Penilaian</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="30%"><strong>Status:</strong></td>
                                    <td><span class="badge bg-${getStatusBadgeClass(data.status)}">${data.status}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Progress:</strong></td>
                                    <td>${data.progress}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nilai Akhir:</strong></td>
                                    <td><strong class="text-primary">${data.nilai || 'Belum ada'}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    ${data.keterangan ? `
                    <div class="mt-3">
                        <h6 class="text-warning mb-2">Keterangan</h6>
                        <p class="text-muted">${data.keterangan}</p>
                    </div>
                    ` : ''}
                `;

                // Show/Hide Evaluate button based on status
                const evaluateBtn = document.getElementById('btnEvaluateFromDetail');
                if (data.status !== 'Selesai') {
                    evaluateBtn.style.display = 'block';
                    evaluateBtn.onclick = function() {
                        window.location.href = `{{ route('juri.evaluate', 'peserta_id') }}`.replace('peserta_id', pesertaId);
                    };
                } else {
                    evaluateBtn.style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('pesertaDetailContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Terjadi kesalahan saat memuat data peserta. Silakan coba lagi.
                </div>
            `;
        });
}

function getStatusBadgeClass(status) {
    switch(status) {
        case 'Selesai': return 'success';
        case 'Sedang Dinilai': return 'warning';
        default: return 'secondary';
    }
}
</script>
@endpush