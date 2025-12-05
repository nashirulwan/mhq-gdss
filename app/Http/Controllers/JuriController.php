<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Juri;
use App\Models\Penilaian;

class JuriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $juris = Juri::withCount('penilaians')
            ->orderBy('nama_lengkap')
            ->paginate(10);

        return view('juri.index', compact('juris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $keahlianOptions = [
            'Tajwid',
            'Qiraat',
            'Fasohah',
            'Makharijul Huruf',
            'Tarannum'
        ];

        return view('juri.create', compact('keahlianOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
            'keahlian' => 'required|string|in:Tajwid,Qiraat,Fasohah,Makharijul Huruf,Tarannum',
            'is_active' => 'boolean',
        ]);

        $juri = Juri::create($request->all());

        return redirect()
            ->route('juri.index')
            ->with('success', 'Juri ' . $juri->nama_lengkap . ' berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $juri = Juri::with(['penilaians.peserta', 'penilaians.kriteria'])
            ->findOrFail($id);

        return view('juri.show', compact('juri'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $juri = Juri::findOrFail($id);
        $keahlianOptions = [
            'Tajwid',
            'Qiraat',
            'Fasohah',
            'Makharijul Huruf',
            'Tarannum'
        ];

        return view('juri.edit', compact('juri', 'keahlianOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $juri = Juri::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
            'keahlian' => 'required|string|in:Tajwid,Qiraat,Fasohah,Makharijul Huruf,Tarannum',
            'is_active' => 'boolean',
        ]);

        $juri->update($request->all());

        return redirect()
            ->route('juri.index')
            ->with('success', 'Data juri berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $juri = Juri::findOrFail($id);

        // Hapus semua penilaian terkait
        Penilaian::where('juri_id', $juri->id)->delete();

        $juri->delete();

        return redirect()
            ->route('juri.index')
            ->with('success', 'Juri ' . $juri->nama_lengkap . ' berhasil dihapus!');
    }
}
