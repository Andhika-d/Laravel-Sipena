<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Imports\MapelImport;

class MapelController extends Controller
{
    public function index()
    {
        $mapels = Mapel::all();
        return view('admin.datamaster.mapel', compact('mapels'));
    }
public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls',
    ]);

    $file = $request->file('file');
    $import = new MapelImport();

    try {
        Excel::import($import, $file);
    } catch (\Exception $e) {
        return redirect()->route('admin.mapel.index')->with('error', 'Gagal membaca file Excel.');
    }

    $errors = $import->errors;

    if (count($errors)) {
        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Import selesai dengan beberapa catatan.')
            ->withErrors($errors);
    }

    return redirect()->route('admin.mapel.index')->with('success', 'Data mapel berhasil diimport.');
}
    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
        ]);

        Mapel::create($request->only('nama_mapel'));

        return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $mapel = Mapel::findOrFail($id);

        $request->validate([
            'nama_mapel' => 'required|string|max:255',
        ]);

        $mapel->update($request->only('nama_mapel'));

        return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $mapel = Mapel::findOrFail($id);
        $mapel->delete();

        return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
