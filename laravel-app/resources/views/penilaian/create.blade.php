@extends('layouts.app')

@section('title', 'Input Penilaian')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-success text-white">
                <h6 class="m-0">
                    <i class="bi bi-clipboard-plus me-2"></i>Input Penilaian MHQ
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('penilaian.store') }}" method="POST" id="penilaianForm">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="juri_id" class="form-label">
                                <i class="bi bi-person-badge me-1"></i>Nama Juri <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="juri_id" name="juri_id" required>
                                <option value="">-- Pilih Juri --</option>
                                @foreach($juris as $juri)
                                    <option value="{{ $juri->id }}" {{ old('juri_id') == $juri->id ? 'selected' : '' }}>
                                        {{ $juri->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="peserta_id" class="form-label">
                                <i class="bi bi-person me-1"></i>Nama Peserta <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="peserta_id" name="peserta_id" required>
                                <option value="">-- Pilih Peserta --</option>
                                @foreach($pesertas as $peserta)
                                    <option value="{{ $peserta->id }}" {{ old('peserta_id') == $peserta->id ? 'selected' : '' }}>
                                        {{ $peserta->nama_lengkap }} - {{ $peserta->nomor_peserta }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="bi bi-info-circle me-1"></i>Status
                            </label>
                            <div id="selectionStatus" class="alert alert-secondary py-2 mb-0">
                                <small>Silakan pilih Juri dan Peserta terlebih dahulu</small>
                            </div>
                        </div>
                    </div>

                    <!-- Assessment Table -->
                    <div id="assessmentSection" style="display: none;">
                        <h5 class="mb-3">
                            <i class="bi bi-clipboard-check me-2"></i>Kriteria Penilaian
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="25%">Kriteria</th>
                                        <th width="10%">Bobot</th>
                                        <th width="15%">Rentang Nilai</th>
                                        <th width="10%">Nilai</th>
                                        <th width="35%">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody id="kriteriaTableBody">
                                    @foreach($kriterias as $index => $kriteria)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $kriteria->nama_kriteria }}</strong><br>
                                            <small class="text-muted">{{ $kriteria->deskripsi }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $kriteria->bobot }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $kriteria->min }} - {{ $kriteria->max }}</small>
                                        </td>
                                        <td>
                                            <input type="number"
                                                   class="form-control nilai-input"
                                                   name="nilai[{{ $kriteria->id }}]"
                                                   min="{{ $kriteria->min }}"
                                                   max="{{ $kriteria->max }}"
                                                   step="0.1"
                                                   data-kriteria="{{ $kriteria->nama_kriteria }}"
                                                   data-min="{{ $kriteria->min }}"
                                                   data-max="{{ $kriteria->max }}"
                                                   required>
                                        </td>
                                        <td>
                                            <textarea class="form-control"
                                                      name="catatan[{{ $kriteria->id }}]"
                                                      rows="1"
                                                      placeholder="Catatan opsional..."></textarea>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-primary">
                                        <td colspan="2"><strong>Total</strong></td>
                                        <td><strong>{{ $kriterias->sum('bobot') }}</strong></td>
                                        <td colspan="2">
                                            <strong id="totalNilai">0</strong>
                                            <br><small class="text-muted">Rata-rata: <span id="rataNilai">0</span></small>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Validation Status -->
                        <div id="validationStatus" class="alert alert-warning" style="display: none;">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Perhatian:</strong> Semua nilai harus diisi sesuai rentang yang ditentukan.
                        </div>

                        <!-- Quick Actions -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="setAllMax()">
                                    <i class="bi bi-arrow-up-circle me-1"></i>Set Nilai Maksimum
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAllMin()">
                                    <i class="bi bi-arrow-down-circle me-1"></i>Set Nilai Minimum
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="setAllAverage()">
                                    <i class="bi bi-dash-circle me-1"></i>Set Nilai Rata-rata
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearAllValues()">
                                    <i class="bi bi-x-circle me-1"></i>Hapus Semua Nilai
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                                    <i class="bi bi-save me-2"></i>Simpan Penilaian
                                </button>
                                <a href="{{ route('penilaian.index') }}" class="btn btn-secondary ms-2">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                                </a>
                            </div>
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
document.addEventListener('DOMContentLoaded', function() {
    const juriSelect = document.getElementById('juri_id');
    const pesertaSelect = document.getElementById('peserta_id');
    const assessmentSection = document.getElementById('assessmentSection');
    const selectionStatus = document.getElementById('selectionStatus');
    const submitBtn = document.getElementById('submitBtn');
    const nilaiInputs = document.querySelectorAll('.nilai-input');

    // Check existing assessments
    function checkExistingAssessment() {
        const juriId = juriSelect.value;
        const pesertaId = pesertaSelect.value;

        if (juriId && pesertaId) {
            fetch(`/api/penilaian/check?juri_id=${juriId}&peserta_id=${pesertaId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        selectionStatus.className = 'alert alert-warning py-2 mb-0';
                        selectionStatus.innerHTML = `
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <strong>Perhatian:</strong> Penilaian untuk juri dan peserta ini sudah ada.
                            Mengisi akan mengupdate data yang ada.
                        `;
                        // Load existing values if any
                        if (data.penilaians) {
                            data.penilaians.forEach(penilaian => {
                                const input = document.querySelector(`input[name="nilai[${penilaian.kriteria_id}]"]`);
                                const textarea = document.querySelector(`textarea[name="catatan[${penilaian.kriteria_id}]"]`);
                                if (input) input.value = penilaian.nilai;
                                if (textarea) textarea.value = penilaian.catatan || '';
                            });
                        }
                    } else {
                        selectionStatus.className = 'alert alert-success py-2 mb-0';
                        selectionStatus.innerHTML = `
                            <i class="bi bi-check-circle me-1"></i>
                            <strong>Siap:</strong> Belum ada penilaian untuk kombinasi ini.
                        `;
                    }
                    assessmentSection.style.display = 'block';
                    updateSubmitButton();
                    calculateTotal();
                })
                .catch(error => {
                    console.error('Error checking assessment:', error);
                });
        } else {
            assessmentSection.style.display = 'none';
            selectionStatus.className = 'alert alert-secondary py-2 mb-0';
            selectionStatus.innerHTML = '<small>Silakan pilih Juri dan Peserta terlebih dahulu</small>';
        }
    }

    // Calculate total and average
    function calculateTotal() {
        let total = 0;
        let count = 0;

        nilaiInputs.forEach(input => {
            const value = parseFloat(input.value) || 0;
            total += value;
            if (value > 0) count++;
        });

        const average = count > 0 ? total / count : 0;

        document.getElementById('totalNilai').textContent = total.toFixed(2);
        document.getElementById('rataNilai').textContent = average.toFixed(2);
    }

    // Validate input ranges
    function validateInput(input) {
        const min = parseFloat(input.dataset.min);
        const max = parseFloat(input.dataset.max);
        const value = parseFloat(input.value);
        const kriteria = input.dataset.kriteria;

        if (value < min || value > max) {
            input.classList.add('is-invalid');
            showValidationMessage(`Nilai untuk ${kriteria} harus antara ${min} - ${max}`);
            return false;
        } else {
            input.classList.remove('is-invalid');
            hideValidationMessage();
            return true;
        }
    }

    function showValidationMessage(message) {
        const status = document.getElementById('validationStatus');
        status.querySelector('strong').parentElement.innerHTML = `
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Perhatian:</strong> ${message}
        `;
        status.style.display = 'block';
    }

    function hideValidationMessage() {
        document.getElementById('validationStatus').style.display = 'none';
    }

    // Update submit button
    function updateSubmitButton() {
        const allValid = Array.from(nilaiInputs).every(input => {
            return validateInput(input) && input.value !== '';
        });
        submitBtn.disabled = !allValid || !juriSelect.value || !pesertaSelect.value;
    }

    // Event listeners
    juriSelect.addEventListener('change', checkExistingAssessment);
    pesertaSelect.addEventListener('change', checkExistingAssessment);

    nilaiInputs.forEach(input => {
        input.addEventListener('input', function() {
            validateInput(this);
            calculateTotal();
            updateSubmitButton();
        });
    });
});

// Quick action functions
function setAllMax() {
    document.querySelectorAll('.nilai-input').forEach(input => {
        input.value = input.dataset.max;
        input.dispatchEvent(new Event('input'));
    });
}

function setAllMin() {
    document.querySelectorAll('.nilai-input').forEach(input => {
        input.value = input.dataset.min;
        input.dispatchEvent(new Event('input'));
    });
}

function setAllAverage() {
    document.querySelectorAll('.nilai-input').forEach(input => {
        const min = parseFloat(input.dataset.min);
        const max = parseFloat(input.dataset.max);
        input.value = ((min + max) / 2).toFixed(1);
        input.dispatchEvent(new Event('input'));
    });
}

function clearAllValues() {
    document.querySelectorAll('.nilai-input').forEach(input => {
        input.value = '';
        input.dispatchEvent(new Event('input'));
    });
    document.querySelectorAll('textarea').forEach(textarea => {
        textarea.value = '';
    });
}
</script>
@endpush