@extends('layouts.app')

@section('title', 'Input Penilaian')

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
                    <li class="breadcrumb-item">
                        <a href="{{ route('juri.pesertas') }}" class="text-decoration-none">Daftar Peserta</a>
                    </li>
                    <li class="breadcrumb-item active">Input Penilaian</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Peserta Info Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-success text-white">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1">
                                <i class="bi bi-person-badge me-2"></i>
                                Penilaian: {{ $peserta->nama_lengkap }}
                            </h4>
                            <p class="mb-0 opacity-75">
                                <i class="bi bi-book me-1"></i>
                                Kategori: {{ $peserta->kategori ?? 'Belum ditentukan' }} |
                                <i class="bi bi-building me-1"></i>
                                Instansi: {{ $peserta->instansi ?? '-' }}
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="badge bg-white text-success fs-6">
                                <i class="bi bi-star-fill me-1"></i>
                                {{ $kriterias->count() }} Kriteria
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Indicator -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Progress Penilaian</h6>
                        <span class="badge bg-primary">
                            {{ $existingPenilaians->whereNotNull('nilai')->count() }}/{{ $kriterias->count() }} Selesai
                        </span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar"
                             style="width: {{ ($existingPenilaians->whereNotNull('nilai')->count() / $kriterias->count()) * 100 }}%"
                             aria-valuenow="{{ $existingPenilaians->whereNotNull('nilai')->count() }}"
                             aria-valuemin="0"
                             aria-valuemax="{{ $kriterias->count() }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Penilaian Form -->
    <div class="row">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('juri.save_evaluation', $peserta->id) }}" id="penilaianForm">
                @csrf

                @foreach($kriterias as $kriteria)
                @php
                $existingNilai = $existingPenilaians[$kriteria->id] ?? null;
                $nilai = $existingNilai ? $existingNilai->nilai : '';
                $catatan = $existingNilai ? $existingNilai->catatan : '';
                $isCompleted = $existingNilai && !is_null($existingNilai->nilai);
                @endphp

                <!-- Kriteria Card -->
                <div class="card border-0 shadow-sm mb-4 {{ $isCompleted ? 'border-success' : '' }}">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="mb-0">
                                    <span class="badge bg-primary me-2">{{ $kriteria->bobot }}%</span>
                                    {{ $kriteria->nama_kriteria }}
                                </h5>
                                <small class="text-muted">{{ $kriteria->deskripsi }}</small>
                            </div>
                            <div class="col-md-4 text-md-end">
                                @if($isCompleted)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Selesai
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="bi bi-clock me-1"></i>Belum Dinilai
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Rating Stars + Input -->
                                <label class="form-label fw-bold">Rating Nilai (0-100)</label>
                                <div class="mb-3">
                                    <!-- Star Rating Display -->
                                    <div class="mb-2" id="starRating{{ $kriteria->id }}">
                                        @for($i = 1; $i <= 5; $i++)
                                        @php
                                        $threshold = ($i * 20);
                                        $active = ($nilai >= $threshold);
                                        @endphp
                                        <span class="star-rating fa fa-star {{ $active ? 'text-warning' : 'text-muted' }} fa-2x"
                                              data-value="{{ $i * 20 }}"
                                              data-kriteria="{{ $kriteria->id }}"
                                              style="cursor: pointer; margin-right: 5px;">
                                        </span>
                                        @endfor
                                        <span class="ms-2 text-muted small">Klik bintang untuk set nilai</span>
                                    </div>

                                    <!-- Numeric Input -->
                                    <div class="input-group">
                                        <input type="number"
                                               name="penilaians[{{ $kriteria->id }}][nilai]"
                                               class="form-control form-control-lg"
                                               id="nilai{{ $kriteria->id }}"
                                               min="0"
                                               max="100"
                                               step="1"
                                               value="{{ $nilai }}"
                                               placeholder="0-100"
                                               required>
                                        <span class="input-group-text bg-primary text-white">pts</span>
                                    </div>

                                    <!-- Quick Value Buttons -->
                                    <div class="mt-2">
                                        <small class="text-muted">Quick values:</small>
                                        <div class="btn-group btn-group-sm mt-1" role="group">
                                            <button type="button" class="btn btn-outline-secondary" onclick="setNilai({{ $kriteria->id }}, 60)">60</button>
                                            <button type="button" class="btn btn-outline-secondary" onclick="setNilai({{ $kriteria->id }}, 70)">70</button>
                                            <button type="button" class="btn btn-outline-secondary" onclick="setNilai({{ $kriteria->id }}, 80)">80</button>
                                            <button type="button" class="btn btn-outline-secondary" onclick="setNilai({{ $kriteria->id }}, 90)">90</button>
                                            <button type="button" class="btn btn-outline-secondary" onclick="setNilai({{ $kriteria->id } }, 100)">100</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Catatan -->
                                <label class="form-label fw-bold">Catatan/Komentar</label>
                                <textarea name="penilaians[{{ $kriteria->id }}][catatan]"
                                          class="form-control"
                                          rows="3"
                                          placeholder="Tambahkan catatan untuk {{ $kriteria->nama_kriteria }}...">{{ $catatan }}</textarea>
                                <small class="text-muted">Opsional: tambahkan komentar atau feedback</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Action Buttons -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Pastikan semua kriteria sudah dinilai sebelum menyimpan
                                </small>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <a href="{{ route('juri.dashboard') }}" class="btn btn-secondary me-2">
                                    <i class="bi bi-x-circle me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>Simpan Penilaian
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <!-- Petunjuk Penilaian -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Petunjuk Penilaian
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Skala Nilai:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <span class="badge bg-danger">0-59</span> Kurang
                            </li>
                            <li class="mb-2">
                                <span class="badge bg-warning">60-69</span> Cukup
                            </li>
                            <li class="mb-2">
                                <span class="badge bg-info">70-84</span> Baik
                            </li>
                            <li class="mb-2">
                                <span class="badge bg-success">85-100</span> Sangat Baik
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h6 class="text-primary">Tips:</h6>
                        <ul class="small">
                            <li>Gunakan skala 0-100 untuk setiap kriteria</li>
                            <li>Beri catatan konstruktif jika perlu</li>
                            <li>Objektif dalam penilaian</li>
                            <li>Perhatikan bobot setiap kriteria</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Total Nilai -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-calculator me-2"></i>
                        Total Nilai Sementara
                    </h6>
                </div>
                <div class="card-body text-center">
                    <h3 class="text-primary mb-1" id="totalNilai">0</h3>
                    <p class="text-muted mb-0">dari 100 points</p>
                    <div class="progress mt-3" style="height: 10px;">
                        <div class="progress-bar bg-primary" id="totalProgress" role="progressbar"
                             style="width: 0%"
                             aria-valuenow="0"
                             aria-valuemin="0"
                             aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Histori Penilaian Lain -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Penilaian Juri Lain
                    </h6>
                </div>
                <div class="card-body">
                    @php
                    $otherPenilaians = \App\Models\Penilaian::with('juri')
                        ->where('peserta_id', $peserta->id)
                        ->whereNotNull('nilai')
                        ->whereHas('juri', function($query) {
                            $query->where('id', '!=', auth()->user()->juri->id ?? 0);
                        })
                        ->take(5)
                        ->get();
                    @endphp

                    @if($otherPenilaians->count() > 0)
                        @foreach($otherPenilaians as $penilaian)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">{{ $penilaian->juri->nama_lengkap }}</small>
                            <span class="badge bg-info">{{ $penilaian->nilai }}</span>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center mb-0">
                            <i class="bi bi-people fa-2x mb-2"></i><br>
                            Belum ada penilaian dari juri lain
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .star-rating {
        transition: color 0.2s ease;
    }
    .star-rating:hover {
        color: #ffc107 !important;
    }
    .card.border-success {
        border-left: 4px solid #28a745 !important;
    }
    .breadcrumb-item + .breadcrumb-item::before {
        content: ">";
        color: #6c757d;
    }
