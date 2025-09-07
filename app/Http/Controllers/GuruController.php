<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GuruImport;

class GuruController extends Controller
{
    public function index() {
        $gurus = Guru::with('mapel')->get();
        $kelases = Kelas::all();
        $mapels = Mapel::all();
        return view('admin.DataMaster.DataGuru', compact('gurus','kelases', 'mapels'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'fileExcel' => 'required|mimes:xls,xlsx',
        ]);

        Excel::import(new GuruImport, $request->file('fileExcel'));

        return redirect()->back()->with('success', 'Data guru berhasil diimport.');
    }

    public function create() {
        return view('admin.guru.create');
    }

    public function store(Request $request) {
        // validasi dasar
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tipe_guru' => 'required|in:kelas,mapel',
        ]);

        if ($request->tipe_guru === 'kelas') {
            // validasi khusus guru kelas
            $request->validate([
                'kelas_id' => 'required|exists:kelas,id',
            ]);

            $guru = Guru::create([
                'nama' => $request->nama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tipe_guru' => 'kelas',
                'kelas_id' => $request->kelas_id,
            ]);

            // auto assign semua mapel kecuali PJOK & PAI
            $mapels = Mapel::whereNotIn('nama_mapel', ['PJOK', 'PAI'])->pluck('id');
            $guru->mapel()->attach($mapels);

        } else {
            // validasi khusus guru mapel
            $request->validate([
                'mapel' => 'required|array',
                'mapel.*' => 'exists:mapel,id',
            ]);

            $guru = Guru::create([
                'nama' => $request->nama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tipe_guru' => 'mapel',
                'kelas_id' => null, // guru mapel khusus ga punya kelas tetap
            ]);

            // simpan mapel sesuai pilihan
            $guru->mapel()->attach($request->mapel);
        }

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
            'tipe_guru' => 'required|in:kelas,mapel', // tambahin validasi tipe
            'kelas_id' => 'nullable|exists:kelas,id',
            'mapel_ids' => 'nullable|array',
            'mapel_ids.*' => 'exists:mapel,id',
        ]);

        $guru = Guru::findOrFail($id);

        // update basic info
        $guru->update([
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tipe_guru' => $request->tipe_guru,
            'kelas_id' => $request->tipe_guru == 'kelas' ? $request->kelas_id : null, 
        ]);

        // handle mapel sesuai tipe guru
        if ($request->tipe_guru == 'kelas') {
            // otomatis semua mapel kecuali PJOK & PAI
            $mapels = Mapel::whereNotIn('nama_mapel', ['PJOK', 'PAI'])->pluck('id');
            $guru->mapel()->sync($mapels);
        } else {
            // manual pilih mapel
            $guru->mapel()->sync($request->mapel_ids ?? []);
        }

        return redirect()->back()->with('success', 'Data guru berhasil diupdate.');
    }
    
    public function destroy($id) {
        $guru = Guru::findOrFail($id);
        $guru->delete();
    
        return redirect()->back()->with('success', 'Data guru berhasil dihapus.');
    }
}
