<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Peserta;
use App\Models\Penilaian;
use App\Models\Kriteria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PesertaController extends Controller
{
    /**
     * Display the peserta dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Find the peserta record associated with this user
        $peserta = Peserta::where('user_id', $user->id)->first();

        if (!$peserta) {
            return redirect()->route('peserta.profile')
                ->with('info', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        // Get peserta's scores (if results are published)
        $penilaians = Penilaian::with(['juri.user', 'kriteria'])
            ->where('peserta_id', $peserta->id)
            ->whereNotNull('nilai')
            ->get();

        // Calculate statistics
        $stats = [
            'total_penilaians' => $penilaians->count(),
            'avg_score' => $penilaians->avg('nilai'),
            'max_score' => $penilaians->max('nilai'),
            'min_score' => $penilaians->min('nilai'),
        ];

        // Get criteria breakdown
        $criteriaScores = Penilaian::with('kriteria')
            ->where('peserta_id', $peserta->id)
            ->whereNotNull('nilai')
            ->selectRaw('kriteria_id, AVG(nilai) as avg_score, COUNT(*) as count')
            ->groupBy('kriteria_id')
            ->get();

        // Check if results are published (this could be a setting in the future)
        $resultsPublished = true; // This could be configurable by admin

        return view('peserta.dashboard', compact(
            'peserta',
            'penilaians',
            'stats',
            'criteriaScores',
            'resultsPublished'
        ));
    }

    /**
     * Display detailed results.
     */
    public function results()
    {
        $user = Auth::user();
        $peserta = Peserta::where('user_id', $user->id)->first();

        if (!$peserta) {
            return redirect()->route('peserta.profile')
                ->with('error', 'Data peserta tidak ditemukan.');
        }

        // Get all penilaians with full details
        $penilaians = Penilaian::with(['juri.user', 'kriteria'])
            ->where('peserta_id', $peserta->id)
            ->whereNotNull('nilai')
            ->orderBy('kriteria_id')
            ->orderBy('juri_id')
            ->get();

        // Group by criteria for better display
        $groupedPenilaians = $penilaians->groupBy('kriteria_id');

        // Calculate overall ranking if results are published
        $ranking = $this->calculateRanking($peserta);

        return view('peserta.results', compact(
            'peserta',
            'penilaians',
            'groupedPenilaians',
            'ranking'
        ));
    }

    /**
     * Display peserta profile.
     */
    public function profile()
    {
        $user = Auth::user();
        $peserta = Peserta::where('user_id', $user->id)->first();

        return view('peserta.profile', compact('user', 'peserta'));
    }

    /**
     * Update peserta profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Display competition information.
     */
    public function competition()
    {
        return view('peserta.competition');
    }

    /**
     * Display leaderboard/ranking.
     */
    public function ranking()
    {
        // Get top 10 performers (if results are published)
        $topPerformers = Peserta::with('user')
            ->select('pesertas.*', DB::raw('AVG(penilaians.nilai) as avg_score'))
            ->join('penilaians', 'pesertas.id', '=', 'penilaians.peserta_id')
            ->whereNotNull('penilaians.nilai')
            ->groupBy('pesertas.id')
            ->orderByDesc('avg_score')
            ->take(10)
            ->get();

        // Get current user's position
        $user = Auth::user();
        $userPeserta = Peserta::where('user_id', $user->id)->first();

        $userRanking = null;
        if ($userPeserta) {
            $userRanking = Peserta::select('pesertas.*', DB::raw('AVG(penilaians.nilai) as avg_score'))
                ->join('penilaians', 'pesertas.id', '=', 'penilaians.peserta_id')
                ->whereNotNull('penilaians.nilai')
                ->groupBy('pesertas.id')
                ->orderByDesc('avg_score')
                ->get()
                ->search(function($item) use ($userPeserta) {
                    return $item->id === $userPeserta->id;
                });

            if ($userRanking !== false) {
                $userRanking = $userRanking + 1;
            }
        }

        return view('peserta.ranking', compact('topPerformers', 'userRanking', 'userPeserta'));
    }

    /**
     * Calculate peserta ranking.
     */
    private function calculateRanking(Peserta $peserta)
    {
        $ranking = Peserta::select('pesertas.*', DB::raw('AVG(penilaians.nilai) as avg_score'))
            ->join('penilaians', 'pesertas.id', '=', 'penilaians.peserta_id')
            ->whereNotNull('penilaians.nilai')
            ->groupBy('pesertas.id')
            ->orderByDesc('avg_score')
            ->get()
            ->search(function($item) use ($peserta) {
                return $item->id === $peserta->id;
            });

        return $ranking !== false ? $ranking + 1 : null;
    }
}
