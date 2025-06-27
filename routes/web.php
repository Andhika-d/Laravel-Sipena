<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\GuruAkunController;
use App\Http\Controllers\UserGuruController;
use App\Http\Controllers\AbsensiGuruController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\MapelController;


Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/admin', function () {
  return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/data-master/akun-pengguna', [GuruAkunController::class, 'index'])->name('admin.datamaster.users');

// Route::get('/admin/datamaster/kelas', [KelasController::class, 'index'])->name('admin.datamaster.kelas');

Route::get('/data-master/data-guru', [GuruController::class, 'index'])->name('admin.datamaster.dataguru');
// Route::get('/data-master/data-akun', [GuruController::class, 'index'])->name('admin.datamaster.dataakun');

Route::get('/guru', function () {
  return view('guru.dashboard');
})->name('guru.dashboard');

Route::get('/kepsek', function () {
  return view('kepsek.dashboard');
})->name('kepsek.dashboard');

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
  //CRUD data Guru
  Route::post('/guru', [GuruController::class, 'store'])->name('admin.guru.store');
  Route::put('/guru/{id}', [GuruController::class, 'update'])->name('admin.guru.update');
  Route::delete('/guru/{id}', [GuruController::class, 'destroy'])->name('admin.guru.destroy');

  //Buat Akun Guru
  Route::get('/guru-akun/create', [GuruAkunController::class, 'create'])->name('admin.guru-akun.create');
  Route::post('/guru-akun', [GuruAkunController::class, 'store'])->name('admin.guru-akun.store');

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
    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('guru.penilaian');
    Route::post('/penilaian/simpan', [PenilaianController::class, 'store'])->name('guru.penilaian.store');
    Route::put('/penilaian/{id}', [PenilaianController::class, 'update'])->name('guru.penilaian.update');
    Route::delete('/penilaian/{id}', [PenilaianController::class, 'destroy'])->name('guru.penilaian.destroy');

});
