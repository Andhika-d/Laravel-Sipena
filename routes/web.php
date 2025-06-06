<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/admin', function () {
  return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/data-master/akun-pengguna', function () {
  return view('admin.DataMaster.users');
})->name('admin.datamaster.users');

Route::get('/data-master/data-guru', [GuruController::class, 'index'])->name('admin.datamaster.dataguru');

Route::get('/guru', function () {
  return view('guru.dashboard');
})->name('guru.dashboard');

Route::get('/kepsek', function () {
  return view('kepsek.dashboard');
})->name('kepsek.dashboard');

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
  Route::post('/guru', [GuruController::class, 'store'])->name('admin.guru.store');
  Route::put('/guru/{id}', [GuruController::class, 'update'])->name('admin.guru.update');
  Route::delete('/guru/{id}', [GuruController::class, 'destroy'])->name('admin.guru.destroy');
});
