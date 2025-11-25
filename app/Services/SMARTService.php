<?php

namespace App\Services;

use App\Models\Peserta;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Juri;

class SMARTService
{
    /**
     * Implementasi metode SMART untuk perhitungan nilai akhir
     * Simple Multi-Attribute Rating Technique
     */
    public function hitungNilaiSMART($peserta_id = null)
    {
        $pesertas = $peserta_id ? Peserta::where('id', $peserta_id)->get() : Peserta::all();
        $kriterias = Kriteria::where('is_active', true)->get();

        foreach ($pesertas as $peserta) {
            $totalNilaiTerbobot = 0;

            foreach ($kriterias as $kriteria) {
                // 1. Normalisasi nilai
                $nilaiNormalisasi = $this->normalisasiNilai($peserta->id, $kriteria->id);

                // 2. Hitung nilai terbobot
                $bobotNormalisasi = $kriteria->getBobotNormalisasi();
                $nilaiTerbobot = $nilaiNormalisasi * $bobotNormalisasi;

                // 3. Update data penilaian
                $penilaian = Penilaian::where('peserta_id', $peserta->id)
                    ->where('kriteria_id', $kriteria->id)
                    ->first();

                if ($penilaian) {
                    $penilaian->update([
                        'nilai_normalisasi' => $nilaiNormalisasi,
                        'nilai_terbobot' => $nilaiTerbobot
                    ]);
                }

                $totalNilaiTerbobot += $nilaiTerbobot;
            }

            // 4. Update nilai akhir peserta
            $peserta->update([
                'nilai_akhir_smart' => $totalNilaiTerbobot
            ]);
        }

        return $pesertas;
    }

    /**
     * Normalisasi nilai untuk setiap kriteria
     * Rumus: (nilai - min) / (max - min) untuk benefit
     */
    private function normalisasiNilai($peserta_id, $kriteria_id)
    {
        $penilaianPeserta = Penilaian::where('peserta_id', $peserta_id)
            ->where('kriteria_id', $kriteria_id)
            ->first();

        if (!$penilaianPeserta) {
            return 0;
        }

        // Cari nilai maksimal dan minimal untuk kriteria ini
        $maxNilai = Penilaian::where('kriteria_id', $kriteria_id)
            ->max('nilai');

        $minNilai = Penilaian::where('kriteria_id', $kriteria_id)
            ->min('nilai');

        if ($maxNilai - $minNilai == 0) {
            return 0;
        }

        $kriteria = Kriteria::find($kriteria_id);
        $nilai = $penilaianPeserta->nilai;

        if ($kriteria->atribut === 'benefit') {
            // Semakin besar semakin baik
            return ($nilai - $minNilai) / ($maxNilai - $minNilai);
        } else {
            // Semakin kecil semakin baik (cost)
            return ($maxNilai - $nilai) / ($maxNilai - $minNilai);
        }
    }

    /**
     * Ranking peserta berdasarkan nilai SMART
     */
    public function rankingPeserta()
    {
        $pesertas = Peserta::whereNotNull('nilai_akhir_smart')
            ->orderBy('nilai_akhir_smart', 'desc')
            ->get();

        $peringkat = 1;
        foreach ($pesertas as $peserta) {
            $peserta->update(['peringkat' => $peringkat]);
            $peringkat++;
        }

        return $pesertas;
    }

    /**
     * Get matriks keputusan untuk visualisasi
     */
    public function getMatriksKeputusan()
    {
        $pesertas = Peserta::with(['penilaians.kriteria'])->get();
        $kriterias = Kriteria::where('is_active', true)->get();

        $matriks = [];
        foreach ($pesertas as $peserta) {
            $row = [
                'peserta_id' => $peserta->id,
                'nama_peserta' => $peserta->nama_lengkap,
                'nomor_peserta' => $peserta->nomor_peserta,
                'nilai_akhir_smart' => $peserta->nilai_akhir_smart,
                'peringkat' => $peserta->peringkat
            ];

            foreach ($kriterias as $kriteria) {
                $penilaian = $peserta->penilaians()
                    ->where('kriteria_id', $kriteria->id)
                    ->first();

                $row['kriteria_' . $kriteria->id] = [
                    'nama_kriteria' => $kriteria->nama_kriteria,
                    'nilai_asli' => $penilaian ? $penilaian->nilai : 0,
                    'nilai_normalisasi' => $penilaian ? $penilaian->nilai_normalisasi : 0,
                    'nilai_terbobot' => $penilaian ? $penilaian->nilai_terbobot : 0,
                    'bobot' => $kriteria->bobot,
                    'bobot_normalisasi' => $kriteria->getBobotNormalisasi()
                ];
            }

            $matriks[] = $row;
        }

        return $matriks;
    }

    /**
     * Check apakah semua penilaian sudah lengkap untuk perhitungan SMART
     */
    public function isValidForSMART()
    {
        $pesertas = Peserta::count();
        $kriterias = Kriteria::where('is_active', true)->count();
        $juris = Juri::where('is_active', true)->count();

        // Hitung penilaian yang seharusnya ada
        $expectedPenilaians = $pesertas * $kriterias * $juris;
        $actualPenilaians = Penilaian::count();

        return [
            'valid' => $actualPenilaians >= $expectedPenilaians,
            'pesertas' => $pesertas,
            'kriterias' => $kriterias,
            'juris' => $juris,
            'expected_penilaians' => $expectedPenilaians,
            'actual_penilaians' => $actualPenilaians,
            'completion_percentage' => $expectedPenilaians > 0 ?
                ($actualPenilaians / $expectedPenilaians) * 100 : 0
        ];
    }
}