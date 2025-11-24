@extends('layouts.app')

@section('title', 'Detail Peserta')

@section('content')
<div class="row">
    <!-- Participant Info Card -->
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-info text-white">
                <h6 class="m-0">
                    <i class="bi bi-person-badge me-2"></i>Detail Peserta
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Nomor Peserta:</strong><br>
                        <span class="badge bg-secondary fs-6">{{ $peserta->nomor_peserta }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Nama Lengkap:</strong><br>
                        <h5 class="text-primary">{{ $peserta->nama_lengkap }}</h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Instansi:</strong><br>
                        {{ $peserta->instansi }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Usia:</strong><br>
                        {{ $peserta->usia }} tahun
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Kategori MHQ:</strong><br>
                        <span class="badge {{ $peserta->kategori == '30 Juz' ? 'bg-danger' : ($peserta->kategori == '20 Juz' ? 'bg-warning' : ($peserta->kategori == '10 Juz' ? 'bg-info' : 'bg-primary')) }}">
                            {{ $peserta->kategori }}
                        </span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Kontak:</strong><br>
                        {{ $peserta->kontak ?: '-' }}
                    </div>
                </div>

                @if($peserta->keterangan)
                <div class="row">
                    <div class="col-12 mb-3">
                        <strong>Keterangan:</strong><br>
                        <span class="text-muted">{{ $peserta->keterangan }}</span>
                    </div>
                </div>
                @endif

                <hr>

                <!-- Scoring Status -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="text-center">
                            <h6 class="text-muted">Total Penilaian</h6>
                            <h4 class="text-primary">{{ $peserta->penilaians_count }}</h4>
                            <small class="text-muted">dari {{ App\Models\Kriteria::where('is_active', true)->count() * App\Models\Juri::where('is_active', true)->count() }} yang diharapkan</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center">
                            <h6 class="text-muted">Status</h6>
                            @if($peserta->penilaians_count > 0)
                                <h4><span class="badge bg-success">Dinilai</span></h4>
                            @else
                                <h4><span class="badge bg-warning">Belum Dinilai</span></h4>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center">
                            <h6 class="text-muted">Nilai SMART</h6>
                            @if($peserta->nilai_akhir_smart)
                                <h4 class="text-success">{{ number_format($peserta->nilai_akhir_smart, 3) }}</h4>
                            @else
                                <h4 class="text-muted">-</h4>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('peserta.edit', $peserta->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Edit Peserta
                        </a>
                        @if($peserta->penilaians_count == 0)
                        <form action="{{ route('peserta.destroy', $peserta->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Yakin ingin menghapus peserta {{ $peserta->nama_lengkap }}?')">
                                <i class="bi bi-trash me-2"></i>Hapus Peserta
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('penilaian.create', ['peserta_id' => $peserta->id]) }}" class="btn btn-success">
                            <i class="bi bi-clipboard-plus me-2"></i>Tambah Penilaian
                        </a>
                        <a href="{{ route('peserta.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Card -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-success text-white">
                <h6 class="m-0">
                    <i class="bi bi-graph-up me-2"></i>Statistik Penilaian
                </h6>
            </div>
            <div class="card-body">
                @if($peserta->penilaians->isNotEmpty())
                    <h6>Penilaian per Juri:</h6>
                    @foreach($peserta->penilaians->groupBy('juri_id') as $juriId => $penilaians)
                        @php $juri = App\Models\Juri::find($juriId); @endphp
                        <div class="mb-3">
                            <small class="text-muted">{{ $juri->nama_lengkap }}</small><br>
                            <span class="badge bg-info">{{ $penilaians->count() }} kriteria</span>
                            @if($penilaians->avg('nilai'))
                                <span class="badge bg-primary">Rata-rata: {{ number_format($penilaians->avg('nilai'), 2) }}</span>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-muted">
                        <i class="bi bi-clipboard-x fa-3x mb-3"></i>
                        <p>Belum ada penilaian</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Assessments -->
        @if($peserta->penilaians->isNotEmpty())
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0">
                    <i class="bi bi-clock-history me-2"></i>Penilaian Terbaru
                </h6>
            </div>
            <div class="card-body">
                @foreach($peserta->penilaians->take(5) as $penilaian)
                <div class="mb-2 pb-2 border-bottom">
                    <small class="text-muted">{{ $penilaian->created_at->format('d M Y H:i') }}</small><br>
                    <strong>{{ $penilaian->juri->nama_lengkap }}</strong> -
                    <span class="badge bg-info">{{ $penilaian->kriteria->nama_kriteria }}</span><br>
                    <strong>Nilai: {{ $penilaian->nilai }}</strong>
                    @if($penilaian->catatan)
                        <br><small class="text-muted">{{ $penilaian->catatan }}</small>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection