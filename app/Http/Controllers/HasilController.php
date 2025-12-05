<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SMARTService;
use App\Services\BordaService;
use App\Models\Peserta;
use App\Models\Kriteria;

class HasilController extends Controller
{
    protected $smartService;
    protected $bordaService;

    public function __construct()
    {
        $this->smartService = new SMARTService();
        $this->bordaService = new BordaService();
    }

    public function index()
    {
        // Get statistics
        $smartStatus = $this->smartService->isValidForSMART();
        $bordaStatus = $this->bordaService->isValidForBorda();

        // Get results if available
        $smartResults = Peserta::whereNotNull('nilai_akhir_smart')
            ->orderBy('nilai_akhir_smart', 'desc')
            ->get();

        $bordaResults = Peserta::whereNotNull('skor_borda')
            ->orderBy('skor_borda', 'desc')
            ->get();

        $combinedResults = null;
        if ($smartResults->isNotEmpty() && $bordaResults->isNotEmpty()) {
            $combinedResults = $this->bordaService->rankingGabungan();
        }

        return view('hasil.index_basic', compact(
            'smartStatus',
            'bordaStatus',
            'smartResults',
            'bordaResults',
            'combinedResults'
        ));
    }

    public function hitungSMART(Request $request)
    {
        if (!$this->smartService->isValidForSMART()['valid']) {
            return redirect()
                ->route('hasil.index')
                ->with('error', 'Data penilaian belum lengkap untuk perhitungan SMART!');
        }

        try {
            $pesertas = $this->smartService->hitungNilaiSMART();
            $this->smartService->rankingPeserta();

            return redirect()
                ->route('hasil.index')
                ->with('success', 'Perhitungan SMART berhasil dilakukan untuk ' . $pesertas->count() . ' peserta!');
        } catch (\Exception $e) {
            return redirect()
                ->route('hasil.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function hitungBorda(Request $request)
    {
        if (!$this->bordaService->isValidForBorda()['valid']) {
            return redirect()
                ->route('hasil.index')
                ->with('error', 'Data penilaian belum lengkap untuk perhitungan Borda!');
        }

        try {
            $pesertas = $this->bordaService->hitungSkorBorda();

            return redirect()
                ->route('hasil.index')
                ->with('success', 'Perhitungan Borda berhasil dilakukan untuk ' . $pesertas->count() . ' peserta!');
        } catch (\Exception $e) {
            return redirect()
                ->route('hasil.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function hitungGabungan(Request $request)
    {
        $smartValid = $this->smartService->isValidForSMART()['valid'];
        $bordaValid = $this->bordaService->isValidForBorda()['valid'];

        if (!$smartValid || !$bordaValid) {
            return redirect()
                ->route('hasil.index')
                ->with('error', 'Data penilaian belum lengkap untuk perhitungan gabungan!');
        }

        try {
            // Run both calculations
            $this->smartService->hitungNilaiSMART();
            $this->smartService->rankingPeserta();
            $this->bordaService->hitungSkorBorda();

            return redirect()
                ->route('hasil.index')
                ->with('success', 'Perhitungan gabungan SMART & Borda berhasil dilakukan!');
        } catch (\Exception $e) {
            return redirect()
                ->route('hasil.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function matriksSMART()
    {
        if (!$this->smartService->isValidForSMART()['valid']) {
            return redirect()
                ->route('hasil.index')
                ->with('error', 'Data penilaian belum lengkap!');
        }

        $matriks = $this->smartService->getMatriksKeputusan();
        $kriterias = Kriteria::where('is_active', true)->get();

        return view('hasil.matriks_smart', compact('matriks', 'kriterias'));
    }

    public function matriksBorda()
    {
        if (!$this->bordaService->isValidForBorda()['valid']) {
            return redirect()
                ->route('hasil.index')
                ->with('error', 'Data penilaian belum lengkap!');
        }

        $matriks = $this->bordaService->getMatriksBorda();
        $detailVoting = $this->bordaService->getDetailVoting();
        $kriterias = Kriteria::where('is_active', true)->get();

        return view('hasil.matriks_borda', compact('matriks', 'detailVoting', 'kriterias'));
    }
}
