<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\GuruAkunController;
use App\Http\Controllers\UserGuruController;
use App\Http\Controllers\AbsensiGuruController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\UserKepsekController;
use App\Http\Controllers\KepsekRekapController;
use App\Http\Controllers\AdminDashboardController;
use App\Exports\RekapAbsensiExport;
use Maatwebsite\Excel\Facades\Excel;


Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/data-master/akun-pengguna', [GuruAkunController::class, 'index'])->name('admin.datamaster.users');

Route::get('/data-master/data-guru', [GuruController::class, 'index'])->name('admin.datamaster.dataguru');

Route::get('/guru', function () {
  return view('guru.dashboard');
})->name('guru.dashboard');

Route::get('/kepsek', function () {
  return view('kepsek.dashboard');
})->name('kepsek.dashboard');

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
  //Akses Admin
  Route::get('/', [AdminDashboardController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.dashboard');

  //CRUD data Guru
  Route::post('/guru', [GuruController::class, 'store'])->name('admin.guru.store');
  Route::put('/guru/{id}', [GuruController::class, 'update'])->name('admin.guru.update');
  Route::delete('/guru/{id}', [GuruController::class, 'destroy'])->name('admin.guru.destroy');

  //Buat Akun Guru
  Route::get('/guru-akun/create', [GuruAkunController::class, 'create'])->name('admin.guru-akun.create');
  Route::post('/guru-akun', [GuruAkunController::class, 'store'])->name('admin.guru-akun.store');
  Route::put('/guru-akun/{id}', [GuruAkunController::class, 'update'])->name('admin.guru-akun.update');
  Route::delete('/guru-akun/{id}', [GuruAkunController::class, 'destroy'])->name('admin.guru-akun.destroy');


  // Kelas CRUD
  Route::resource('kelas', KelasController::class)->names('admin.kelas');

  // Siswa CRUD
  Route::resource('siswa', SiswaController::class)->names('admin.siswa');

  // Mapel CRUD
  Route::resource('mapel', MapelController::class)->names('admin.mapel');

});

Route::prefix('guru')->middleware('auth')->group(function () {
    Route::get('/', [UserGuruController::class, 'dashboard'])->name('guru.dashboard');

    Route::get('/absensi', [AbsensiGuruController::class, 'index'])->name('guru.absensi');
    Route::post('/absensi/masuk', [AbsensiGuruController::class, 'absenMasuk'])->name('guru.absen.masuk');
    Route::post('/absensi/pulang', [AbsensiGuruController::class, 'absenPulang'])->name('guru.absen.pulang');
    Route::post('/absensi/izin', [AbsensiGuruController::class, 'ajukanIzin'])->name('guru.absen.izin');

    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('guru.penilaian');
    Route::post('/penilaian/simpan', [PenilaianController::class, 'store'])->name('guru.penilaian.store');
    Route::put('/penilaian/{id}', [PenilaianController::class, 'update'])->name('guru.penilaian.update');
    Route::delete('/penilaian/{id}', [PenilaianController::class, 'destroy'])->name('guru.penilaian.destroy');
    Route::get('/penilaian/rekap', [PenilaianController::class, 'rekap'])->name('guru.penilaian.rekap');

});

Route::prefix('kepsek')->middleware(['auth', 'role:kepsek'])->group(function () {
    Route::get('/', [UserKepsekController::class, 'dashboard'])->name('kepsek.dashboard');
    Route::get('/rekap-absensi', [KepsekRekapController::class, 'index'])->name('kepsek.rekap');
});

Route::get('/rekap-absensi/export', function (Request $request) {
    $bulan = $request->input('bulan', date('m'));
    $tahun = $request->input('tahun', date('Y'));

    return Excel::download(new RekapAbsensiExport($bulan, $tahun), 'rekap_absensi.xlsx');
})->name('rekap-absensi.export');
