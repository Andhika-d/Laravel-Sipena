<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with('kelas')->get();
        $kelases = Kelas::all();
        return view('admin.datamaster.siswa', compact('siswas', 'kelases'));
    }

    public function create()
    {
        // Tidak perlu, karena pakai modal di index
        return redirect()->route('admin.siswa.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'nis' => 'required|unique:siswa,nis',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        Siswa::create($request->all());

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        // Tidak digunakan kalau tidak pakai halaman detail
        return redirect()->route('admin.siswa.index');
    }

    public function edit(string $id)
    {
        // Tidak digunakan kalau pakai modal
        return redirect()->route('admin.siswa.index');
    }

    public function update(Request $request, string $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nama' => 'required|string',
            'nis' => 'required|unique:siswa,nis,' . $siswa->id,
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $siswa->update($request->all());

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
