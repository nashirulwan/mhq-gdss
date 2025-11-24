@extends('layouts.app')

@section('title', 'Edit Penilaian')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-warning text-white">
                <h6 class="m-0">
                    <i class="bi bi-pencil-square me-2"></i>Edit Penilaian
                </h6>
            </div>
            <div class="card-body">
                <!-- Info Section -->
                <div class="alert alert-info mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Juri:</strong> {{ $penilaian->juri->nama_lengkap }}
                        </div>
                        <div class="col-md-3">
                            <strong>Peserta:</strong> {{ $penilaian->peserta->nama_lengkap }}
                        </div>
                        <div class="col-md-3">
                            <strong>Kriteria:</strong> {{ $penilaian->kriteria->nama_kriteria }}
                        </div>
                        <div class="col-md-3">
                            <strong>Bobot:</strong> {{ $penilaian->kriteria->bobot }}
                        </div>
                    </div>
                </div>

                <form action="{{ route('penilaian.update', $penilaian->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-sliders me-2"></i>Input Nilai
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="nilai" class="form-label">
                                            <strong>Nilai</strong>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="number"
                                               class="form-control form-control-lg"
                                               id="nilai"
                                               name="nilai"
                                               value="{{ old('nilai', $penilaian->nilai) }}"
                                               min="{{ $penilaian->kriteria->min }}"
                                               max="{{ $penilaian->kriteria->max }}"
                                               step="0.1"
                                               required>
                                        <div class="form-text">
                                            Rentang nilai: {{ $penilaian->kriteria->min }} - {{ $penilaian->kriteria->max }}
                                        </div>
                                    </div>

                                    <!-- Visual Scale -->
                                    <div class="mb-3">
                                        <label class="form-label">Skala Visual:</label>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-center">
                                                <div class="badge bg-danger p-2">{{ $penilaian->kriteria->min }}</div>
                                                <br><small>Kurang</small>
                                            </div>
                                            <div class="text-center">
                                                <div class="badge bg-warning p-2">{{ ($penilaian->kriteria->min + $penilaian->kriteria->max) / 2 }}</div>
                                                <br><small>Cukup</small>
                                            </div>
                                            <div class="text-center">
                                                <div class="badge bg-success p-2">{{ $penilaian->kriteria->max }}</div>
                                                <br><small>Sangat Baik</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quick Values -->
                                    <div class="mb-3">
                                        <label class="form-label">Nilai Cepat:</label>
                                        <div class="btn-group w-100" role="group">
                                            <button type="button" class="btn btn-outline-danger" onclick="setNilai({{ $penilaian->kriteria->min }})">
                                                {{ $penilaian->kriteria->min }}
                                            </button>
                                            <button type="button" class="btn btn-outline-warning" onclick="setNilai({{ ($penilaian->kriteria->min + $penilaian->kriteria->max) / 2 }})">
                                                {{ number_format(($penilaian->kriteria->min + $penilaian->kriteria->max) / 2, 1) }}
                                            </button>
                                            <button type="button" class="btn btn-outline-success" onclick="setNilai({{ $penilaian->kriteria->max }})">
                                                {{ $penilaian->kriteria->max }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-chat-text me-2"></i>Catatan Penilaian
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="catatan" class="form-label">Catatan</label>
                                        <textarea class="form-control"
                                                  id="catatan"
                                                  name="catatan"
                                                  rows="8"
                                                  placeholder="Tambahkan catatan penilaian...">{{ old('catatan', $penilaian->catatan) }}</textarea>
                                    </div>

                                    <!-- Suggested Comments -->
                                    <div class="mb-3">
                                        <label class="form-label">Komentar Standar:</label>
                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addCatatan('Penguasaan materi baik')">
                                                Penguasaan materi baik
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addCatatan('Perlu perbaikan di beberapa aspek')">
                                                Perlu perbaikan di beberapa aspek
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addCatatan('Sangat memuaskan, maksimal')">
                                                Sangat memuaskan, maksimal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Calculation Info -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-light">
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <strong>Nilai:</strong> <span id="displayNilai">{{ $penilaian->nilai }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Bobot:</strong> {{ $penilaian->kriteria->bobot }}%
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Nilai Terbobot:</strong> <span id="displayTerbobot">{{ number_format($penilaian->nilai * $penilaian->kriteria->bobot / 100, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save me-2"></i>Update Penilaian
                            </button>
                            <a href="{{ route('penilaian.show', $penilaian->id) }}" class="btn btn-info ms-2">
                                <i class="bi bi-eye me-2"></i>Lihat Detail
                            </a>
                            <a href="{{ route('penilaian.index') }}" class="btn btn-secondary ms-2">
                                <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const nilaiInput = document.getElementById('nilai');
const catatanTextarea = document.getElementById('catatan');
const bobot = {{ $penilaian->kriteria->bobot }};

function setNilai(value) {
    nilaiInput.value = value;
    updateCalculation();
}

function addCatatan(text) {
    if (catatanTextarea.value) {
        catatanTextarea.value += '\n';
    }
    catatanTextarea.value += text;
}

function updateCalculation() {
    const nilai = parseFloat(nilaiInput.value) || 0;
    const terbobot = (nilai * bobot / 100).toFixed(2);

    document.getElementById('displayNilai').textContent = nilai;
    document.getElementById('displayTerbobot').textContent = terbobot;
}

// Real-time calculation update
nilaiInput.addEventListener('input', updateCalculation);

// Validate range
nilaiInput.addEventListener('input', function() {
    const min = parseFloat(this.min);
    const max = parseFloat(this.max);
    const value = parseFloat(this.value);

    if (value < min) {
        this.value = min;
    } else if (value > max) {
        this.value = max;
    }

    updateCalculation();
});

// Initialize calculation
updateCalculation();
</script>
@endpush