@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Admin Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Total Users Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Peserta Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
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

        <!-- Total Juri Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Juri</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_juri'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gavel fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Penilaian Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Penilaian</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_penilaian'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Recent Users -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Users</h6>
                </div>
                <div class="card-body">
                    @if($stats['recent_users']->count() > 0)
                        @foreach($stats['recent_users'] as $user)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <img class="rounded-circle" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" width="40" height="40">
                            </div>
                            <div class="ms-3">
                                <div class="fw-bold">{{ $user->name }}</div>
                                <div class="text-muted small">{{ $user->email }}</div>
                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'juri' ? 'info' : 'success') }}">
                                    {{ $user->role_label }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">No recent users found.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Penilaians -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Penilaians</h6>
                </div>
                <div class="card-body">
                    @if($stats['recent_penilaians']->count() > 0)
                        @foreach($stats['recent_penilaians'] as $penilaian)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-{{ $penilaian->nilai >= 80 ? 'success' : ($penilaian->nilai >= 60 ? 'warning' : 'danger') }} text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    {{ $penilaian->nilai ?? '-' }}
                                </div>
                            </div>
                            <div class="ms-3">
                                <div class="fw-bold">{{ $penilaian->peserta->nama_lengkap ?? 'Unknown' }}</div>
                                <div class="text-muted small">{{ $penilaian->kriteria->nama_kriteria }}</div>
                                <div class="text-muted small">by {{ $penilaian->juri->nama_lengkap ?? 'Unknown' }}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">No recent penilaians found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection