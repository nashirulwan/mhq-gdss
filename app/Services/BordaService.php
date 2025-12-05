<?php

namespace App\Services;

use App\Models\Peserta;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Juri;

class BordaService
{
    /**
     * Implementasi metode Borda untuk agregasi keputusan kelompok
     * Borda Count Method
     */
    public function hitungSkorBorda($peserta_id = null)
    {
        $pesertas = $peserta_id ? Peserta::where('id', $peserta_id)->get() : Peserta::all();
        $juris = Juri::where('is_active', true)->get();
        $kriterias = Kriteria::where('is_active', true)->get();

        // Reset skor borda semua peserta
        Peserta::query()->update(['skor_borda' => 0]);

        foreach ($juris as $juri) {
            foreach ($kriterias as $kriteria) {
                // 1. Ranking peserta berdasarkan nilai untuk setiap juri dan kriteria
                $rankingPerJuri = $this->rankingPesertaPerJuri($juri->id, $kriteria->id);

                // 2. Hitung poin Borda untuk setiap peserta
                $jumlahPeserta = count($rankingPerJuri);
                foreach ($rankingPerJuri as $index => $pesertaData) {
                    // Poin Borda: (n-1-rank) dimana n = jumlah peserta, rank = posisi (dimulai dari 0)
                    $poinBorda = $jumlahPeserta - 1 - $index;

                    // Update skor borda peserta
                    $peserta = Peserta::find($pesertaData['peserta_id']);
                    if ($peserta) {
                        $peserta->increment('skor_borda', $poinBorda * $kriteria->bobot_borda);
                    }
                }
            }
        }

        return $pesertas;
    }

    /**
     * Ranking peserta berdasarkan nilai dari satu juri untuk satu kriteria
     */
    private function rankingPesertaPerJuri($juri_id, $kriteria_id)
    {
        $penilaians = Penilaian::with('peserta')
            ->where('juri_id', $juri_id)
            ->where('kriteria_id', $kriteria_id)
            ->orderBy('nilai', 'desc')
            ->get();

        $ranking = [];
        foreach ($penilaians as $penilaian) {
            $ranking[] = [
                'peserta_id' => $penilaian->peserta_id,
                'nama_peserta' => $penilaian->peserta->nama_lengkap,
                'nilai' => $penilaian->nilai
            ];
        }

        return $ranking;
    }

    /**
     * Ranking akhir berdasarkan skor Borda
     */
    public function rankingBorda()
    {
        $pesertas = Peserta::whereNotNull('skor_borda')
            ->orderBy('skor_borda', 'desc')
            ->get();

        // Update peringkat di database
        foreach ($pesertas as $index => $peserta) {
            $peserta->peringkat_borda = $index + 1;
            $peserta->save();
        }

        return $pesertas;
    }

    /**
     * Gabungkan hasil SMART dan Borda untuk ranking akhir
     */
    public function rankingGabungan()
    {
        $pesertas = Peserta::with(['penilaians'])
            ->whereNotNull('nilai_akhir_smart')
            ->whereNotNull('skor_borda')
            ->get();

        // Normalisasi nilai SMART dan Borda ke skala 0-100
        $maxSMART = $pesertas->max('nilai_akhir_smart');
        $minSMART = $pesertas->min('nilai_akhir_smart');
        $maxBorda = $pesertas->max('skor_borda');
        $minBorda = $pesertas->min('skor_borda');

        $hasilGabungan = [];
        foreach ($pesertas as $peserta) {
            // Normalisasi SMART (0-100)
            $smartNormalisasi = ($maxSMART - $minSMART) > 0 ?
                (($peserta->nilai_akhir_smart - $minSMART) / ($maxSMART - $minSMART)) * 100 : 0;

            // Normalisasi Borda (0-100)
            $bordaNormalisasi = ($maxBorda - $minBorda) > 0 ?
                (($peserta->skor_borda - $minBorda) / ($maxBorda - $minBorda)) * 100 : 0;

            // Weighted combination (50% SMART, 50% Borda)
            $skorAkhir = ($smartNormalisasi * 0.5) + ($bordaNormalisasi * 0.5);

            $hasilGabungan[] = [
                'peserta' => $peserta,
                'smart_normalisasi' => round($smartNormalisasi, 2),
                'borda_normalisasi' => round($bordaNormalisasi, 2),
                'skor_akhir' => round($skorAkhir, 2)
            ];
        }

        // Sort by skor akhir descending
        usort($hasilGabungan, function ($a, $b) {
            return $b['skor_akhir'] <=> $a['skor_akhir'];
        });

        // Add ranking
        $peringkat = 1;
        foreach ($hasilGabungan as &$hasil) {
            $hasil['peringkat'] = $peringkat;
            $peringkat++;
        }

        return $hasilGabungan;
    }

    /**
     * Get Borda matrix yang sudah diproses
     */
    public function getMatriksBorda()
    {
        $pesertas = Peserta::whereNotNull('skor_borda')
            ->orderBy('peringkat_borda')
            ->get();

        $matriks = [];

        foreach ($pesertas as $peserta) {
            $matriks[] = [
                'id' => $peserta->id,
                'nama_lengkap' => $peserta->nama_lengkap,
                'instansi' => $peserta->instansi,
                'skor_borda' => $peserta->skor_borda,
                'peringkat_borda' => $peserta->peringkat_borda,
                'peringkat_smart' => $peserta->peringkat_smart,
                'nilai_akhir_smart' => $peserta->nilai_akhir_smart
            ];
        }

        return collect($matriks);
    }

    /**
     * Get detail voting per juri dan peserta untuk transparansi
     */
    public function getDetailVoting()
    {
        $juris = Juri::where('is_active', true)->get();
        $pesertas = Peserta::whereNotNull('skor_borda')
            ->orderBy('peringkat_borda')
            ->get();
        $kriterias = Kriteria::where('is_active', true)->get();

        $detailVoting = [];

        foreach ($pesertas as $peserta) {
            $detailVoting[$peserta->id] = [
                'nama_peserta' => $peserta->nama_lengkap,
                'skor_borda' => $peserta->skor_borda,
                'peringkat_borda' => $peserta->peringkat_borda,
            ];

            foreach ($juris as $juri) {
                $detailVoting[$peserta->id]['nama_juri'] = $juri->nama_lengkap;

                foreach ($kriterias as $kriteria) {
                    $ranking = $this->rankingPesertaPerJuri($juri->id, $kriteria->id);
                    $rank = 0;
                    $poinBorda = 0;

                    foreach ($ranking as $rankedPeserta) {
                        $rank++;
                        if ($rankedPeserta['peserta_id'] == $peserta->id) {
                            $jumlahPeserta = count($ranking);
                            $poinBorda = ($jumlahPeserta - 1) - $rank + 1;
                            break;
                        }
                    }

                    $detailVoting[$peserta->id]["rank_{$kriteria->id}"] = $rank;
                    $detailVoting[$peserta->id]["poin_borda_{$kriteria->id}"] = $poinBorda;
                    $detailVoting[$peserta->id]['juri_id'] = $juri->id;
                }
            }
        }

        return $detailVoting;
    }

    /**
     * Validasi data untuk perhitungan Borda
     */
    public function isValidForBorda()
    {
        $pesertas = Peserta::count();
        $kriterias = Kriteria::where('is_active', true)->count();
        $juris = Juri::where('is_active', true)->count();

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