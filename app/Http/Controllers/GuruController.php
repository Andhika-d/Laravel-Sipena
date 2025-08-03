<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index() {
        $gurus = Guru::with('mapel')->get();
        $kelases = Kelas::all();
        $mapels = Mapel::all();
        return view('admin.DataMaster.DataGuru', compact('gurus','kelases', 'mapels'));
    }

    public function create() {
        return view('admin.guru.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jk' => 'required|in:L,P',
            'mapel' => 'required|array', // validasi baru
            'mapel.*' => 'exists:mapel,id', // pastikan id mapel valid
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $guru = Guru::create([
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jk,
            'kelas_id' => $request->kelas_id,
        ]);

        // Simpan relasi mapel ke pivot
        $guru->mapel()->attach($request->mapel);

        return redirect()->back()->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit($id) {
        $guru = Guru::with('mapel')->findOrFail($id);
        $mapels = Mapel::all();
        return view('admin.guru.edit', compact('guru', 'mapels'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_ids' => 'required|array',
            'mapel_ids.*' => 'exists:mapel,id',
        ]);
    
        $guru = Guru::findOrFail($id);
        $guru->update([
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kelas_id' => $request->kelas_id,
        ]);

        $guru->mapel()->sync($request->mapel_ids);

        return redirect()->back()->with('success', 'Data guru berhasil diupdate.');
    }
    
    public function destroy($id) {
        $guru = Guru::findOrFail($id);
        $guru->delete();
    
        return redirect()->back()->with('success', 'Data guru berhasil dihapus.');
    }
}
