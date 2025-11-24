<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\Penilaian;

class PesertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pesertas = Peserta::withCount('penilaians')
            ->orderBy('nama_lengkap')
            ->paginate(10);

        return view('peserta.index', compact('pesertas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoriOptions = ['1 Juz', '3 Juz', '5 Juz', '10 Juz', '15 Juz', '20 Juz', '30 Juz'];

        return view('peserta.create', compact('kategoriOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nomor_peserta' => 'required|string|max:50|unique:pesertas,nomor_peserta',
            'instansi' => 'required|string|max:255',
            'kategori' => 'required|string|in:1 Juz,3 Juz,5 Juz,10 Juz,15 Juz,20 Juz,30 Juz',
            'usia' => 'required|integer|min:6|max:100',
            'kontak' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $peserta = Peserta::create($request->all());

        return redirect()
            ->route('peserta.index')
            ->with('success', 'Peserta ' . $peserta->nama_lengkap . ' berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $peserta = Peserta::with(['penilaians.juri', 'penilaians.kriteria'])
            ->findOrFail($id);

        return view('peserta.show', compact('peserta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $peserta = Peserta::findOrFail($id);
        $kategoriOptions = ['1 Juz', '3 Juz', '5 Juz', '10 Juz', '15 Juz', '20 Juz', '30 Juz'];

        return view('peserta.edit', compact('peserta', 'kategoriOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $peserta = Peserta::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nomor_peserta' => 'required|string|max:50|unique:pesertas,nomor_peserta,' . $peserta->id,
            'instansi' => 'required|string|max:255',
            'kategori' => 'required|string|in:1 Juz,3 Juz,5 Juz,10 Juz,15 Juz,20 Juz,30 Juz',
            'usia' => 'required|integer|min:6|max:100',
            'kontak' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $peserta->update($request->all());

        return redirect()
            ->route('peserta.index')
            ->with('success', 'Data peserta berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $peserta = Peserta::findOrFail($id);

        // Hapus semua penilaian terkait
        Penilaian::where('peserta_id', $peserta->id)->delete();

        $peserta->delete();

        return redirect()
            ->route('peserta.index')
            ->with('success', 'Peserta ' . $peserta->nama_lengkap . ' berhasil dihapus!');
    }
}
