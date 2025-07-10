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

    public function rekap(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapel,id',
        ]);

        $kelas = Kelas::findOrFail($request->kelas_id);
        $mapel = Mapel::findOrFail($request->mapel_id);

        $data = NilaiHarian::whereHas('siswa', function ($query) use ($kelas) {
                        $query->where('kelas_id', $kelas->id);
                    })
                    ->where('mapel_id', $mapel->id)
                    ->with(['siswa.kelas', 'mapel'])
                    ->orderBy('tanggal', 'asc')
                    ->get();

        $filename = 'rekap_nilai_' . strtolower(str_replace(' ', '_', $kelas->nama)) . '_' . strtolower(str_replace(' ', '_', $mapel->nama_mapel)) . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($data) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'Nama Siswa', 'Kelas', 'Mapel', 'Tanggal', 'Deskripsi', 'Nilai']);

            foreach ($data as $i => $nilai) {
                fputcsv($handle, [
                    $i + 1,
                    $nilai->siswa->nama,
                    $nilai->siswa->kelas->nama,
                    $nilai->mapel->nama_mapel,
                    \Carbon\Carbon::parse($nilai->tanggal)->format('d-m-Y'),
                    $nilai->deskripsi ?? '-',
                    $nilai->nilai
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

}