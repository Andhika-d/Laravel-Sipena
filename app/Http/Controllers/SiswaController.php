<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with('kelas')->get();
        $kelases = Kelas::all();
        return view('admin.datamaster.siswa', compact('siswas', 'kelases'));
    }

    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls',
    ]);

    $import = new SiswaImport();
    Excel::import($import, $request->file('file'));
    $rows = $import->rows;


    DB::beginTransaction();
    $errors = [];

    try {
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Skip header

            $nama = trim($row[0] ?? '');
            $nis = trim($row[1] ?? '');
            $nama_kelas = trim($row[2] ?? '');

            // Cek kelengkapan
            if (!$nama || !$nis || !$nama_kelas) {
                $errors[] = "Baris ke-" . ($index + 1) . " tidak lengkap.";
                continue;
            }

            // Cari kelas
            $kelas = Kelas::where('nama', $nama_kelas)->first();
            if (!$kelas) {
                $errors[] = "Baris ke-" . ($index + 1) . ": Kelas '$nama_kelas' tidak ditemukan.";
                continue;
            }

            // Validasi NIS unik
            if (Siswa::where('nis', $nis)->exists()) {
                $errors[] = "Baris ke-" . ($index + 1) . ": NIS '$nis' sudah terdaftar.";
                continue;
            }

            // Simpan data
            Siswa::create([
                'nama' => $nama,
                'nis' => $nis,
                'kelas_id' => $kelas->id,
            ]);
        }

        DB::commit();

        $message = 'Import selesai.';
        if (count($errors)) {
            $message .= ' Beberapa baris dilewati.';
            return redirect()->route('admin.siswa.index')
                ->with('success', $message)
                ->with('import_errors', $errors);
        }

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Semua data siswa berhasil diimport.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['msg' => 'Gagal import: ' . $e->getMessage()]);
    }
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
