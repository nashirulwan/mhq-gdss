<?php

namespace App\Http\Controllers\Juri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Peserta;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JuriController extends Controller
{
    /**
     * Display the juri dashboard.
     */
    public function dashboard()
    {
        $juri = Auth::user();

        // Get juri statistics
        $stats = [
            'total_peserta' => Peserta::count(),
            'total_penilaian' => Penilaian::where('juri_id', $juri->juri->id ?? $juri->id)->count(),
            'completed_penilaian' => Penilaian::where('juri_id', $juri->juri->id ?? $juri->id)
                ->whereNotNull('nilai')
                ->count(),
            'pending_penilaian' => Penilaian::where('juri_id', $juri->juri->id ?? $juri->id)
                ->whereNull('nilai')
                ->count(),
        ];

        // Get assigned participants
        $assignedPesertas = Peserta::with(['user.profile', 'penilaians' => function($query) use ($juri) {
            $query->where('juri_id', $juri->juri->id ?? $juri->id);
        }])
        ->orderBy('nama_lengkap')
        ->get();

        // Get recent penilaians
        $recentPenilaians = Penilaian::with(['peserta', 'kriteria'])
            ->where('juri_id', $juri->juri->id ?? $juri->id)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // Get completion percentage
        $completionPercentage = $stats['total_penilaian'] > 0
            ? round(($stats['completed_penilaian'] / $stats['total_penilaian']) * 100, 1)
            : 0;

        return view('juri.dashboard', compact(
            'stats',
            'assignedPesertas',
            'recentPenilaians',
            'completionPercentage'
        ));
    }

    /**
     * Display list of participants for evaluation.
     */
    public function pesertas()
    {
        $juri = Auth::user();
        $juriId = $juri->juri->id ?? $juri->id;

        $pesertas = Peserta::with(['user.profile', 'penilaians' => function($query) use ($juriId) {
            $query->where('juri_id', $juriId);
        }])
        ->orderBy('nama_lengkap')
        ->paginate(10);

        return view('juri.pesertas', compact('pesertas'));
    }

    /**
     * Show evaluation form for a participant.
     */
    public function evaluate(Peserta $peserta)
    {
        $juri = Auth::user();
        $juriId = $juri->juri->id ?? $juri->id;
        $kriterias = Kriteria::where('is_active', true)->get();

        // Get existing penilaians by this juri for this peserta
        $existingPenilaians = Penilaian::where('juri_id', $juriId)
            ->where('peserta_id', $peserta->id)
            ->get()
            ->keyBy('kriteria_id');

        return view('juri.evaluate', compact('peserta', 'kriterias', 'existingPenilaians'));
    }

    /**
     * Save evaluation scores.
     */
    public function saveEvaluation(Request $request, Peserta $peserta)
    {
        $juri = Auth::user();
        $juriId = $juri->juri->id ?? $juri->id;

        $validated = $request->validate([
            'penilaians' => 'required|array',
            'penilaians.*.kriteria_id' => 'required|exists:kriterias,id',
            'penilaians.*.nilai' => 'required|numeric|min:0|max:100',
            'penilaians.*.catatan' => 'nullable|string|max:1000',
        ]);

        foreach ($validated['penilaians'] as $penilaianData) {
            Penilaian::updateOrCreate(
                [
                    'juri_id' => $juriId,
                    'peserta_id' => $peserta->id,
                    'kriteria_id' => $penilaianData['kriteria_id'],
                ],
                [
                    'nilai' => $penilaianData['nilai'],
                    'catatan' => $penilaianData['catatan'] ?? null,
                ]
            );
        }

        return redirect()->route('juri.pesertas')
            ->with('success', "Penilaian untuk {$peserta->nama_lengkap} berhasil disimpan.");
    }

    /**
     * Display evaluation history.
     */
    public function history()
    {
        $juri = Auth::user();
        $juriId = $juri->juri->id ?? $juri->id;

        $penilaians = Penilaian::with(['peserta', 'kriteria'])
            ->where('juri_id', $juriId)
            ->whereNotNull('nilai')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('juri.history', compact('penilaians'));
    }

    /**
     * Display juri profile.
     */
    public function profile()
    {
        $juri = Auth::user();
        $profile = $juri->profile;
        $juriId = $juri->juri->id ?? $juri->id;

        // Get juri statistics
        $stats = [
            'total_evaluations' => Penilaian::where('juri_id', $juriId)->count(),
            'avg_scores' => Penilaian::where('juri_id', $juriId)
                ->whereNotNull('nilai')
                ->avg('nilai'),
            'last_evaluation' => Penilaian::where('juri_id', $juriId)
                ->orderBy('updated_at', 'desc')
                ->first(),
        ];

        return view('juri.profile', compact('juri', 'profile', 'stats'));
    }

    /**
     * Show statistics and analytics for juri.
     */
    public function statistics()
    {
        $juri = Auth::user();
        $juriId = $juri->juri->id ?? $juri->id;

        // Score distribution
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
            ->get();

        // Evaluation by criteria
        $criteriaStats = Penilaian::with('kriteria')
            ->selectRaw('kriteria_id, AVG(nilai) as avg_score, COUNT(*) as count')
            ->where('juri_id', $juriId)
            ->whereNotNull('nilai')
            ->groupBy('kriteria_id')
            ->get();

        return view('juri.statistics', compact('scoreDistribution', 'criteriaStats'));
    }
}
