@extends('layouts.app')

@section('title', 'Hasil & Analisis')

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('hasil.matriks-smart') }}" class="btn btn-outline-info btn-sm">
            <i class="bi bi-table me-1"></i>Matriks SMART
        </a>
        <a href="{{ route('hasil.matriks-borda') }}" class="btn btn-outline-success btn-sm">
            <i class="bi bi-diagram-3 me-1"></i>Matriks Borda
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Status Cards -->
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0">
                    <i class="bi bi-calculator me-2"></i>Status Perhitungan SMART
                </h6>
            </div>
            <div class="card-body">
                @if($smartStatus['valid'])
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <strong>Data lengkap!</strong> Perhitungan SMART dapat dilakukan.
                    </div>
                    <form action="{{ route('hasil.hitung_smart') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-play-circle me-2"></i>Jalankan Perhitungan SMART
                        </button>
                    </form>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Data belum lengkap!</strong><br>
                        Progress: {{ round($smartStatus['completion_percentage'], 1) }}% ({{ $smartStatus['actual_penilaians'] }}/{{ $smartStatus['expected_penilaians'] }} penilaian)
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar" style="width: {{ $smartStatus['completion_percentage'] }}%"></div>
                    </div>
                    <a href="{{ route('penilaian.create') }}" class="btn btn-warning w-100">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Data Penilaian
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-success text-white">
                <h6 class="m-0">
                    <i class="bi bi-bar-chart me-2"></i>Status Perhitungan Borda
                </h6>
            </div>
            <div class="card-body">
                @if($bordaStatus['valid'])
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <strong>Data lengkap!</strong> Perhitungan Borda dapat dilakukan.
                    </div>
                    <form action="{{ route('hasil.hitung_borda') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-play-circle me-2"></i>Jalankan Perhitungan Borda
                        </button>
                    </form>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Data belum lengkap!</strong><br>
                        Progress: {{ round($bordaStatus['completion_percentage'], 1) }}% ({{ $bordaStatus['actual_penilaians'] }}/{{ $bordaStatus['expected_penilaians'] }} penilaian)
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-success" style="width: {{ $bordaStatus['completion_percentage'] }}%"></div>
                    </div>
                    <a href="{{ route('penilaian.create') }}" class="btn btn-warning w-100">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Data Penilaian
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Combined Calculation -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-info text-white">
                <h6 class="m-0">
                    <i class="bi bi-layers me-2"></i>Perhitungan Gabungan (SMART + Borda)
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Perhitungan gabungan menggabungkan hasil dari metode SMART (50%) dan Borda (50%) untuk memberikan ranking yang lebih seimbang.
                </p>
                @if($smartStatus['valid'] && $bordaStatus['valid'])
                    <form action="{{ route('hasil.hitung_gabungan') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-info">
                            <i class="bi bi-play-circle me-2"></i>Jalankan Perhitungan Gabungan
                        </button>
                    </form>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-info-circle me-2"></i>
                        Perhitungan gabungan membutuhkan data yang lengkap untuk kedua metode (SMART dan Borda).
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Results Tables -->
@if($smartResults->isNotEmpty() || $bordaResults->isNotEmpty())
<div class="row">
    <!-- SMART Results -->
    @if($smartResults->isNotEmpty())
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0">
                    <i class="bi bi-trophy me-2"></i>Hasil Ranking SMART
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Rank</th>
                                <th>Peserta</th>
                                <th>Nilai SMART</th>
                                <th>Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($smartResults as $index => $peserta)
                            <tr>
                                <td>
                                    <span class="badge {{ $index === 0 ? 'bg-warning' : ($index === 1 ? 'bg-secondary' : 'bg-primary') }}">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td>
                                    <strong>{{ $peserta->nama_lengkap }}</strong><br>
                                    <small class="text-muted">{{ $peserta->instansi }}</small>
                                </td>
                                <td>
                                    <strong class="text-primary">{{ number_format($peserta->nilai_akhir_smart, 3) }}</strong>
                                </td>
                                <td>{{ $peserta->kategori }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Borda Results -->
    @if($bordaResults->isNotEmpty())
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-success text-white">
                <h6 class="m-0">
                    <i class="bi bi-award me-2"></i>Hasil Ranking Borda
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Rank</th>
                                <th>Peserta</th>
                                <th>Skor Borda</th>
                                <th>Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bordaResults as $index => $peserta)
                            <tr>
                                <td>
                                    <span class="badge {{ $index === 0 ? 'bg-warning' : ($index === 1 ? 'bg-secondary' : 'bg-success') }}">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td>
                                    <strong>{{ $peserta->nama_lengkap }}</strong><br>
                                    <small class="text-muted">{{ $peserta->instansi }}</small>
                                </td>
                                <td>
                                    <strong class="text-success">{{ $peserta->skor_borda }}</strong>
                                </td>
                                <td>{{ $peserta->kategori }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Combined Results -->
@if($combinedResults && count($combinedResults) > 0)
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-info text-white">
                <h6 class="m-0">
                    <i class="bi bi-star me-2"></i>Hasil Ranking Gabungan (50% SMART + 50% Borda)
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Rank</th>
                                <th>Peserta</th>
                                <th>Instansi</th>
                                <th>Kategori</th>
                                <th>SMART Score</th>
                                <th>Borda Score</th>
                                <th>Final Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($combinedResults as $result)
                            <tr>
                                <td>
                                    <h5><span class="badge bg-info">{{ $result['peringkat'] }}</span></h5>
                                </td>
                                <td>
                                    <strong>{{ $result['peserta']->nama_lengkap }}</strong>
                                </td>
                                <td>{{ $result['peserta']->instansi }}</td>
                                <td>{{ $result['peserta']->kategori }}</td>
                                <td>
                                    <div class="text-center">
                                        <strong>{{ $result['smart_normalisasi'] }}</strong><br>
                                        <small class="text-muted">({{ number_format($result['peserta']->nilai_akhir_smart, 3) }})</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <strong>{{ $result['borda_normalisasi'] }}</strong><br>
                                        <small class="text-muted">({{ $result['peserta']->skor_borda }})</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <h4 class="text-primary">{{ $result['skor_akhir'] }}</h4>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Empty State -->
@if($smartResults->isEmpty() && $bordaResults->isEmpty())
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-body text-center py-5">
                <i class="bi bi-graph-up fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Belum Ada Hasil Perhitungan</h4>
                <p class="text-muted">
                    Silakan lengkapi data penilaian terlebih dahulu, kemudian jalankan perhitungan untuk melihat hasilnya.
                </p>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <a href="{{ route('penilaian.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Input Penilaian
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('peserta.create') }}" class="btn btn-success">
                            <i class="bi bi-person-plus me-2"></i>Tambah Peserta
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-info">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection