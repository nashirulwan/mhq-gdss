@extends('layouts.app')

@section('title', 'Riwayat Penilaian')

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
                    <li class="breadcrumb-item active">Riwayat Penilaian</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-gradient-success text-white">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1">
                                <i class="bi bi-clock-history me-2"></i>
                                Riwayat Penilaian
                            </h4>
                            <p class="mb-0 opacity-75">
                                Histori semua penilaian yang telah Anda lakukan
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="badge bg-white text-success fs-6">
                                <i class="bi bi-clipboard-check me-1"></i>
                                {{ $penilaians->total() }} Penilaian
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('juri.history') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Peserta</label>
                                <select name="peserta_id" class="form-select">
                                    <option value="">Semua Peserta</option>
                                    @foreach(\App\Models\Peserta::orderBy('nama_lengkap')->get() as $peserta)
                                    <option value="{{ $peserta->id }}" {{ request('peserta_id') == $peserta->id ? 'selected' : '' }}>
                                        {{ $peserta->nama_lengkap }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kriteria</label>
                                <select name="kriteria_id" class="form-select">
                                    <option value="">Semua Kriteria</option>
                                    @foreach(\App\Models\Kriteria::where('is_active', true)->get() as $kriteria)
                                    <option value="{{ $kriteria->id }}" {{ request('kriteria_id') == $kriteria->id ? 'selected' : '' }}>
                                        {{ $kriteria->nama_kriteria }} ({{ $kriteria->bobot }}%)
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Dari Nilai</label>
                                <input type="number" name="min_nilai" class="form-control"
                                       min="0" max="100" step="1"
                                       value="{{ request('min_nilai') }}" placeholder="0">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Sampai Nilai</label>
                                <input type="number" name="max_nilai" class="form-control"
                                       min="0" max="100" step="1"
                                       value="{{ request('max_nilai') }}" placeholder="100">
                            </div>
                            <div class="col-md-2">
                                <div class="btn-group w-100" role="group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-funnel"></i> Filter
                                    </button>
                                    <a href="{{ route('juri.history') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-clockwise"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="bi bi-clipboard-check-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">{{ $penilaians->total() }}</h3>
                    <p class="text-muted mb-0 small">Total Penilaian</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="bi bi-people-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">
                        {{ $penilaians->pluck('peserta_id')->unique()->count() }}
                    </h3>
                    <p class="text-muted mb-0 small">Peserta Dinilai</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="bi bi-star-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">
                        @php
                        $avgNilai = $penilaians->avg('nilai');
                        @endphp
                        {{ $avgNilai ? number_format($avgNilai, 1) : 0 }}
                    </h3>
                    <p class="text-muted mb-0 small">Rata-rata Nilai</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="bi bi-calendar-check fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">
                        {{ $penilaians->max('nilai') ?? 0 }}
                    </h3>
                    <p class="text-muted mb-0 small">Nilai Tertinggi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- History Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">
                                <i class="bi bi-list-ul me-2"></i>
                                Daftar Penilaian
                            </h5>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('juri.history') }}?export=pdf" class="btn btn-outline-danger">
                                    <i class="bi bi-file-pdf me-1"></i>PDF
                                </a>
                                <a href="{{ route('juri.history') }}?export=excel" class="btn btn-outline-success">
                                    <i class="bi bi-file-earmark-excel me-1"></i>Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($penilaians->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Tanggal</th>
                                        <th>Peserta</th>
                                        <th>Kategori</th>
                                        <th>Kriteria</th>
                                        <th>Nilai</th>
                                        <th>Bobot</th>
                                        <th>Nilai Bobot</th>
                                        <th>Status</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penilaians as $index => $penilaian)
                                    <tr>
                                        <td>{{ $penilaians->firstItem() + $index }}</td>
                                        <td>
                                            <small>
                                                <i class="bi bi-calendar-event me-1"></i>
                                                {{ $penilaian->updated_at->format('d/m/Y') }}
                                                <br>
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $penilaian->updated_at->format('H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            <strong>{{ $penilaian->peserta->nama_lengkap }}</strong>
                                            <br><small class="text-muted">{{ $penilaian->peserta->nomor_peserta }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $penilaian->peserta->kategori ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $penilaian->kriteria->nama_kriteria }}</span>
                                            <br><small class="text-muted">{{ $penilaian->kriteria->bobot }}%</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $penilaian->nilai >= 85 ? 'success' : ($penilaian->nilai >= 70 ? 'info' : ($penilaian->nilai >= 60 ? 'warning' : 'danger')) }} fs-6">
                                                {{ $penilaian->nilai }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $penilaian->kriteria->bobot }}%</td>
                                        <td class="text-center">
                                            <strong>{{ number_format(($penilaian->nilai * $penilaian->kriteria->bobot) / 100, 2) }}</strong>
                                        </td>
                                        <td>
                                            @if($penilaian->created_at->diffInDays($penilaian->updated_at) > 0)
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-pencil me-1"></i>Diubah
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Awal
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($penilaian->catatan)
                                                <button type="button" class="btn btn-sm btn-outline-info"
                                                        data-bs-toggle="tooltip"
                                                        title="{{ Str::limit($penilaian->catatan, 100) }}"
                                                        onclick="showCatatan('{{$penilaian->id}}', '{{$penilaian->catatan}}')">
                                                    <i class="bi bi-chat-text"></i>
                                                </button>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Menampilkan {{ $penilaians->firstItem() }}-{{ $penilaians->lastItem() }} dari {{ $penilaians->total() }} penilaian
                            </div>
                            {{ $penilaians->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-clock-history fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Riwayat Penilaian</h5>
                            <p class="text-muted">Anda belum melakukan penilaian apapun</p>
                            <a href="{{ route('juri.pesertas') }}" class="btn btn-primary">
                                <i class="bi bi-pencil-square me-1"></i>Mulai Penilaian
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Catatan Modal -->
<div class="modal fade" id="catatanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Catatan Penilaian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="catatanContent"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showCatatan(id, catatan) {
    document.getElementById('catatanContent').textContent = catatan;
    new bootstrap.Modal(document.getElementById('catatanModal')).show();
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