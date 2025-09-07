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
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Siswa::with('kelas');

        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('nis', 'like', "%{$search}%");
        }

        $siswas = $query->orderBy('nama')->paginate(5)->appends($request->query());

        $kelases = Kelas::all();

        return view('admin.datamaster.siswa', compact('siswas', 'kelases', 'search'));
    }

    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls',
    ]);

    $rows = Excel::toCollection(new SiswaImport, $request->file('file'))->first();

    DB::beginTransaction();
    $errors = [];

    try {
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Skip header

            $nama = trim($row[0] ?? '');
            $nis = trim($row[1] ?? '');
            $nama_kelas = strtoupper(trim($row[2] ?? ''));

            // Ambil hanya "2A", "3B" → buang kata "Kelas", titik, spasi dll
            $nama_kelas = preg_replace('/[^0-9A-Za-z]/', '', $nama_kelas);

            if (!$nama || !$nis || !$nama_kelas) {
                $errors[] = "Baris ke-" . ($index + 1) . " tidak lengkap.";
                continue;
            }

            $kelas = Kelas::whereRaw("REPLACE(UPPER(nama), ' ', '') = ?", [$nama_kelas])->first();
            if (!$kelas) {
                $errors[] = "Baris ke-" . ($index + 1) . ": Kelas '{$row[2]}' tidak ditemukan.";
                continue;
            }

            if (Siswa::where('nis', $nis)->exists()) {
                $errors[] = "Baris ke-" . ($index + 1) . ": NIS '$nis' sudah terdaftar.";
                continue;
            }

            Siswa::create([
                'nama' => $nama,
                'nis' => $nis,
                'kelas_id' => $kelas->id,
            ]);
        }

        DB::commit();

        if (count($errors)) {
            return redirect()->route('admin.siswa.index')
                ->with('success', 'Import selesai dengan beberapa error.')
                ->with('import_errors', $errors);
        }

        return redirect()->route('admin.siswa.index')
            ->with('import_errors', $errors);
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
