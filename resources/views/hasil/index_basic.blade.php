@extends('layouts.app')

@section('title', 'Hasil & Analisis')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-gradient-info text-white">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1">
                                <i class="bi bi-graph-up-arrow me-2"></i>
                                Hasil & Analisis Kompetisi MHQ
                            </h4>
                            <p class="mb-0 opacity-75">
                                Sistem Perhitungan Metode SMART + Borda
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="badge bg-white text-info fs-6">
                                <i class="bi bi-calculator me-1"></i>
                                Dashboard Analisis
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Overview -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="bi bi-people-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">{{ \App\Models\Peserta::count() }}</h3>
                    <p class="text-muted mb-0 small">Total Peserta</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="bi bi-gavel-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">{{ \App\Models\Juri::where('is_active', true)->count() }}</h3>
                    <p class="text-muted mb-0 small">Juri Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="bi bi-clipboard-check-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">{{ \App\Models\Penilaian::count() }}</h3>
                    <p class="text-muted mb-0 small">Total Penilaian</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="bi bi-star-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">{{ \App\Models\Kriteria::where('is_active', true)->count() }}</h3>
                    <p class="text-muted mb-0 small">Kriteria</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Perhitungan Control Panel -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-cpu-fill text-primary me-2"></i>
                        Control Panel Perhitungan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Status Check -->
                            @php
                            $smartStatus = app(\App\Services\SMARTService::class)->isValidForSMART();
                            $bordaStatus = app(\App\Services\BordaService::class)->isValidForBorda();
                            @endphp

                            <div class="alert {{ ($smartStatus['valid'] && $bordaStatus['valid']) ? 'alert-success' : 'alert-warning' }} mb-0">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="mb-2">Status Data untuk Perhitungan:</h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <strong>Peserta:</strong> {{ $smartStatus['pesertas'] }}
                                                <i class="bi bi-check-circle text-success"></i>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Juri:</strong> {{ $smartStatus['juris'] }}
                                                <i class="bi bi-check-circle text-success"></i>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Penilaian:</strong> {{ round($smartStatus['completion_percentage']) }}% Complete
                                                @if($smartStatus['completion_percentage'] >= 100)
                                                    <i class="bi bi-check-circle text-success"></i>
                                                @else
                                                    <i class="bi bi-exclamation-triangle text-warning"></i>
                                                @endif
                                            </div>
                                        </div>
                                        @if(!$smartStatus['valid'] || !$bordaStatus['valid'])
                                        <small class="text-muted">
                                            Dibutuhkan: {{ $smartStatus['expected_penilaians'] }} penilaian | Tersedia: {{ $smartStatus['actual_penilaians'] }} penilaian
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <!-- Action Buttons -->
                            <form method="POST" action="{{ route('hasil.hitung_gabungan') }}" class="d-inline">
                                @csrf
                                <button type="submit"
                                        class="btn btn-{{ ($smartStatus['valid'] && $bordaStatus['valid']) ? 'success' : 'secondary' }} btn-lg"
                                        @if(!$smartStatus['valid'] || !$bordaStatus['valid']) disabled
                                        @endif>
                                    <i class="bi bi-lightning-fill me-1"></i>
                                    Hitung Gabungan (50% SMART + 50% Borda)
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Individual Calculation Buttons -->
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <form method="POST" action="{{ route('hasil.hitung_smart') }}" class="d-inline">
                                @csrf
                                <button type="submit"
                                        class="btn btn-primary w-100 {{ $smartStatus['valid'] ? '' : 'disabled' }}"
                                        @if(!$smartStatus['valid']) disabled @endif>
                                    <i class="bi bi-calculator me-1"></i>
                                    Hitung SMART
                                </button>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <form method="POST" action="{{ route('hasil.hitung_borda') }}" class="d-inline">
                                @csrf
                                <button type="submit"
                                        class="btn btn-success w-100 {{ $bordaStatus['valid'] ? '' : 'disabled' }}"
                                        @if(!$bordaStatus['valid']) disabled @endif>
                                    <i class="bi bi-bar-chart me-1"></i>
                                    Hitung Borda
                                </button>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('hasil.matriks_smart') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-grid-3x3-gap me-1"></i>
                                Matrix View
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Section -->
    <div class="row">
        <!-- Current Results -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-trophy-fill text-warning me-2"></i>
                        Hasil Peringkat Saat Ini
                    </h5>
                </div>
                <div class="card-body">
                    @php
                    $smartResults = \App\Models\Peserta::whereNotNull('nilai_akhir_smart')
                        ->orderBy('nilai_akhir_smart', 'desc')
                        ->get();

                    $bordaResults = \App\Models\Peserta::whereNotNull('skor_borda')
                        ->orderBy('skor_borda', 'desc')
                        ->get();
                    @endphp

                    @if($smartResults->count() > 0 || $bordaResults->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Peserta</th>
                                        <th>Instansi</th>
                                        <th class="text-center">Score SMART</th>
                                        <th class="text-center">Score Borda</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Combine and sort both results --}}
                                    @php
                                    $allPeserta = \App\Models\Peserta::with('penilaians')
                                        ->where(function($query) {
                                            $query->whereNotNull('nilai_akhir_smart')
                                                  ->orWhereNotNull('skor_borda');
                                        })
                                        ->orderBy('nilai_akhir_smart', 'desc')
                                        ->orderBy('skor_borda', 'desc')
                                        ->get();
                                    @endphp

                                    @foreach($allPeserta as $index => $peserta)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $peserta->nama_lengkap }}</strong>
                                            <br><small class="text-muted">{{ $peserta->kategori }}</small>
                                        </td>
                                        <td>{{ $peserta->instansi ?? '-' }}</td>
                                        <td class="text-center">
                                            @if($peserta->nilai_akhir_smart)
                                                <span class="badge bg-primary">{{ number_format($peserta->nilai_akhir_smart, 3) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($peserta->skor_borda)
                                                <span class="badge bg-success">{{ $peserta->skor_borda }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($peserta->nilai_akhir_smart && $peserta->skor_borda)
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-check-circle me-1"></i>Complete
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-clock me-1"></i>Partial
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-calculator fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Hasil Perhitungan</h5>
                            <p class="text-muted mb-0">
                                Selesaikan semua penilaian dan klik "Hitung Gabungan" untuk melihat hasil
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Info Sidebar -->
        <div class="col-lg-4 mb-4">
            <!-- Method Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Metode Perhitungan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">
                            <i class="bi bi-calculator me-1"></i>SMART Method
                        </h6>
                        <p class="small text-muted mb-0">
                            Normalisasi nilai + pembobotan kriteria. Fokus pada nilai absolut peserta.
                        </p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-success">
                            <i class="bi bi-bar-chart me-1"></i>Borda Method
                        </h6>
                        <p class="small text-muted mb-0">
                            Voting system berdasarkan preferensi relatif antar peserta.
                        </p>
                    </div>
                    <div>
                        <h6 class="text-warning">
                            <i class="bi bi-layers me-1"></i>Kombinasi 50-50
                        </h6>
                        <p class="small text-muted mb-0">
                            Menggabungkan kedua metode untuk hasil yang lebih objektif.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-lightning-fill me-2"></i>
                        Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('hasil.matriks_smart') }}" class="btn btn-outline-primary">
                            <i class="bi bi-grid me-1"></i>
                            Matrix SMART
                        </a>
                        <a href="{{ route('hasil.matriks_borda') }}" class="btn btn-outline-success">
                            <i class="bi bi-list-ol me-1"></i>
                            Matrix Borda
                        </a>
                        <a href="{{ route('penilaian.index') }}" class="btn btn-outline-info">
                            <i class="bi bi-pencil-square me-1"></i>
                            Input Penilaian
                        </a>
                        <a href="{{ route('peserta.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-people me-1"></i>
                            Manage Peserta
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection