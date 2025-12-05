@extends('layouts.app')

@section('title', 'Data Juri')

@section('page-actions')
    <a href="{{ route('juri.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Tambah Juri
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-gavel me-2"></i>Data Juri MHQ
                </h6>
                <div>
                    <span class="badge bg-info">{{ $juris->total() }} Juri</span>
                </div>
            </div>
            <div class="card-body">
                @if($juris->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Instansi</th>
                                    <th>Keahlian</th>
                                    <th>Kontak</th>
                                    <th>Jml Penilaian</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($juris as $index => $juri)
                                <tr>
                                    <td>{{ $juris->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $juri->nama_lengkap }}</strong>
                                    </td>
                                    <td>{{ $juri->instansi }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $juri->keahlian }}</span>
                                    </td>
                                    <td>
                                        <a href="tel:{{ $juri->kontak }}" class="text-decoration-none">
                                            <i class="bi bi-telephone me-1"></i>{{ $juri->kontak }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <span class="badge {{ $juri->penilaians_count > 0 ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $juri->penilaians_count }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($juri->is_active)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-x-circle me-1"></i>Tidak Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="table-actions">
                                        <a href="{{ route('juri.show', $juri->id) }}"
                                           class="btn btn-sm btn-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('juri.edit', $juri->id) }}"
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('juri.destroy', $juri->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin ingin menghapus juri {{ $juri->nama_lengkap }}?')"
                                                    title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Menampilkan {{ $juris->firstItem() }} - {{ $juris->lastItem() }}
                            dari {{ $juris->total() }} juri
                        </div>
                        {{ $juris->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-gavel fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Belum Ada Data Juri</h4>
                        <p class="text-muted">Silakan tambahkan juri untuk memulai proses penilaian MHQ</p>
                        <a href="{{ route('juri.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Juri Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($juris->count() > 0)
<!-- Quick Stats -->
<div class="row">
    <div class="col-md-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Juri
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $juris->total() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-gavel fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Juri Aktif
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ \App\Models\Juri::where('is_active', true)->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Sudah Menilai
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ \App\Models\Juri::whereHas('penilaians')->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-clipboard-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Keahlian
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ App\Models\Juri::distinct('keahlian')->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-star-fill fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    .table-actions {
        white-space: nowrap;
    }
    .table-actions .btn {
        margin: 0 2px;
    }
    .border-left-primary { border-left: 4px solid #4e73df !important; }
    .border-left-success { border-left: 4px solid #1cc88a !important; }
    .border-left-info { border-left: 4px solid #36b9cc !important; }
    .border-left-warning { border-left: 4px solid #f6c23e !important; }
    .text-gray-300 { color: #dddfeb !important; }
    .text-gray-800 { color: #5a5c69 !important; }
</style>
@endpush