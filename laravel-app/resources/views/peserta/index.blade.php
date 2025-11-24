@extends('layouts.app')

@section('title', 'Data Peserta')

@section('page-actions')
    <a href="{{ route('peserta.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Tambah Peserta
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-people-fill me-2"></i>Data Peserta MHQ
                </h6>
                <div>
                    <span class="badge bg-info">{{ $pesertas->total() }} Peserta</span>
                </div>
            </div>
            <div class="card-body">
                @if($pesertas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Peserta</th>
                                    <th>Nama Lengkap</th>
                                    <th>Instansi</th>
                                    <th>Kategori</th>
                                    <th>Usia</th>
                                    <th>Jml Penilaian</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesertas as $index => $peserta)
                                <tr>
                                    <td>{{ $pesertas->firstItem() + $index }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $peserta->nomor_peserta }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $peserta->nama_lengkap }}</strong>
                                        @if($peserta->keterangan)
                                            <br><small class="text-muted">{{ $peserta->keterangan }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $peserta->instansi }}</td>
                                    <td>
                                        <span class="badge {{ $peserta->kategori == '30 Juz' ? 'bg-danger' : ($peserta->kategori == '20 Juz' ? 'bg-warning' : ($peserta->kategori == '10 Juz' ? 'bg-info' : 'bg-primary')) }}">
                                            {{ $peserta->kategori }}
                                        </span>
                                    </td>
                                    <td>{{ $peserta->usia }} tahun</td>
                                    <td>
                                        <div class="text-center">
                                            <span class="badge {{ $peserta->penilaians_count > 0 ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $peserta->penilaians_count }} / {{ App\Models\Kriteria::where('is_active', true)->count() * App\Models\Juri::where('is_active', true)->count() }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($peserta->penilaians_count > 0)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Dinilai
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="bi bi-clock me-1"></i>Belum Dinilai
                                            </span>
                                        @endif
                                        @if($peserta->nilai_akhir_smart)
                                            <span class="badge bg-primary ms-1">
                                                <i class="bi bi-calculator me-1"></i>{{ number_format($peserta->nilai_akhir_smart, 3) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="table-actions">
                                        <a href="{{ route('peserta.show', $peserta->id) }}"
                                           class="btn btn-sm btn-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('peserta.edit', $peserta->id) }}"
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('peserta.destroy', $peserta->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin ingin menghapus peserta {{ $peserta->nama_lengkap }}?')"
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
                            Menampilkan {{ $pesertas->firstItem() }} - {{ $pesertas->lastItem() }}
                            dari {{ $pesertas->total() }} peserta
                        </div>
                        {{ $pesertas->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-people fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Belum Ada Data Peserta</h4>
                        <p class="text-muted">Silakan tambahkan peserta untuk memulai proses penilaian MHQ</p>
                        <a href="{{ route('peserta.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Peserta Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($pesertas->count() > 0)
<!-- Quick Stats -->
<div class="row">
    <div class="col-md-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Peserta
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pesertas->total() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people-fill fa-2x text-gray-300"></i>
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
                            Sudah Dinilai
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ \App\Models\Peserta::whereHas('penilaians')->count() }}
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
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Belum Dinilai
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ \App\Models\Peserta::whereDoesntHave('penilaians')->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-clock fa-2x text-gray-300"></i>
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
                            Kategori Tersedia
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ App\Models\Peserta::distinct('kategori')->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-tags-fill fa-2x text-gray-300"></i>
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