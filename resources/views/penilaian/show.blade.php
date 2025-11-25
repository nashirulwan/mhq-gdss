@extends('layouts.app')

@section('title', 'Detail Penilaian')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-info text-white">
                <h6 class="m-0">
                    <i class="bi bi-clipboard-data me-2"></i>Detail Penilaian
                </h6>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Informasi Penilai</h6>
                        <table class="table table-sm">
                            <tr>
                                <td width="30%"><strong>Nama Juri:</strong></td>
                                <td>{{ $penilaian->juri->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td><strong>Waktu Penilaian:</strong></td>
                                <td>{{ $penilaian->created_at->format('d F Y, H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Informasi Peserta</h6>
                        <table class="table table-sm">
                            <tr>
                                <td width="30%"><strong>Nama Peserta:</strong></td>
                                <td>{{ $penilaian->peserta->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nomor Peserta:</strong></td>
                                <td><span class="badge bg-secondary">{{ $penilaian->peserta->nomor_peserta }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Instansi:</strong></td>
                                <td>{{ $penilaian->peserta->instansi }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <h6>Detail Kriteria</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td width="20%"><strong>Kriteria:</strong></td>
                                    <td>
                                        <span class="badge bg-primary">{{ $penilaian->kriteria->nama_kriteria }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Deskripsi:</strong></td>
                                    <td>{{ $penilaian->kriteria->deskripsi }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Bobot:</strong></td>
                                    <td>
                                        <span class="badge bg-warning">{{ $penilaian->kriteria->bobot }}%</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Rentang Nilai:</strong></td>
                                    <td>{{ $penilaian->kriteria->min }} - {{ $penilaian->kriteria->max }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <h6>Hasil Penilaian</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="text-primary">{{ $penilaian->nilai }}</h5>
                                        <p class="mb-0">Nilai</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="text-warning">{{ $penilaian->kriteria->bobot }}%</h5>
                                        <p class="mb-0">Bobot</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="text-success">{{ number_format($penilaian->nilai * $penilaian->kriteria->bobot / 100, 2) }}</h5>
                                        <p class="mb-0">Nilai Terbobot</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($penilaian->catatan)
                <div class="row mb-4">
                    <div class="col-12">
                        <h6>Catatan Penilaian</h6>
                        <div class="alert alert-light">
                            <p class="mb-0">{{ $penilaian->catatan }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('penilaian.edit', $penilaian->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Edit Penilaian
                        </a>
                        <form action="{{ route('penilaian.destroy', $penilaian->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Yakin ingin menghapus penilaian ini?')">
                                <i class="bi bi-trash me-2"></i>Hapus Penilaian
                            </button>
                        </form>
                        <a href="{{ route('penilaian.create', ['peserta_id' => $penilaian->peserta_id, 'juri_id' => $penilaian->juri_id]) }}" class="btn btn-success ms-2">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Penilaian Lain
                        </a>
                        <a href="{{ route('peserta.show', $penilaian->peserta_id) }}" class="btn btn-info ms-2">
                            <i class="bi bi-person me-2"></i>Lihat Profil Peserta
                        </a>
                        <a href="{{ route('penilaian.index') }}" class="btn btn-secondary ms-2">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Other Assessments -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-secondary text-white">
                <h6 class="m-0">
                    <i class="bi bi-list-check me-2"></i>Penilaian Lain untuk Peserta Ini
                </h6>
            </div>
            <div class="card-body">
                @php
                    $otherPenilaians = App\Models\Penilaian::where('peserta_id', $penilaian->peserta_id)
                        ->where('id', '!=', $penilaian->id)
                        ->with(['juri', 'kriteria'])
                        ->get();
                @endphp
                @if($otherPenilaians->isNotEmpty())
                    @foreach($otherPenilaians as $other)
                    <div class="mb-3 p-2 border rounded">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted">{{ $other->juri->nama_lengkap }}</small><br>
                                <strong>{{ $other->kriteria->nama_kriteria }}</strong><br>
                                <span class="badge bg-primary">{{ $other->nilai }}</span>
                                <small class="text-muted">({{ $other->kriteria->bobot }}% bobot)</small>
                            </div>
                            <a href="{{ route('penilaian.show', $other->id) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted">
                        <i class="bi bi-clipboard-x fa-3x mb-3"></i>
                        <p>Belum ada penilaian lain untuk peserta ini</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Completion Status -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-success text-white">
                <h6 class="m-0">
                    <i class="bi bi-check-circle me-2"></i>Status Kelengkapan
                </h6>
            </div>
            <div class="card-body">
                @php
                    $totalExpected = App\Models\Kriteria::where('is_active', true)->count() * App\Models\Juri::where('is_active', true)->count();
                    $pesertaPenilaians = App\Models\Penilaian::where('peserta_id', $penilaian->peserta_id)->count();
                @endphp
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Progress Penilaian</span>
                        <strong>{{ $pesertaPenilaians }} / {{ $totalExpected }}</strong>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: {{ ($pesertaPenilaians / $totalExpected) * 100 }}%"></div>
                    </div>
                </div>
                <div class="text-center">
                    @if($pesertaPenilaians >= $totalExpected)
                        <span class="badge bg-success">✓ Lengkap</span>
                    @else
                        <span class="badge bg-warning">⚠ Belum Lengkap</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection