@extends('layouts.app')

@section('title', 'Statistik Juri')

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
                    <li class="breadcrumb-item active">Statistik</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-gradient-info text-white">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1">
                                <i class="bi bi-graph-up me-2"></i>
                                Statistik & Analisis Performa
                            </h4>
                            <p class="mb-0 opacity-75">
                                Analisis mendalam performa penilaian Anda
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="badge bg-white text-info fs-6">
                                <i class="bi bi-bar-chart me-1"></i>
                                Analytics Dashboard
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="bi bi-clipboard-check-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">{{ $stats['total_evaluations'] }}</h3>
                    <p class="text-muted mb-0 small">Total Evaluasi</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="bi bi-people-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">{{ $stats['total_peserta'] }}</h3>
                    <p class="text-muted mb-0 small">Peserta Dinilai</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="bi bi-star-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">{{ number_format($stats['avg_scores'], 1) }}</h3>
                    <p class="text-muted mb-0 small">Rata-rata Nilai</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="bi bi-trophy-fill fa-2x"></i>
                    </div>
                    <h3 class="h4 mb-1">{{ $stats['highest_score'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Nilai Tertinggi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Score Distribution -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart-fill me-2"></i>
                        Distribusi Nilai
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Range Nilai</th>
                                    <th>Jumlah</th>
                                    <th>Persentase</th>
                                    <th>Visual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($scoreDistribution as $range)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{
                                            $range->score_range == 'Sangat Baik' ? 'success' :
                                            ($range->score_range == 'Baik' ? 'info' :
                                            ($range->score_range == 'Cukup' ? 'warning' : 'danger')) }}">
                                            {{ $range->score_range }}
                                        </span>
                                    </td>
                                    <td>{{ $range->count }}</td>
                                    <td>{{ round(($range->count / $stats['total_evaluations']) * 100, 1) }}%</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-{{
                                                $range->score_range == 'Sangat Baik' ? 'success' :
                                                ($range->score_range == 'Baik' ? 'info' :
                                                ($range->score_range == 'Cukup' ? 'warning' : 'danger') }}"
                                                 role="progressbar"
                                                 style="width: {{ round(($range->count / $stats['total_evaluations']) * 100) }}%">
                                                {{ $range->count }}
                                            </div>
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

        <!-- Performance by Criteria -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-list-stars me-2"></i>
                        Performa per Kriteria
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Jumlah</th>
                                    <th>Rata-rata</th>
                                    <th>Tertinggi</th>
                                    <th>Visual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($criteriaStats as $stat)
                                <tr>
                                    <td>
                                        <strong>{{ $stat->kriteria->nama_kriteria }}</strong>
                                        <br><small class="text-muted">{{ $stat->kriteria->bobot }}%</small>
                                    </td>
                                    <td>{{ $stat->count }}</td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ number_format($stat->avg_score, 1) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                        $maxScore = \App\Models\Penilaian::where('juri_id', auth()->user()->juri->id)
                                            ->where('kriteria_id', $stat->kriteria_id)
                                            ->max('nilai');
                                        @endphp
                                        <span class="badge bg-success">{{ $maxScore ?? 0 }}</span>
                                    </td>
                                    <td>
                                        @php
                                        $percentage = ($stat->avg_score / 100) * 100;
                                        @endphp
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-primary"
                                                 role="progressbar"
                                                 style="width: {{ $percentage }}%">
                                            </div>
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

    <!-- Top Performers & Recent Activity -->
    <div class="row mb-4">
        <!-- Top Performers -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-trophy-fill me-2"></i>
                        Top Performers
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Peserta</th>
                                    <th>Rata-rata</th>
                                    <th>Jumlah Kriteria</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $topPerformers = \App\Models\Peserta::selectRaw('peserta_id, pesertas.nama_lengkap, AVG(nilai) as avg_nilai, COUNT(*) as count')
                                    ->join('penilaians', 'penilaians.peserta_id', '=', 'pesertas.id')
                                    ->where('penilaians.juri_id', auth()->user()->juri->id)
                                    ->whereNotNull('penilaians.nilai')
                                    ->groupBy('peserta_id', 'pesertas.nama_lengkap')
                                    ->orderBy('avg_nilai', 'desc')
                                    ->limit(5)
                                    ->get();
                                @endforeach
                                @foreach($topPerformers as $index => $performer)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $performer->nama_lengkap }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{
                                            $performer->avg_nilai >= 85 ? 'success' :
                                            ($performer->avg_nilai >= 70 ? 'info' :
                                            ($performer->avg_nilai >= 60 ? 'warning' : 'danger')) }}">
                                            {{ number_format($performer->avg_nilai, 1) }}
                                        </span>
                                    </td>
                                    <td>{{ $performer->count }}/{{ \App\Models\Kriteria::where('is_active', true)->count() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Aktivitas Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @php
                        $recentActivity = \App\Models\Penilaian::with(['peserta', 'kriteria'])
                            ->where('juri_id', auth()->user()->juri->id)
                            ->whereNotNull('nilai')
                            ->orderBy('updated_at', 'desc')
                            ->limit(10)
                            ->get();
                        @endforeach

                        @if($recentActivity->count() > 0)
                            @foreach($recentActivity as $activity)
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    <div class="bg-{{ $activity->nilai >= 85 ? 'success' : ($activity->nilai >= 70 ? 'info' : ($activity->nilai >= 60 ? 'warning' : 'danger')) }} text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 12px;">
                                        {{ $activity->nilai }}
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <div class="fw-bold small">{{ $activity->peserta->nama_lengkap }}</div>
                                    <div class="text-muted small">{{ $activity->kriteria->nama_kriteria }}</div>
                                    <div class="text-muted small">{{ $activity->updated_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center">
                                <i class="bi bi-clock fa-3x mb-3"></i><br>
                                Belum ada aktivitas penilaian
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Trends -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up-arrow me-2"></i>
                        Trend & Insights
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>💡 Key Insights:</h6>
                            <ul class="small">
                                <li>Rata-rata nilai Anda: <strong>{{ number_format($stats['avg_scores'], 1) }}</strong></li>
                                <li>Nilai tertinggi: <strong>{{ $stats['highest_score'] ?? 0 }}</strong></li>
                                <li>Range nilai: <strong>{{ $stats['score_range'] ?? '0-0' }}</strong></li>
                                <li>Peserta dengan nilai sempurna (100): <strong>{{ $stats['perfect_scores'] ?? 0 }}</strong></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>📈 Performance Metrics:</h6>
                            <ul class="small">
                                <li>Consistency Score: <strong>{{ $stats['consistency_score'] ?? 'N/A' }}</strong></li>
                                <li>Evaluation Speed: <strong>{{ $stats['avg_evaluation_time'] ?? 'N/A' }}</strong></li>
                                <li>Feedback Quality: <strong>{{ $stats['feedback_quality'] ?? 'N/A' }}</strong></li>
                                <li>Completion Rate: <strong>{{ $stats['completion_rate'] ?? 'N/A' }}</strong></li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-3 p-3 bg-light rounded">
                        <h6 class="text-primary mb-2">🎯 Recommendations:</h6>
                        @if($stats['avg_scores'] >= 85)
                            <p class="mb-0 small">Excellent! Anda memberikan nilai yang sangat baik dan konsisten. Pertimbangkan untuk memberikan feedback yang lebih detail untuk membantu peserta improve.</p>
                        @elseif($stats['avg_scores'] >= 70)
                            <p class="mb-0 small">Good job! Nilai Anda cukup konsisten. Fokus pada kriteria dengan rata-rata lebih rendah untuk improvement.</p>
                        @else
                            <p class="mb-0 small">Consider reviewing your evaluation criteria. Range yang lebih luas dan feedback konstruktif bisa membantu peserta lebih baik.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>📊 Export Data</h6>
                    <p class="text-muted mb-3">Download detailed analytics reports</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('juri.export.statistics_csv') }}" class="btn btn-success">
                            <i class="bi bi-file-earmark-excel me-1"></i>Export CSV
                        </a>
                        <button type="button" class="btn btn-danger" onclick="generatePDFReport()">
                            <i class="bi bi-file-pdf me-1"></i>Generate PDF Report
                        </button>
                        <button type="button" class="btn btn-primary" onclick="printStatistics()">
                            <i class="bi bi-printer me-1"></i>Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function generatePDFReport() {
    fetch('{{ route("export.pdf", "juri_statistics") }}')
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

function printStatistics() {
    window.print();
}
</script>
@endpush

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
}
</style>
@endpush