<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Imports\KelasImport;
use Maatwebsite\Excel\Facades\Excel;

class KelasController extends Controller
{
    public function index()
    {
        $kelases = Kelas::all();
        return view('admin.DataMaster.kelas', compact('kelases'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $import = new KelasImport();

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Exception $e) {
            return redirect()->route('admin.kelas.index')->with('error', 'Gagal membaca file Excel.');
        }

        if (count($import->errors)) {
            return redirect()
                ->route('admin.kelas.index')
                ->with('success', 'Import selesai dengan beberapa catatan.')
                ->with('import_errors', $import->errors);
        }

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil diimport.');
    }
    public function create()
    {
        return view('admin.kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        Kelas::create([
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil ditambahkan.');
    }

    public function show(Kelas $kelas)
    {
        return view('admin.kelas.show', compact('kelas'));
    }

    public function edit(Kelas $kelas)
    {
        return view('admin.kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kela) {
    $kela->update([
        'nama' => $request->nama
    ]);
    return redirect()->route('admin.kelas.index')->with('success', 'Berhasil update');
    }


    public function destroy(Kelas $kela)
    {
        $kela->delete();
        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil dihapus.');
    }
}
