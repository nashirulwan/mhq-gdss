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
        $user = Auth::user();

        // Get juri record associated with this user
        $juri = $user->juri;

        // If no juri record found, create one for demo
        if (!$juri) {
            $juri = \App\Models\Juri::create([
                'nama_lengkap' => $user->name,
                'instansi' => $user->institusi ?? 'Demo Institution',
                'kontak' => $user->phone ?? 'N/A',
                'keahlian' => 'Tajwid',
                'is_active' => true,
            ]);
        }

        // Get juri statistics
        $totalKriteria = Kriteria::where('is_active', true)->count();
        $totalPeserta = Peserta::count();
        $expectedPenilaians = $totalPeserta * $totalKriteria;

        $stats = [
            'total_peserta' => $totalPeserta,
            'total_penilaian' => $expectedPenilaians,
            'completed_penilaian' => Penilaian::where('juri_id', $juri->id)
                ->whereNotNull('nilai')
                ->count(),
            'pending_penilaian' => $expectedPenilaians - Penilaian::where('juri_id', $juri->id)
                ->whereNotNull('nilai')
                ->count(),
        ];

        // Get all participants (not just assigned - juri can evaluate all)
        $assignedPesertas = Peserta::with(['penilaians' => function($query) use ($juri) {
            $query->where('juri_id', $juri->id);
        }])
        ->orderBy('nama_lengkap')
        ->get();

        // Get recent penilaians
        $recentPenilaians = Penilaian::with(['peserta', 'kriteria'])
            ->where('juri_id', $juri->id)
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
    public function pesertas(Request $request)
    {
        $user = Auth::user();

        // Get or create juri record
        $juri = $user->juri;
        if (!$juri) {
            $juri = \App\Models\Juri::create([
                'nama_lengkap' => $user->name,
                'instansi' => $user->institusi ?? 'Demo Institution',
                'kontak' => $user->phone ?? 'N/A',
                'keahlian' => 'Tajwid',
                'is_active' => true,
            ]);
        }

        $query = Peserta::with(['penilaians' => function($query) use ($juri) {
            $query->where('juri_id', $juri->id);
        }]);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_lengkap', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nomor_peserta', 'like', '%' . $searchTerm . '%')
                  ->orWhere('instansi', 'like', '%' . $searchTerm . '%');
            });
        }

        $pesertas = $query->orderBy('nama_lengkap')
                         ->paginate(10);

        return view('juri.pesertas', compact('pesertas'));
    }

    /**
     * Get participant detail for AJAX.
     */
    public function pesertaDetail($pesertaId)
    {
        $user = Auth::user();
        $juri = $user->juri;

        if (!$juri) {
            return response()->json(['error' => 'Juri record not found'], 404);
        }

        $peserta = Peserta::find($pesertaId);
        if (!$peserta) {
            return response()->json(['error' => 'Peserta not found'], 404);
        }

        $peserta->load('penilaians');
        $totalKriteria = Kriteria::where('is_active', true)->count();
        $completedCount = $peserta->penilaians->whereNotNull('nilai')->count();
        $isCompleted = $completedCount >= $totalKriteria;
        $progress = $totalKriteria > 0 ? round(($completedCount / $totalKriteria) * 100) : 0;

        return response()->json([
            'nama_lengkap' => $peserta->nama_lengkap,
            'nomor_peserta' => $peserta->nomor_peserta,
            'instansi' => $peserta->instansi,
            'kategori' => $peserta->kategori,
            'usia' => $peserta->usia,
            'kontak' => $peserta->kontak,
            'keterangan' => $peserta->keterangan,
            'status' => $isCompleted ? 'Selesai' : ($completedCount > 0 ? 'Sedang Dinilai' : 'Belum Dinilai'),
            'progress' => $progress . '% (' . $completedCount . '/' . $totalKriteria . ')',
            'nilai' => $peserta->nilai_akhir_smart ? number_format($peserta->nilai_akhir_smart, 3) : null
        ]);
    }

    /**
     * Show evaluation form for a participant.
     */
    public function evaluate(Peserta $peserta)
    {
        $user = Auth::user();

        // Get or create juri record
        $juri = $user->juri;
        if (!$juri) {
            $juri = \App\Models\Juri::create([
                'nama_lengkap' => $user->name,
                'instansi' => $user->institusi ?? 'Demo Institution',
                'kontak' => $user->phone ?? 'N/A',
                'keahlian' => 'Tajwid',
                'is_active' => true,
            ]);
        }

        $kriterias = Kriteria::where('is_active', true)->get();

        // Get existing penilaians by this juri for this peserta
        $existingPenilaians = Penilaian::where('juri_id', $juri->id)
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
        $user = Auth::user();

        // Get or create juri record
        $juri = $user->juri;
        if (!$juri) {
            $juri = \App\Models\Juri::create([
                'nama_lengkap' => $user->name,
                'instansi' => $user->institusi ?? 'Demo Institution',
                'kontak' => $user->phone ?? 'N/A',
                'keahlian' => 'Tajwid',
                'is_active' => true,
            ]);
        }

        $juriId = $juri->id;

        $validated = $request->validate([
            'penilaians' => 'required|array',
            'penilaians.*.kriteria_id' => 'required|exists:kriterias,id',
            'penilaians.*.nilai' => 'required|numeric|min:0|max:100',
            'penilaians.*.catatan' => 'nullable|string|max:1000',
        ]);

        $savedCount = 0;
        foreach ($validated['penilaians'] as $kriteriaId => $penilaianData) {
            $penilaian = Penilaian::updateOrCreate(
                [
                    'juri_id' => $juriId,
                    'peserta_id' => $peserta->id,
                    'kriteria_id' => $kriteriaId,
                ],
                [
                    'nilai' => $penilaianData['nilai'],
                    'catatan' => $penilaianData['catatan'] ?? null,
                ]
            );

            if ($penilaian) {
                $savedCount++;
            }
        }

        // Check if all kriteria for this peserta are now complete
        $totalKriteria = Kriteria::where('is_active', true)->count();
        $completedKriteria = Penilaian::where('peserta_id', $peserta->id)
            ->where('juri_id', $juriId)
            ->whereNotNull('nilai')
            ->count();

        $message = "Berhasil menyimpan {$savedCount} penilaian untuk {$peserta->nama_lengkap}.";

        if ($completedKriteria === $totalKriteria) {
            $message .= " Semua kriteria telah dinilai!";
        }

        return redirect()->route('juri.dashboard')
            ->with('success', $message);
    }

    /**
     * Display evaluation history.
     */
    public function history(Request $request)
    {
        $user = Auth::user();

        // Get or create juri record
        $juri = $user->juri;
        if (!$juri) {
            $juri = \App\Models\Juri::create([
                'nama_lengkap' => $user->name,
                'instansi' => $user->institusi ?? 'Demo Institution',
                'kontak' => $user->phone ?? 'N/A',
                'keahlian' => 'Tajwid',
                'is_active' => true,
            ]);
        }

        $query = Penilaian::with(['peserta', 'kriteria'])
            ->where('juri_id', $juri->id)
            ->whereNotNull('nilai');

        // Apply filters
        if ($request->has('peserta_id') && !empty($request->peserta_id)) {
            $query->where('peserta_id', $request->peserta_id);
        }

        if ($request->has('kriteria_id') && !empty($request->kriteria_id)) {
            $query->where('kriteria_id', $request->kriteria_id);
        }

        if ($request->has('min_nilai') && !empty($request->min_nilai)) {
            $query->where('nilai', '>=', $request->min_nilai);
        }

        if ($request->has('max_nilai') && !empty($request->max_nilai)) {
            $query->where('nilai', '<=', $request->max_nilai);
        }

        $penilaians = $query->orderBy('updated_at', 'desc')
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
        $user = Auth::user();

        // Get or create juri record
        $juri = $user->juri;
        if (!$juri) {
            $juri = \App\Models\Juri::create([
                'nama_lengkap' => $user->name,
                'instansi' => $user->institusi ?? 'Demo Institution',
                'kontak' => $user->phone ?? 'N/A',
                'keahlian' => 'Tajwid',
                'is_active' => true,
            ]);
        }

        $juriId = $juri->id;

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

        // Add score range
        if ($stats['highest_score']) {
            $lowestScore = Penilaian::where('juri_id', $juriId)
                ->whereNotNull('nilai')
                ->min('nilai');
            $stats['score_range'] = $lowestScore . '-' . $stats['highest_score'];
        } else {
            $stats['score_range'] = '0-0';
        }

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
            ->orderByRaw('FIELD(score_range, "Kurang", "Cukup", "Baik", "Sangat Baik")')
            ->get();

        // Evaluation by criteria
        $criteriaStats = Penilaian::with('kriteria')
            ->selectRaw('kriteria_id, AVG(nilai) as avg_score, COUNT(*) as count')
            ->where('juri_id', $juriId)
            ->whereNotNull('nilai')
            ->groupBy('kriteria_id')
            ->get();

        return view('juri.statistics', compact('stats', 'scoreDistribution', 'criteriaStats'));
    }
}
