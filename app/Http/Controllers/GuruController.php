<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index() {
        $gurus = Guru::all();
        return view('admin.DataMaster.DataGuru', compact('gurus'));
    }

    public function create() {
        return view('admin.guru.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jk' => 'required|in:L,P',
            'jurusan' => 'required|string|max:255',
            'mengajar' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
        ]);

        Guru::create([
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jk,
            'jurusan_prodi' => $request->jurusan,
            'mengajar' => $request->mengajar,
            'kelas' => $request->kelas,
        ]);

        return redirect()->back()->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit($id) {
        $guru = Guru::findOrFail($id);
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'jurusan_prodi' => 'required|string|max:255',
            'mengajar' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
        ]);
    
        $guru = Guru::findOrFail($id);
        $guru->update($request->all());
    
        return redirect()->back()->with('success', 'Data guru berhasil diupdate.');
    }
    
    public function destroy($id) {
        $guru = Guru::findOrFail($id);
        $guru->delete();
    
        return redirect()->back()->with('success', 'Data guru berhasil dihapus.');
    }
}
