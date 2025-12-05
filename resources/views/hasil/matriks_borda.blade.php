@extends('layouts.app')

@section('title', 'Matrix Perhitungan Borda')

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
                    <li class="breadcrumb-item active">Matrix Borda</li>
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
                                <i class="bi bi-list-ol me-2"></i>
                                Matriks Perhitungan Borda
                            </h4>
                            <p class="mb-0 opacity-75">
                                Ranking dan Sistem Voting per Juri
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

    <!-- Borda Method Explanation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Metode Borda (Count Method)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-success">Rumus Poin Borda:</h6>
                            <div class="alert alert-light">
                                <code>
                                    Poin = (Jumlah Peserta - 1) - Rank
                                </code>
                            </div>
                            <p class="mb-0 small">Rank 1 mendapat poin tertinggi, rank terakhir mendapat 0</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-info">Rumus Agregasi:</h6>
                            <div class="alert alert-light">
                                <code>
                                    Total Poin = Σ(Poin × Bobot Borda)
                                </code>
                            </div>
                            <p class="mb-0 small">Menghitung total poin dengan bobot kriteria</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Borda Matrix Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-table me-2"></i>
                        Matriks Voting Borda
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
                                    <th width="12%" colspan="2">{{ $kriteria->nama_kriteria }} ({{ $kriteria->borda_borda }}%)</th>
                                    @endforeach
                                    <th width="10%" rowspan="2">Total Poin</th>
                                    <th width="5%" rowspan="2">Ranking</th>
                                </tr>
                                <tr>
                                    @foreach($kriterias as $kriteria)
                                    <th width="6%">Rank</th>
                                    <th width="6%">Poin</th>
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
                                        $rank = $peserta["rank_{$kriteria->id}"] ?? 1;
                                        $poinBorda = isset($detailVoting[$peserta['id']][$kriteria->id])
                                                ? $detailVoting[$peserta['id']][$kriteria->id]['poin_borda']
                                                : 0;
                                        @endphp
                                        <td class="text-center">
                                            <span class="badge bg-primary">#{{ $rank }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $poinBorda }}</span>
                                        </td>
                                        @endforeach
                                        <td class="text-center">
                                            <span class="badge bg-success fs-6">
                                                {{ $peserta['skor_borda'] }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning">
                                                #{{ $peserta['peringkat_borda'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="{{ 3 + ($kriterias->count() * 2) + 2 }}" class="text-center">
                                            <div class="py-4">
                                                <i class="bi bi-list-ol fa-3x text-muted mb-2"></i>
                                                <p class="text-muted">Belum ada matriks voting</p>
                                                <small class="text-muted">Lakukan perhitungan Borda terlebih dahulu</small>
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

    <!-- Juri Voting Details -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-people-fill me-2"></i>
                        Detail Voting per Juri
                    </h5>
                </div>
                <div class="card-body">
                    @if(!empty($detailVoting))
                        <div class="accordion" id="juriAccordion">
                            @foreach($detailVoting as $pesertaId => $votingData)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $pesertaId }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $pesertaId }}" aria-expanded="false" aria-controls="collapse{{ $pesertaId }}">
                                        <strong>{{ $matriks->where('id', $pesertaId)->first()->nama_lengkap ?? 'Peserta ' . $pesertaId }}</strong>
                                        <span class="ms-2 badge bg-success">Total: {{ array_sum(array_column($votingData, 'poin_borda')) }} pts</span>
                                    </button>
                                </h2>
                                <div id="collapse{{ $pesertaId }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $pesertaId }}" data-bs-parent="#juriAccordion">
                                    <div class="accordion-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Juri</th>
                                                        @foreach($kriterias as $kriteria)
                                                        <th>{{ $kriteria->nama_kriteria }}</th>
                                                        @endforeach
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($votingData as $juriId => $juriData)
                                                    <tr>
                                                        <td>
                                                            <strong>{{ $juriData['nama_juri'] ?? 'Juri ' . $juriId }}</strong>
                                                        </td>
                                                        @foreach($kriterias as $kriteria)
                                                        <td class="text-center">
                                                            @php
                                                            $rank = $juriData["rank_{$kriteria->id}"] ?? '-';
                                                            $poin = isset($juriData["poin_borda_{$kriteria->id}"]) ? $juriData["poin_borda_{$kriteria->id}"] : 0;
                                                            @endphp
                                                            #{{ $rank }}
                                                            @if($poin > 0)
                                                                <span class="badge bg-info ms-1">{{ $poin }}</span>
                                                            @endif
                                                        </td>
                                                        @endforeach
                                                        <td class="text-center">
                                                            <strong>{{ $peserta['skor_borda'] ?? 0 }}</strong>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Belum ada data voting detail untuk ditampilkan.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="bi bi-bar-chart-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">{{ $matriks->count() }}</h3>
                    <p class="text-muted mb-0 small">Peserta Dinilai</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="bi bi-trophy-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">
                        {{ $matriks->max('skor_borda') ?? 0 }}
                    </h3>
                    <p class="text-muted mb-0 small">Poin Tertinggi</p>
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
                        @php
                        $avgPoin = $matriks->avg('skor_borda');
                        @endphp
                        {{ $avgPoin ? number_format($avgPoin, 1) : 0 }}
                    </h3>
                    <p class="text-muted mb-0 small">Rata-rata Poin</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="bi bi-people fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">
                        @php
                        $totalJuris = !empty($detailVoting) ? count(array_first($detailVoting)) : 0;
                        @endphp
                        {{ $totalJuris }}
                    </h3>
                    <p class="text-muted mb-0 small">Total Juri</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Method Comparison -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-diagram-3 me-2"></i>
                        Perbandingan Hasil Ranking
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Peserta</th>
                                    <th class="text-center">Rank SMART</th>
                                    <th class="text-center">Score SMART</th>
                                    <th class="text-center">Rank Borda</th>
                                    <th class="text-center">Poin Borda</th>
                                    <th class="text-center">Selisih Rank</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($matriks->count() > 0)
                                    @php
                                    $pesertasWithBoth = $matriks->filter(function($peserta) {
                                        return isset($peserta['peringkat_smart']) && isset($peserta['peringkat_borda']);
                                    })->sortBy('peringkat_smart');
                                    @endphp
                                    @foreach($pesertasWithBoth as $index => $peserta)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $peserta['nama_lengkap'] }}</strong></td>
                                        <td class="text-center">
                                            @if(isset($peserta['peringkat_smart']))
                                                <span class="badge bg-primary">#{{ $peserta['peringkat_smart'] }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(isset($peserta['nilai_akhir_smart']))
                                                {{ number_format($peserta['nilai_akhir_smart'], 3) }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">#{{ $peserta['peringkat_borda'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $peserta['skor_borda'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                            $selisih = isset($peserta['peringkat_smart']) ?
                                    $peserta['peringkat_smart'] - $peserta['peringkat_borda'] :
                                    'N/A';
                                            @endphp
                                            @if($selisih === 'N/A')
                                                <span class="text-muted">N/A</span>
                                            @elseif($selisih == 0)
                                                <span class="badge bg-secondary">Sama</span>
                                            @elseif($selisih > 0)
                                                <span class="badge bg-warning">-{{ abs($selisih) }}</span>
                                            @else
                                                <span class="badge bg-success">+{{ abs($selisih) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="py-4">
                                                <i class="bi bi-diagram-3 fa-3x text-muted mb-2"></i>
                                                <p class="text-muted">Belum ada data untuk dibandingkan</p>
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
                                <a href="{{ route('export.voting_borda_csv') }}" class="btn btn-success">
                                    <i class="bi bi-file-earmark-excel me-2"></i>Export Detail Voting Borda
                                </a>
                                <a href="{{ route('export.borda_csv') }}" class="btn btn-outline-success">
                                    <i class="bi bi-file-earmark-text me-2"></i>Export Hasil Borda
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
                                <button type="button" class="btn btn-danger" onclick="generatePDF('borda')">
                                    <i class="bi bi-file-pdf me-2"></i>Export Matrix Borda (PDF)
                                </button>
                                <button type="button" class="btn btn-outline-danger" onclick="generatePDF('voting')">
                                    <i class="bi bi-file-pdf me-2"></i>Export Detail Voting (PDF)
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
                <a href="{{ auth()->user()->isAdmin() ? route('hasil.matriks_smart') : route('juri.hasil.matriks_smart') }}" class="btn btn-primary">
                    <i class="bi bi-calculator me-1"></i>Lihat Matrix SMART
                </a>
                @if(auth()->user()->isAdmin())
                <form method="POST" action="{{ route('hasil.hitung_gabungan') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-lightning-fill me-1"></i>Hitung Gabungan
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
    .accordion-button:not(.collapsed) {
        background-color: rgba(0,0,0,.125);
        color: white;
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