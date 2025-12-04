@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Juri Dashboard</h1>
        <a href="{{ route('juri.pesertas') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-users fa-sm text-white-50"></i> View All Peserta
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Total Peserta Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Peserta</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_peserta'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Penilaian Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed_penilaian'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Penilaian Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_penilaian'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Progress</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $completionPercentage }}%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $completionPercentage }}%" aria-valuenow="{{ $completionPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Assigned Peserta -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Assigned Peserta</h6>
                </div>
                <div class="card-body">
                    @if($assignedPesertas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Institution</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assignedPesertas as $peserta)
                                    <tr>
                                        <td>{{ $peserta->nama_lengkap }}</td>
                                        <td>{{ $peserta->user->institusi ?? '-' }}</td>
                                        <td>
                                            <?php
                                            $completedCount = $peserta->penilaians->whereNotNull('nilai')->count();
                                            $totalCount = $peserta->penilaians->count();
                                            $isCompleted = $totalCount > 0 && $completedCount === $totalCount;
                                            ?>
                                            @if($isCompleted)
                                                <span class="badge bg-success">Completed</span>
                                            @else
                                                <span class="badge bg-warning">In Progress ({{ $completedCount }}/{{ $totalCount }})</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('juri.evaluate', $peserta->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Evaluate
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No participants assigned yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Penilaians -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Penilaians</h6>
                </div>
                <div class="card-body">
                    @if($recentPenilaians->count() > 0)
                        @foreach($recentPenilaians as $penilaian)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-{{ $penilaian->nilai >= 80 ? 'success' : ($penilaian->nilai >= 60 ? 'warning' : 'danger') }} text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    {{ $penilaian->nilai }}
                                </div>
                            </div>
                            <div class="ms-3">
                                <div class="fw-bold">{{ $penilaian->peserta->nama_lengkap }}</div>
                                <div class="text-muted small">{{ $penilaian->kriteria->nama_kriteria }}</div>
                                <div class="text-muted small">{{ $penilaian->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">No recent evaluations found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <a href="{{ route('juri.pesertas') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-users me-2"></i>All Peserta
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="{{ route('juri.history') }}" class="btn btn-success btn-block">
                                <i class="fas fa-history me-2"></i>History
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="{{ route('juri.statistics') }}" class="btn btn-info btn-block">
                                <i class="fas fa-chart-bar me-2"></i>Statistics
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection