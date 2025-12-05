@extends('layouts.app')

@section('title', 'Daftar Peserta')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('juri.dashboard') }}" class="text-decoration-none">
                            <i class="bi bi-house-door me-1"></i>Dashboard Juri
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Daftar Peserta</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-gradient-primary text-white">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1">
                                <i class="bi bi-people-fill me-2"></i>
                                Daftar Semua Peserta
                            </h4>
                            <p class="mb-0 opacity-75">
                                Total {{ $pesertas->total() }} peserta untuk penilaian MHQ
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <form method="GET" action="{{ route('juri.pesertas') }}" class="d-flex">
                                <input type="text" name="search" class="form-control me-2"
                                       placeholder="Cari peserta..."
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-light">
                                    <i class="bi bi-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="bi bi-people-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">{{ $pesertas->total() }}</h3>
                    <p class="text-muted mb-0 small">Total Peserta</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="bi bi-check-circle-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">
                        {{ $pesertas->filter(function($p) {
                            return $p->penilaians->whereNotNull('nilai')->count() >= \App\Models\Kriteria::where('is_active', true)->count();
                        })->count() }}
                    </h3>
                    <p class="text-muted mb-0 small">Selesai Dinilai</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="bi bi-clock-history fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">
                        {{ $pesertas->filter(function($p) {
                            return $p->penilaians->whereNotNull('nilai')->count() > 0 &&
                                   $p->penilaians->whereNotNull('nilai')->count() < \App\Models\Kriteria::where('is_active', true)->count();
                        })->count() }}
                    </h3>
                    <p class="text-muted mb-0 small">Sedang Dinilai</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="bi bi-tags-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">
                        {{ $pesertas->pluck('kategori')->unique()->count() }}
                    </h3>
                    <p class="text-muted mb-0 small">Kategori</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Peserta Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">
                                <i class="bi bi-list-check me-2"></i>
                                Daftar Peserta MHQ
                            </h5>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <small class="text-muted">
                                Menampilkan {{ $pesertas->firstItem() }}-{{ $pesertas->lastItem() }} dari {{ $pesertas->total() }} peserta
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($pesertas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nomor</th>
                                        <th>Nama Lengkap</th>
                                        <th>Instansi</th>
                                        <th>Kategori</th>
                                        <th>Usia</th>
                                        <th>Progress</th>
                                        <th>Nilai</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $totalKriteria = \App\Models\Kriteria::where('is_active', true)->count();
                                    @endphp
                                    @foreach($pesertas as $index => $peserta)
                                    @php
                                    $completedCount = $peserta->penilaians->whereNotNull('nilai')->count();
                                    $isCompleted = $completedCount >= $totalKriteria;
                                    $isInProgress = $completedCount > 0 && $completedCount < $totalKriteria;
                                    $progress = $totalKriteria > 0 ? ($completedCount / $totalKriteria) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $pesertas->firstItem() + $index }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $peserta->nomor_peserta }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $peserta->nama_lengkap }}</strong>
                                            @if($peserta->keterangan)
                                                <br><small class="text-muted">{{ $peserta->keterangan }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $peserta->instansi ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $peserta->kategori ?? '-' }}</span>
                                        </td>
                                        <td>{{ $peserta->usia }} tahun</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                    <div class="progress-bar bg-{{ $isCompleted ? 'success' : ($isInProgress ? 'warning' : 'secondary') }}"
                                                         role="progressbar"
                                                         style="width: {{ $progress }}%"
                                                         aria-valuenow="{{ $progress }}"
                                                         aria-valuemin="0"
                                                         aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ round($progress) }}%</small>
                                            </div>
                                            <small class="text-muted">{{ $completedCount }}/{{ $totalKriteria }}</small>
                                        </td>
                                        <td>
                                            @if($isCompleted)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Selesai
                                                </span>
                                                @if($peserta->nilai_akhir_smart)
                                                    <br><small class="text-muted">{{ number_format($peserta->nilai_akhir_smart, 3) }}</small>
                                                @endif
                                            @elseif($isInProgress)
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-clock me-1"></i>{{ $completedCount }}/{{ $totalKriteria }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-circle me-1"></i>Belum
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-info"
                                                        data-bs-toggle="tooltip"
                                                        title="Detail Peserta"
                                                        onclick="showDetail({{ $peserta->id }})">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <a href="{{ route('juri.evaluate', $peserta->id) }}"
                                                   class="btn btn-{{ $isCompleted ? 'outline-success' : 'primary' }}"
                                                   data-bs-toggle="tooltip"
                                                   title="{{ $isCompleted ? 'Lihat/Edit Penilaian' : 'Mulai Penilaian' }}">
                                                    <i class="bi bi-{{ $isCompleted ? 'pencil-square' : 'play-circle' }}"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                {{ $pesertas->firstItem() }} - {{ $pesertas->lastItem() }} dari {{ $pesertas->total() }} peserta
                            </div>
                            {{ $pesertas->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-people fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Peserta</h5>
                            <p class="text-muted">
                                @if(request('search'))
                                    Tidak ditemukan peserta dengan kata kunci "{{ request('search') }}"
                                @else
                                    Hubungi admin untuk menambahkan peserta ke kompetisi
                                @endif
                            </p>
                            @if(request('search'))
                                <a href="{{ route('juri.pesertas') }}" class="btn btn-primary">
                                    <i class="bi bi-arrow-left me-1"></i>Reset Pencarian
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Peserta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent">
                <!-- Content loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnEvaluateFromModal">
                    <i class="bi bi-pencil-square me-1"></i>Evaluasi
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Show detail function
function showDetail(pesertaId) {
    // Simulate loading detail via AJAX (implement actual AJAX if needed)
    fetch(`/juri/pesertas/${pesertaId}/detail`)
        .then(response => response.json())
        .then(data => {
            const detailContent = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>Nama Lengkap:</strong> ${data.nama_lengkap}<br>
                        <strong>Nomor Peserta:</strong> ${data.nomor_peserta}<br>
                        <strong>Instansi:</strong> ${data.instansi || '-'}<br>
                        <strong>Kategori:</strong> ${data.kategori || '-'}<br>
                        <strong>Usia:</strong> ${data.usia} tahun
                    </div>
                    <div class="col-md-6">
                        <strong>Kontak:</strong> ${data.kontak || '-'}<br>
                        <strong>Status:</strong> ${data.status}<br>
                        <strong>Progress:</strong> ${data.progress}<br>
                        <strong>Nilai:</strong> ${data.nilai || '-'}
                    </div>
                </div>
                ${data.keterangan ? `<hr><strong>Keterangan:</strong><br>${data.keterangan}` : ''}
            `;

            document.getElementById('detailContent').innerHTML = detailContent;
            document.getElementById('btnEvaluateFromModal').onclick = function() {
                window.location.href = `/juri/pesertas/${pesertaId}/evaluate`;
            };

            new bootstrap.Modal(document.getElementById('detailModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('detailContent').innerHTML =
                '<p class="text-danger">Gagal memuat detail peserta</p>';
            new bootstrap.Modal(document.getElementById('detailModal')).show();
        });
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush