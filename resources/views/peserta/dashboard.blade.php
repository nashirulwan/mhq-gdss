@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Peserta Dashboard</h1>
        <a href="{{ route('peserta.results') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-chart-bar fa-sm text-white-50"></i> View Results
        </a>
    </div>

    @if(!$resultsPublished)
    <!-- Results Not Published Alert -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info" role="alert">
                <h4 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Results Coming Soon!</h4>
                <p>Competition results will be published by the administrator. Please check back later.</p>
                <hr>
                <p class="mb-0">Thank you for your patience and participation in the Tahfidz Competition.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Content Row -->
    <div class="row">
        <!-- Total Evaluations Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Evaluations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_penilaians'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Score Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Average Score</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['avg_score'], 1) ?? '-' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Highest Score Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Highest Score</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['max_score'] ?? '-' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-trophy fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lowest Score Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Lowest Score</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['min_score'] ?? '-' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($resultsPublished && $stats['total_penilaians'] > 0)
    <!-- Content Row -->
    <div class="row">
        <!-- Criteria Scores -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Scores by Criteria</h6>
                </div>
                <div class="card-body">
                    @if($criteriaScores->count() > 0)
                        <div class="row">
                            @foreach($criteriaScores as $criteria)
                            <div class="col-md-6 mb-3">
                                <div class="card border-left-primary">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $criteria->kriteria->nama_kriteria }}</h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">Average Score:</span>
                                            <span class="badge bg-primary">{{ number_format($criteria->avg_score, 1) }}</span>
                                        </div>
                                        <div class="progress mt-2">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $criteria->avg_score }}%" aria-valuenow="{{ $criteria->avg_score }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No evaluation data available yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Performance Summary -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Performance Summary</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="h1 text-primary">{{ number_format($stats['avg_score'], 1) }}</div>
                        <div class="text-muted">Overall Average</div>
                    </div>

                    <?php
                    $grade = '';
                    $gradeColor = 'secondary';
                    if ($stats['avg_score'] >= 90) {
                        $grade = 'A+ (Excellent)';
                        $gradeColor = 'success';
                    } elseif ($stats['avg_score'] >= 80) {
                        $grade = 'A (Very Good)';
                        $gradeColor = 'info';
                    } elseif ($stats['avg_score'] >= 70) {
                        $grade = 'B (Good)';
                        $gradeColor = 'primary';
                    } elseif ($stats['avg_score'] >= 60) {
                        $grade = 'C (Fair)';
                        $gradeColor = 'warning';
                    } else {
                        $grade = 'D (Need Improvement)';
                        $gradeColor = 'danger';
                    }
                    ?>

                    <div class="text-center mb-4">
                        <span class="badge bg-{{ $gradeColor }} fs-6">{{ $grade }}</span>
                    </div>

                    <a href="{{ route('peserta.results') }}" class="btn btn-primary btn-block">
                        <i class="fas fa-chart-bar me-2"></i>View Detailed Results
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('peserta.profile') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-user me-2"></i>My Profile
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('peserta.results') }}" class="btn btn-success btn-block">
                                <i class="fas fa-chart-bar me-2"></i>My Results
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('peserta.ranking') }}" class="btn btn-info btn-block">
                                <i class="fas fa-trophy me-2"></i>Rankings
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('peserta.competition') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-info-circle me-2"></i>Competition Info
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection