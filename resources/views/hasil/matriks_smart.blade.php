@extends('layouts.app')

@section('title', 'Matrix Perhitungan SMART')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ auth()->user()->isAdmin() ? route('hasil.index') : route('juri.hasil.index') }}" class="text-decoration-none">
                            <i class="bi bi-graph-up me-1"></i>Hasil & Analisis
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Matrix SMART</li>
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
                                <i class="bi bi-calculator me-2"></i>
                                Matriks Keputusan SMART
                            </h4>
                            <p class="mb-0 opacity-75">
                                Normalisasi dan Pembobotan Nilai Kriteria
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <button type="button" class="btn btn-light" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i>Cetak
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SMART Method Explanation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Metode SMART (Simple Multi-Attribute Rating Technique)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Rumus Normalisasi:</h6>
                            <div class="alert alert-light">
                                <code>
                                    Nilai Normalisasi = (Nilai - Min) / (Max - Min)
                                </code>
                            </div>
                            <p class="mb-0 small">Mengubah semua nilai ke skala 0-1 untuk komparabilitas</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">Rumus Pembobotan:</h6>
                            <div class="alert alert-light">
                                <code>
                                    Nilai Akhir = Σ(Normalisasi × Bobot)
                                </code>
                            </div>
                            <p class="mb-0 small">Menghitung nilai tertimbang sesuai bobot kriteria</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Decision Matrix -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-grid-3x3-gap me-2"></i>
                        Matriks Keputusan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%" rowspan="2">No</th>
                                    <th width="15%" rowspan="2">Peserta</th>
                                    @foreach($kriterias as $kriteria)
                                    <th width="10%" colspan="2">{{ $kriteria->nama_kriteria }}</th>
                                    @endforeach
                                    <th width="10%" rowspan="2">Total SMART</th>
                                    <th width="5%" rowspan="2">Ranking</th>
                                </tr>
                                <tr>
                                    @foreach($kriterias as $kriteria)
                                    <th width="5%">{{ $kriteria->bobot }}%</th>
                                    <th width="5%">× Bobot</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @if($matriks->count() > 0)
                                    @foreach($matriks as $index => $peserta)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $peserta['nama_lengkap'] }}</strong>
                                            <br><small class="text-muted">{{ $peserta['instansi'] }}</small>
                                        </td>
                                        @foreach($kriterias as $kriteria)
                                        @php
                                        $nilai = $peserta["penilaian_{$kriteria->id}"] ?? 0;
                                        $normalisasi = $peserta["normalisasi_{$kriteria->id}"] ?? 0;
                                        $terbobot = $normalisasi * ($kriteria->bobot / 100);
                                        @endphp
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ number_format($normalisasi, 4) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ number_format($terbobot, 4) }}</span>
                                        </td>
                                        @endforeach
                                        <td class="text-center">
                                            <span class="badge bg-success fs-6">
                                                {{ number_format($peserta['nilai_akhir_smart'], 4) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning">
                                                #{{ $peserta['peringkat_smart'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="{{ 3 + ($kriterias->count() * 2) + 2 }}" class="text-center">
                                            <div class="py-4">
                                                <i class="bi bi-grid fa-3x text-muted mb-2"></i>
                                                <p class="text-muted">Belum ada matriks keputusan</p>
                                                <small class="text-muted">Lakukan perhitungan SMART terlebih dahulu</small>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="bi bi-calculator-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">{{ $matriks->count() }}</h3>
                    <p class="text-muted mb-0 small">Peserta Dinilai</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="bi bi-trophy-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">
                        {{ $matriks->max('nilai_akhir_smart') ? number_format($matriks->max('nilai_akhir_smart'), 4) : 0 }}
                    </h3>
                    <p class="text-muted mb-0 small">Score Tertinggi</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="bi bi-graph-up fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">
                        {{ $matriks->avg('nilai_akhir_smart') ? number_format($matriks->avg('nilai_akhir_smart'), 4) : 0 }}
                    </h3>
                    <p class="text-muted mb-0 small">Rata-rata</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="bi bi-bar-chart-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">
                        {{ $matriks->min('nilai_akhir_smart') ? number_format($matriks->min('nilai_akhir_smart'), 4) : 0 }}
                    </h3>
                    <p class="text-muted mb-0 small">Score Terendah</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Calculation Steps -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-journal-text me-2"></i>
                        Detail Langkah Perhitungan (Contoh untuk Peserta Pertama)
                    </h5>
                </div>
                <div class="card-body">
                    @if($matriks->count() > 0)
                    @php
                    $contohPeserta = $matriks->first();
                    @endphp
                    <div class="alert alert-info">
                        <h6>Contoh Perhitungan untuk: <strong>{{ $contohPeserta['nama_lengkap'] }}</strong></h6>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h6>1. Data Nilai Asli:</h6>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Kriteria</th>
                                            <th>Nilai</th>
                                            <th>Min</th>
                                            <th>Max</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kriterias as $kriteria)
                                        <tr>
                                            <td>{{ $kriteria->nama_kriteria }}</td>
                                            <td>{{ $contohPeserta["penilaian_{$kriteria->id}"] ?? '-' }}</td>
                                            <td>{{ $contohPeserta["min_{$kriteria->id}"] ?? '-' }}</td>
                                            <td>{{ $contohPeserta["max_{$kriteria->id}"] ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>2. Hasil Normalisasi:</h6>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Kriteria</th>
                                            <th>Normalisasi</th>
                                            <th>Bobot</th>
                                            <th>× Bobot</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kriterias as $kriteria)
                                        @php
                                        $nilai = $contohPeserta["penilaian_{$kriteria->id}"] ?? 0;
                                        $normalisasi = $contohPeserta["normalisasi_{$kriteria->id}"] ?? 0;
                                        $terbobot = $normalisasi * ($kriteria->bobot / 100);
                                        @endphp
                                        <tr>
                                            <td>{{ $kriteria->nama_kriteria }}</td>
                                            <td>{{ number_format($normalisasi, 4) }}</td>
                                            <td>{{ $kriteria->bobot }}%</td>
                                            <td>{{ number_format($terbobot, 4) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="fw-bold">
                                            <td>Total:</td>
                                            <td colspan="3">{{ number_format($contohPeserta['nilai_akhir_smart'], 4) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Belum ada data untuk ditampilkan. Lakukan perhitungan SMART terlebih dahulu.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-download me-2"></i>
                        Export Data
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-success mb-3">
                                <i class="bi bi-file-earmark-excel me-2"></i>
                                Export CSV (Available Now)
                            </h6>
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('export.matrix_smart_csv') }}" class="btn btn-success">
                                    <i class="bi bi-file-earmark-excel me-2"></i>Export Matrix SMART
                                </a>
                                <a href="{{ route('export.smart_csv') }}" class="btn btn-outline-success">
                                    <i class="bi bi-file-earmark-text me-2"></i>Export Hasil SMART
                                </a>
                                <a href="{{ route('export.combined_csv') }}" class="btn btn-outline-success">
                                    <i class="bi bi-file-earmark-text me-2"></i>Export Hasil Gabungan
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-danger mb-3">
                                <i class="bi bi-file-pdf me-2"></i>
                                Export PDF (Coming Soon)
                            </h6>
                            <div class="d-flex flex-column gap-2">
                                <button type="button" class="btn btn-danger" onclick="generatePDF('smart')">
                                    <i class="bi bi-file-pdf me-2"></i>Export Matrix SMART (PDF)
                                </button>
                                <button type="button" class="btn btn-outline-danger" onclick="generatePDF('results')">
                                    <i class="bi bi-file-pdf me-2"></i>Export Hasil (PDF)
                                </button>
                                <button type="button" class="btn btn-outline-danger" onclick="generatePDF('combined')">
                                    <i class="bi bi-file-pdf me-2"></i>Export Gabungan (PDF)
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12 text-center">
            <div class="btn-group" role="group">
                <a href="{{ auth()->user()->isAdmin() ? route('hasil.index') : route('juri.hasil.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Hasil
                </a>
                <a href="{{ auth()->user()->isAdmin() ? route('hasil.matriks_borda') : route('juri.hasil.matriks_borda') }}" class="btn btn-success">
                    <i class="bi bi-list-ol me-1"></i>Lihat Matrix Borda
                </a>
                @if(auth()->user()->isAdmin())
                <form method="POST" action="{{ route('hasil.hitung_smart') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-calculator me-1"></i>Hitung Ulang SMART
                    </button>
                </form>
                @endif
                <button type="button" class="btn btn-info" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i>Cetak
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    .no-print {
        display: none !important;
    }
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }
    .table {
        font-size: 12px !important;
    }
    .badge {
        padding: 2px 6px !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
function generatePDF(type) {
    fetch(`{{ route('export.pdf', 'type') }}`.replace('type', type))
        .then(response => response.json())
        .then(data => {
            if (data.status === 'placeholder') {
                alert(data.message + '\n\nCatatan: ' + data.note);
            } else {
                alert('PDF generation failed: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghubungi server');
        });
}
</script>
@endpush