</style>
@endpush

@push('scripts')
<script>
// Set nilai function
function setNilai(kriteriaId, value) {
    document.getElementById('nilai' + kriteriaId).value = value;
    updateStarRating(kriteriaId, value);
    calculateTotal();
}

// Update star rating based on numeric input
function updateStarRating(kriteriaId, nilai) {
    const stars = document.querySelectorAll(`#starRating${kriteriaId} .star-rating`);
    stars.forEach((star, index) => {
        const threshold = (index + 1) * 20;
        if (nilai >= threshold) {
            star.classList.remove('text-muted');
            star.classList.add('text-warning');
        } else {
            star.classList.remove('text-warning');
            star.classList.add('text-muted');
        }
    });
}

// Star rating click handler
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.star-rating').forEach(star => {
        star.addEventListener('click', function() {
            const kriteriaId = this.dataset.kriteria;
            const value = this.dataset.value;
            setNilai(kriteriaId, value);
        });
    });

    // Calculate initial total
    calculateTotal();

    // Add input change listeners
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', function() {
            const kriteriaId = this.id.replace('nilai', '');
            updateStarRating(kriteriaId, this.value);
            calculateTotal();
        });
    });
});

// Calculate total weighted score
function calculateTotal() {
    const kriterias = [
        { id: '{{ $kriterias->firstWhere("nama_kriteria", "Tajwid")->id ?? 1 }}', bobot: 30 },
        { id: '{{ $kriterias->firstWhere("nama_kriteria", "Kelancaran")->id ?? 2 }}', bobot: 25 },
        { id: '{{ $kriterias->firstWhere("nama_kriteria", "Fasohah")->id ?? 3 }}', bobot: 20 },
        { id: '{{ $kriterias->firstWhere("nama_kriteria", "Adab")->id ?? 4 }}', bobot: 15 },
        { id: '{{ $kriterias->firstWhere("nama_kriteria", "Tartil")->id ?? 5 }}', bobot: 10 }
    ];

    let totalScore = 0;
    let totalBobot = 0;

    kriterias.forEach(kriteria => {
        const nilai = parseInt(document.getElementById('nilai' + kriteria.id)?.value || 0);
        totalScore += (nilai * kriteria.bobot) / 100;
        totalBobot += kriteria.bobot;
    });

    const finalScore = Math.round(totalScore);

    // Update display
    document.getElementById('totalNilai').textContent = finalScore;
    document.getElementById('totalProgress').style.width = finalScore + '%';
    document.getElementById('totalProgress').setAttribute('aria-valuenow', finalScore);

    // Update progress bar color based on score
    const progressBar = document.getElementById('totalProgress');
    progressBar.classList.remove('bg-danger', 'bg-warning', 'bg-info', 'bg-success');

    if (finalScore >= 85) {
        progressBar.classList.add('bg-success');
    } else if (finalScore >= 70) {
        progressBar.classList.add('bg-info');
    } else if (finalScore >= 60) {
        progressBar.classList.add('bg-warning');
    } else {
        progressBar.classList.add('bg-danger');
    }
}

// Form validation
document.getElementById('penilaianForm').addEventListener('submit', function(e) {
    const inputs = document.querySelectorAll('input[type="number"]');
    let hasEmpty = false;

    inputs.forEach(input => {
        if (!input.value || input.value < 0 || input.value > 100) {
            hasEmpty = true;
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    });

    if (hasEmpty) {
        e.preventDefault();
        alert('Mohon lengkapi semua nilai (0-100) sebelum menyimpan!');
    }
});
</script>
@endpush