@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_peserta'] }}</div>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_juri'] }}</div>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_kriteria'] }}</div>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_penilaian'] }}</div>
                        <div class="text-xs text-muted">/ {{ $stats['total_peserta'] * $stats['total_juri'] * $stats['total_kriteria'] }}</div>
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
                        <span class="badge {{ $smartStatus['completion_percentage'] >= 100 ? 'bg-success' : 'bg-warning' }}">
                            {{ round($smartStatus['completion_percentage'], 1) }}%
                        </span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar {{ $smartStatus['completion_percentage'] >= 100 ? 'bg-success' : 'bg-warning' }}"
                             style="width: {{ $smartStatus['completion_percentage'] }}%"></div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-4">
                        <small class="text-muted">Peserta</small><br>
                        <strong>{{ $smartStatus['pesertas'] }}</strong>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">Kriteria</small><br>
                        <strong>{{ $smartStatus['kriterias'] }}</strong>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">Penilaian</small><br>
                        <strong>{{ $smartStatus['actual_penilaians'] }}/{{ $smartStatus['expected_penilaians'] }}</strong>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    @if($smartStatus['valid'])
                        <span class="badge bg-success">✓ Valid untuk SMART</span>
                    @else
                        <span class="badge bg-warning">⚠ Data belum lengkap</span>
                    @endif
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
                        <span class="badge {{ $bordaStatus['completion_percentage'] >= 100 ? 'bg-success' : 'bg-warning' }}">
                            {{ round($bordaStatus['completion_percentage'], 1) }}%
                        </span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar {{ $bordaStatus['completion_percentage'] >= 100 ? 'bg-success' : 'bg-warning' }}"
                             style="width: {{ $bordaStatus['completion_percentage'] }}%"></div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-4">
                        <small class="text-muted">Peserta</small><br>
                        <strong>{{ $bordaStatus['pesertas'] }}</strong>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">Juri</small><br>
                        <strong>{{ $bordaStatus['juris'] }}</strong>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">Penilaian</small><br>
                        <strong>{{ $bordaStatus['actual_penilaians'] }}/{{ $bordaStatus['expected_penilaians'] }}</strong>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    @if($bordaStatus['valid'])
                        <span class="badge bg-success">✓ Valid untuk Borda</span>
                    @else
                        <span class="badge bg-warning">⚠ Data belum lengkap</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Ranking (if available) -->
@if($topRanking->isNotEmpty())
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="bi bi-trophy-fill me-2"></i>Peringkat Sementara (SMART)
                </h6>
                <a href="{{ route('hasil.index') }}" class="btn btn-sm btn-outline-info">Lihat Semua →</a>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($topRanking as $index => $peserta)
                    <div class="col-md-4">
                        <div class="text-center p-3 {{ $index === 0 ? 'bg-warning bg-opacity-10' : ($index === 1 ? 'bg-secondary bg-opacity-10' : 'bg-light') }}">
                            <div class="display-4 {{ $index === 0 ? 'text-warning' : ($index === 1 ? 'text-secondary' : 'text-muted') }}">
                                {{ $index === 1 ? 2 : ($index === 0 ? 1 : 3) }}
                            </div>
                            <h6 class="mt-2 mb-1">{{ $peserta->nama_lengkap }}</h6>
                            <small class="text-muted">{{ $peserta->instansi }}</small><br>
                            <strong class="text-primary">{{ number_format($peserta->nilai_akhir_smart, 3) }}</strong>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

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
                @if($recentPenilaians->isNotEmpty())
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
                                @foreach($recentPenilaians as $penilaian)
                                <tr>
                                    <td><small>{{ $penilaian->created_at->format('d M H:i') }}</small></td>
                                    <td>{{ $penilaian->juri->nama_lengkap }}</td>
                                    <td>{{ $penilaian->peserta->nama_lengkap }}</td>
                                    <td><span class="badge bg-info">{{ $penilaian->kriteria->nama_kriteria }}</span></td>
                                    <td><strong>{{ $penilaian->nilai }}</strong></td>
                                    <td><small class="text-muted">{{ $penilaian->catatan ?? '-' }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-clipboard-x fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada aktivitas penilaian</p>
                        <a href="{{ route('penilaian.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>Mulai Penilaian
                        </a>
                    </div>
                @endif
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
                        <a href="{{ route('peserta.create') }}" class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-person-plus me-2"></i>Tambah Peserta
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('penilaian.create') }}" class="btn btn-outline-success btn-sm w-100">
                            <i class="bi bi-clipboard-plus me-2"></i>Input Penilaian
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('hasil.index') }}" class="btn btn-outline-info btn-sm w-100">
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
@endsection

@push('styles')
<style>
    .border-left-primary { border-left: 4px solid #4e73df !important; }
    .border-left-success { border-left: 4px solid #1cc88a !important; }
    .border-left-info { border-left: 4px solid #36b9cc !important; }
    .border-left-warning { border-left: 4px solid #f6c23e !important; }
    .text-gray-300 { color: #dddfeb !important; }
    .text-gray-800 { color: #5a5c69 !important; }
</style>
@endpush