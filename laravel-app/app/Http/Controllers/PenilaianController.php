<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\Juri;
use App\Models\Kriteria;
use App\Models\Penilaian;

class PenilaianController extends Controller
{
    public function index()
    {
        $pesertas = Peserta::orderBy('nama_lengkap')->get();
        $juris = Juri::where('is_active', true)->orderBy('nama_lengkap')->get();
        $kriterias = Kriteria::where('is_active', true)->orderBy('nama_kriteria')->get();

        // Get existing penilaians for display
        $penilaians = Penilaian::with(['peserta', 'juri', 'kriteria'])
            ->latest()
            ->paginate(20);

        return view('penilaian.index', compact(
            'pesertas',
            'juris',
            'kriterias',
            'penilaians'
        ));
    }

    public function create()
    {
        $pesertas = Peserta::orderBy('nama_lengkap')->get();
        $juris = Juri::where('is_active', true)->orderBy('nama_lengkap')->get();
        $kriterias = Kriteria::where('is_active', true)->orderBy('nama_kriteria')->get();

        if ($pesertas->isEmpty() || $juris->isEmpty() || $kriterias->isEmpty()) {
            return redirect()
                ->route('penilaian.index')
                ->with('error', 'Mohon lengkapi data peserta, juri, dan kriteria terlebih dahulu!');
        }

        return view('penilaian.create', compact(
            'pesertas',
            'juris',
            'kriterias'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peserta_id' => 'required|exists:pesertas,id',
            'juri_id' => 'required|exists:juris,id',
            'kriteria_id' => 'required|exists:kriterias,id',
            'nilai' => 'required|integer|min:0|max:100',
            'catatan' => 'nullable|string|max:1000',
        ]);

        // Check if penilaian already exists
        $existingPenilaian = Penilaian::where('peserta_id', $request->peserta_id)
            ->where('juri_id', $request->juri_id)
            ->where('kriteria_id', $request->kriteria_id)
            ->first();

        if ($existingPenilaian) {
            return redirect()
                ->route('penilaian.index')
                ->with('error', 'Penilaian untuk peserta, juri, dan kriteria ini sudah ada!');
        }

        Penilaian::create($request->all());

        return redirect()
            ->route('penilaian.index')
            ->with('success', 'Penilaian berhasil ditambahkan!');
    }

    public function edit($peserta_id, $juri_id)
    {
        $peserta = Peserta::findOrFail($peserta_id);
        $juri = Juri::findOrFail($juri_id);
        $kriterias = Kriteria::where('is_active', true)->orderBy('nama_kriteria')->get();

        // Get existing penilaians for this peserta and juri
        $existingPenilaians = Penilaian::where('peserta_id', $peserta_id)
            ->where('juri_id', $juri_id)
            ->with('kriteria')
            ->get()
            ->keyBy('kriteria_id');

        return view('penilaian.edit', compact(
            'peserta',
            'juri',
            'kriterias',
            'existingPenilaians'
        ));
    }

    public function update(Request $request, $peserta_id, $juri_id)
    {
        $peserta = Peserta::findOrFail($peserta_id);
        $juri = Juri::findOrFail($juri_id);
        $kriterias = Kriteria::where('is_active', true)->get();

        // Validate input for each criteria
        $request->validate([
            'nilai' => 'required|array',
            'nilai.*' => 'required|integer|min:0|max:100',
            'catatan' => 'array',
            'catatan.*' => 'nullable|string|max:1000',
        ]);

        foreach ($kriterias as $kriteria) {
            $nilai = $request->input("nilai.{$kriteria->id}");
            $catatan = $request->input("catatan.{$kriteria->id}");

            // Check if penilaian exists
            $penilaian = Penilaian::where('peserta_id', $peserta_id)
                ->where('juri_id', $juri_id)
                ->where('kriteria_id', $kriteria->id)
                ->first();

            if ($penilaian) {
                // Update existing
                $penilaian->update([
                    'nilai' => $nilai,
                    'catatan' => $catatan,
                ]);
            } else {
                // Create new
                Penilaian::create([
                    'peserta_id' => $peserta_id,
                    'juri_id' => $juri_id,
                    'kriteria_id' => $kriteria->id,
                    'nilai' => $nilai,
                    'catatan' => $catatan,
                ]);
            }
        }

        return redirect()
            ->route('penilaian.index')
            ->with('success', "Penilaian untuk {$peserta->nama_lengkap} oleh {$juri->nama_lengkap} berhasil diperbarui!");
    }
}
