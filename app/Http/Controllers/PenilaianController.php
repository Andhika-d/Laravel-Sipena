<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\NilaiHarian;

class PenilaianController extends Controller
{
    // TAMPILAN UTAMA PENILAIAN
    public function index()
    {
        $siswa = Siswa::with('kelas')->get();
        $mapels = Mapel::all();
        $kelases = Kelas::all();
        $nilaiHarian = NilaiHarian::with(['siswa.kelas', 'mapel'])
            ->where('guru_id', Auth::user()->guru->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('guru.penilaian.index', compact('siswa', 'mapels', 'nilaiHarian', 'kelases'));
    }

    // SIMPAN NILAI HARIAN
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'mapel_id' => 'required|exists:mapel,id',
            'tanggal' => 'required|date',
            'deskripsi' => 'nullable|string',
            'nilai' => 'required|integer|min:0|max:100',
        ]);

        NilaiHarian::create([
            'siswa_id' => $request->siswa_id,
            'mapel_id' => $request->mapel_id,
            'guru_id' => Auth::user()->guru->id,
            'tanggal' => $request->tanggal,
            'deskripsi' => $request->deskripsi,
            'nilai' => $request->nilai,
        ]);

        return back()->with('success', 'Nilai berhasil disimpan.');
    }

    public function edit($id)
    {
        $nilai = NilaiHarian::with(['siswa', 'mapel', 'kelas'])->findOrFail($id);
        $siswas = Siswa::all(); // atau filter sesuai guru
        $mapels = Mapel::all();
        $kelases = Kelas::all();

        return view('guru.penilaian.edit', compact('nilai', 'siswas', 'mapels', 'kelases'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'deskripsi_tugas' => 'required|string',
            'nilai' => 'required|numeric|min:0|max:100',
            'tanggal' => 'required|date',
        ]);

        $nilai = NilaiHarian::findOrFail($id);
        $nilai->update([
            'deskripsi' => $request->deskripsi_tugas,
            'nilai' => $request->nilai,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('guru.penilaian')->with('success', 'Data nilai berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $nilai = NilaiHarian::findOrFail($id);
        $nilai->delete();

        return redirect()->route('guru.penilaian')->with('success', 'Data nilai berhasil dihapus.');
    }
}