<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\Juri;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Services\SMARTService;
use App\Services\BordaService;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'total_peserta' => Peserta::count(),
            'total_juri' => Juri::where('is_active', true)->count(),
            'total_kriteria' => Kriteria::where('is_active', true)->count(),
            'total_penilaian' => Penilaian::count(),
            'peserta_sudah_dinilai' => Peserta::whereHas('penilaians')->count(),
        ];

        // Get recent activities
        $recentPenilaians = Penilaian::with(['peserta', 'juri', 'kriteria'])
            ->latest()
            ->take(5)
            ->get();

        // Check completion status
        $smartService = new SMARTService();
        $bordaService = new BordaService();
        $smartStatus = $smartService->isValidForSMART();
        $bordaStatus = $bordaService->isValidForBorda();

        // Get top ranking jika sudah ada perhitungan
        $topRanking = Peserta::whereNotNull('nilai_akhir_smart')
            ->orderBy('nilai_akhir_smart', 'desc')
            ->take(3)
            ->get();

        return view('dashboard.index', compact(
            'stats',
            'recentPenilaians',
            'smartStatus',
            'bordaStatus',
            'topRanking'
        ));
    }
}
