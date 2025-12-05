<?php

namespace App\Services;

use App\Models\Peserta;
use App\Models\Penilaian;
use App\Models\Kriteria;
use App\Models\Juri;
use App\Services\SMARTService;
use App\Services\BordaService;

class ExportService
{
    protected $smartService;
    protected $bordaService;

    public function __construct()
    {
        $this->smartService = new SMARTService();
        $this->bordaService = new BordaService();
    }

    /**
     * Export hasil SMART ke CSV
     */
    public function exportSMARTToCSV()
    {
        $filename = "hasil_smart_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Add BOM for proper UTF-8 encoding
            fwrite($file, "\xEF\xBB\xBF");

            // Header CSV
            fputcsv($file, [
                'No',
                'Nama Peserta',
                'Nomor Peserta',
                'Instansi',
                'Nilai Akhir SMART',
                'Ranking',
                'Detail Penilaian per Kriteria'
            ]);

            // Get data
            $pesertas = Peserta::whereNotNull('nilai_akhir_smart')
                ->orderBy('peringkat_smart', 'asc')
                ->get();

            $kriterias = Kriteria::where('is_active', true)
                ->orderBy('nama_kriteria')
                ->get();

            foreach ($pesertas as $index => $peserta) {
                $detailPenilaian = [];
                foreach ($kriterias as $kriteria) {
                    $avgNilai = Penilaian::where('peserta_id', $peserta->id)
                        ->where('kriteria_id', $kriteria->id)
                        ->whereNotNull('nilai')
                        ->avg('nilai');

                    $detailPenilaian[] = $kriteria->nama_kriteria . ': ' .
                        number_format($avgNilai, 2);
                }

                fputcsv($file, [
                    $index + 1,
                    $peserta->nama_lengkap,
                    $peserta->nomor_peserta,
                    $peserta->instansi,
                    number_format($peserta->nilai_akhir_smart, 4),
                    $peserta->peringkat_smart,
                    implode('; ', $detailPenilaian)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export hasil Borda ke CSV
     */
    public function exportBordaToCSV()
    {
        $filename = "hasil_borda_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Add BOM for proper UTF-8 encoding
            fwrite($file, "\xEF\xBB\xBF");

            // Header CSV
            fputcsv($file, [
                'No',
                'Nama Peserta',
                'Nomor Peserta',
                'Instansi',
                'Skor Borda',
                'Ranking Borda'
            ]);

            // Get data
            $pesertas = Peserta::whereNotNull('skor_borda')
                ->orderBy('skor_borda', 'desc')
                ->get();

            foreach ($pesertas as $index => $peserta) {
                fputcsv($file, [
                    $index + 1,
                    $peserta->nama_lengkap,
                    $peserta->nomor_peserta,
                    $peserta->instansi,
                    number_format($peserta->skor_borda, 2),
                    $peserta->peringkat_borda
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export hasil gabungan ke CSV
     */
    public function exportCombinedToCSV()
    {
        $filename = "hasil_gabungan_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Add BOM for proper UTF-8 encoding
            fwrite($file, "\xEF\xBB\xBF");

            // Header CSV
            fputcsv($file, [
                'No',
                'Nama Peserta',
                'Nomor Peserta',
                'Instansi',
                'Nilai SMART Normalisasi',
                'Skor Borda Normalisasi',
                'Skor Akhir',
                'Ranking Akhir'
            ]);

            // Get data
            $results = $this->bordaService->rankingGabungan();

            foreach ($results as $index => $result) {
                fputcsv($file, [
                    $index + 1,
                    $result['peserta']->nama_lengkap,
                    $result['peserta']->nomor_peserta,
                    $result['peserta']->instansi,
                    $result['smart_normalisasi'],
                    $result['borda_normalisasi'],
                    $result['skor_akhir'],
                    $result['peringkat']
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export matrix SMART ke CSV
     */
    public function exportMatrixSMARTToCSV()
    {
        $filename = "matriks_smart_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Add BOM for proper UTF-8 encoding
            fwrite($file, "\xEF\xBB\xBF");

            $matriks = $this->smartService->getMatriksKeputusan();
            $kriterias = Kriteria::where('is_active', true)->get();

            if ($matriks->isEmpty()) {
                fputcsv($file, ['Tidak ada data untuk diekspor']);
                fclose($file);
                return;
            }

            // Build headers
            $headers = ['No', 'Nama Peserta', 'Nomor Peserta', 'Instansi'];
            foreach ($kriterias as $kriteria) {
                $headers[] = $kriteria->nama_kriteria . ' (Nilai Asli)';
                $headers[] = $kriteria->nama_kriteria . ' (Normalisasi)';
                $headers[] = $kriteria->nama_kriteria . ' (Terbobot)';
            }
            $headers[] = 'Total SMART';
            $headers[] = 'Ranking';

            fputcsv($file, $headers);

            // Data rows
            foreach ($matriks as $index => $peserta) {
                $row = [
                    $index + 1,
                    $peserta['nama_lengkap'],
                    $peserta['nomor_peserta'],
                    $peserta['instansi']
                ];

                foreach ($kriterias as $kriteria) {
                    $row[] = number_format($peserta["penilaian_{$kriteria->id}"] ?? 0, 2);
                    $row[] = number_format($peserta["normalisasi_{$kriteria->id}"] ?? 0, 4);
                    $terbobot = ($peserta["normalisasi_{$kriteria->id}"] ?? 0) * ($kriteria->bobot / 100);
                    $row[] = number_format($terbobot, 4);
                }

                $row[] = number_format($peserta['nilai_akhir_smart'], 4);
                $row[] = $peserta['peringkat_smart'];

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export voting detail Borda ke CSV
     */
    public function exportVotingBordaToCSV()
    {
        $filename = "detail_voting_borda_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Add BOM for proper UTF-8 encoding
            fwrite($file, "\xEF\xBB\xBF");

            $detailVoting = $this->bordaService->getDetailVoting();
            $juris = Juri::where('is_active', true)->get();
            $kriterias = Kriteria::where('is_active', true)->get();

            if (empty($detailVoting)) {
                fputcsv($file, ['Tidak ada data untuk diekspor']);
                fclose($file);
                return;
            }

            // Header
            fputcsv($file, [
                'Nama Peserta',
                'Skor Borda Total',
                'Ranking Borda',
                'Detail Voting per Juri dan Kriteria'
            ]);

            foreach ($detailVoting as $pesertaId => $pesertaData) {
                $detailVotes = [];

                foreach ($juris as $juri) {
                    foreach ($kriterias as $kriteria) {
                        $rank = $pesertaData["rank_{$kriteria->id}"] ?? '-';
                        $poin = $pesertaData["poin_borda_{$kriteria->id}"] ?? 0;
                        $detailVotes[] = "Juri {$juri->nama_lengkap} - {$kriteria->nama_kriteria}: Rank #{$rank}, Poin: {$poin}";
                    }
                }

                fputcsv($file, [
                    $pesertaData['nama_peserta'],
                    $pesertaData['skor_borda'],
                    $pesertaData['peringkat_borda'],
                    implode('; ', $detailVotes)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate PDF placeholder
     */
    public function generatePDFPlaceholder($type)
    {
        return response()->json([
            'message' => 'PDF export akan segera tersedia!',
            'type' => $type,
            'status' => 'placeholder',
            'note' => 'Fitur export PDF memerlukan library tambahan seperti dompdf atau tcpdf'
        ]);
    }

    /**
     * Export juri statistics ke CSV
     */
    public function exportJuriStatisticsToCSV($juriId)
    {
        $filename = "statistik_juri_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($juriId) {
            $file = fopen('php://output', 'w');

            // Add BOM for proper UTF-8 encoding
            fwrite($file, "\xEF\xBB\xBF");

            // Basic statistics
            $stats = [
                'total_evaluations' => Penilaian::where('juri_id', $juriId)
                    ->whereNotNull('nilai')
                    ->count(),
                'total_peserta' => Penilaian::where('juri_id', $juriId)
                    ->whereNotNull('nilai')
                    ->distinct('peserta_id')
                    ->count(),
                'avg_scores' => Penilaian::where('juri_id', $juriId)
                    ->whereNotNull('nilai')
                    ->avg('nilai'),
                'highest_score' => Penilaian::where('juri_id', $juriId)
                    ->whereNotNull('nilai')
                    ->max('nilai'),
                'perfect_scores' => Penilaian::where('juri_id', $juriId)
                    ->where('nilai', 100)
                    ->count(),
            ];

            // Score range
            if ($stats['highest_score']) {
                $lowestScore = Penilaian::where('juri_id', $juriId)
                    ->whereNotNull('nilai')
                    ->min('nilai');
                $stats['score_range'] = $lowestScore . '-' . $stats['highest_score'];
            } else {
                $stats['score_range'] = '0-0';
            }

            // Header CSV
            fputcsv($file, ['Statistik Juri - ' . date('Y-m-d H:i:s')]);
            fputcsv($file, ['']);

            // Basic Stats
            fputcsv($file, ['Statistik Dasar']);
            fputcsv($file, ['Total Evaluasi', $stats['total_evaluations']]);
            fputcsv($file, ['Peserta Dinilai', $stats['total_peserta']]);
            fputcsv($file, ['Rata-rata Nilai', number_format($stats['avg_scores'], 2)]);
            fputcsv($file, ['Nilai Tertinggi', $stats['highest_score'] ?? 0]);
            fputcsv($file, ['Range Nilai', $stats['score_range']]);
            fputcsv($file, ['Nilai Sempurna (100)', $stats['perfect_scores']]);
            fputcsv($file, ['']);

            // Score Distribution
            fputcsv($file, ['Distribusi Nilai']);
            $scoreDistribution = Penilaian::selectRaw('
                    CASE
                        WHEN nilai >= 90 THEN "Sangat Baik"
                        WHEN nilai >= 75 THEN "Baik"
                        WHEN nilai >= 60 THEN "Cukup"
                        ELSE "Kurang"
                    END as score_range,
                    COUNT(*) as count
                ')
                ->where('juri_id', $juriId)
                ->whereNotNull('nilai')
                ->groupBy('score_range')
                ->orderByRaw('FIELD(score_range, "Kurang", "Cukup", "Baik", "Sangat Baik")')
                ->get();

            foreach ($scoreDistribution as $range) {
                $percentage = round(($range->count / $stats['total_evaluations']) * 100, 1);
                fputcsv($file, [$range->score_range, $range->count, $percentage . '%']);
            }
            fputcsv($file, ['']);

            // Performance by Criteria
            fputcsv($file, ['Performa per Kriteria']);
            fputcsv($file, ['Kriteria', 'Jumlah', 'Rata-rata', 'Tertinggi']);

            $criteriaStats = Penilaian::with('kriteria')
                ->selectRaw('kriteria_id, AVG(nilai) as avg_score, COUNT(*) as count')
                ->where('juri_id', $juriId)
                ->whereNotNull('nilai')
                ->groupBy('kriteria_id')
                ->get();

            foreach ($criteriaStats as $stat) {
                $maxScore = Penilaian::where('juri_id', $juriId)
                    ->where('kriteria_id', $stat->kriteria_id)
                    ->max('nilai');

                fputcsv($file, [
                    $stat->kriteria->nama_kriteria,
                    $stat->count,
                    number_format($stat->avg_score, 2),
                    $maxScore ?? 0
                ]);
            }
            fputcsv($file, ['']);

            // Top Performers
            fputcsv($file, ['Top Performers']);
            fputcsv($file, ['Nama Peserta', 'Rata-rata Nilai', 'Jumlah Kriteria']);

            $topPerformers = \App\Models\Peserta::selectRaw('peserta_id, pesertas.nama_lengkap, AVG(nilai) as avg_nilai, COUNT(*) as count')
                ->join('penilaians', 'penilaians.peserta_id', '=', 'pesertas.id')
                ->where('penilaians.juri_id', $juriId)
                ->whereNotNull('penilaians.nilai')
                ->groupBy('peserta_id', 'pesertas.nama_lengkap')
                ->orderBy('avg_nilai', 'desc')
                ->limit(10)
                ->get();

            foreach ($topPerformers as $performer) {
                fputcsv($file, [
                    $performer->nama_lengkap,
                    number_format($performer->avg_nilai, 2),
                    $performer->count
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate Excel placeholder
     */
    public function generateExcelPlaceholder($type)
    {
        return response()->json([
            'message' => 'Excel export akan segera tersedia!',
            'type' => $type,
            'status' => 'placeholder',
            'note' => 'Fitur export Excel memerlukan library tambahan seperti Laravel Excel (maatwebsite/excel)'
        ]);
    }
}