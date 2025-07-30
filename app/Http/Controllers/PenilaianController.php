<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\NilaiHarian;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapNilaiExport;

class PenilaianController extends Controller
{
    // TAMPILAN UTAMA PENILAIAN
    public function index(Request $request)
{
    $siswa = Siswa::with('kelas')->get();
    $mapels = Mapel::all();
    $kelases = Kelas::all();
    $kkm = 75;

    $query = NilaiHarian::with(['siswa.kelas', 'mapel'])
        ->where('guru_id', Auth::user()->guru->id);

    // Ambil semua data (tanpa paginasi) untuk statistik
    $allData = (clone $query)->get();

    if ($request->filled('siswa_id')) {
        $query->where('siswa_id', $request->siswa_id);
    }

    $nilaiHarian = $query->orderBy('tanggal', 'desc')->paginate(3)->withQueryString();

    // Statistik akurat dari semua data
    $rataRataNilai = $allData->avg('nilai') ?? 0;
    $jumlahLulus = $allData->where('nilai', '>=', $kkm)->count();
    $jumlahTidakLulus = $allData->where('nilai', '<', $kkm)->count();

    return view('guru.penilaian.index', compact(
        'siswa', 'mapels', 'nilaiHarian', 'kelases',
        'kkm', 'rataRataNilai', 'jumlahLulus', 'jumlahTidakLulus','allData'
    ));
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
            'siswa_id' => $request->input('siswa_id'),
            'mapel_id' => $request->input('mapel_id'),
            'guru_id' => Auth::user()->guru->id,
            'tanggal' => $request->input('tanggal'),
            'deskripsi' => $request->input('deskripsi'),
            'nilai' => $request->input('nilai'),
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
            'deskripsi' => $request->input('deskripsi_tugas'),
            'nilai' => $request->input('nilai'),
            'tanggal' => $request->input('tanggal'),
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

        $data = NilaiHarian::whereHas('siswa', function ($q) use ($kelas) {
                        $q->where('kelas_id', $kelas->id);
                    })
                    ->where('mapel_id', $mapel->id)
                    ->with(['siswa.kelas', 'mapel'])
                    ->get();

        if ($data->isEmpty()) {
            return redirect()->back()->with([
                'rekap_kosong' => true,
                'kelas_nama' => $kelas->nama,
                'mapel_nama' => $mapel->nama_mapel,
            ]);
        }

        $rataRataPerSiswa = $data->groupBy('siswa_id')->map(function ($item) {
            return [
                'nama' => $item->first()->siswa->nama,
                'kelas' => $item->first()->siswa->kelas->nama,
                'mapel' => $item->first()->mapel->nama_mapel,
                'rata_rata' => $item->avg('nilai'),
            ];
        });

        $filename = 'Rekap Nilai ' . $kelas->nama . ' - ' . $mapel->nama_mapel . '.xlsx';

        return Excel::download(new RekapNilaiExport($data, $rataRataPerSiswa), $filename);
    }

}