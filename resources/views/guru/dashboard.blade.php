@extends('guru.layout')

@section('title', 'Dashboard Guru - SIPENA')

@section('content')
<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="m-0">Selamat Datang, {{ $namaGuru }}</h1>
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item active">Beranda</li>
        </ol>
      </div>
    </div>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Welcome Card -->
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Informasi SIPENA</h3>
        </div>
        <div class="card-body row">
          <div class="col-md-6">
            <p class="lead">
              Halo, <span style="font-weight: 600; color: #007bff;">{{ $namaGuru }}</span> 👋
            </p>
            <p class="text-muted">
              <strong>Apa itu SIPENA?</strong><br>
              SIPENA adalah sistem berbasis web untuk membantu sekolah mengelola <strong>nilai harian siswa</strong> dan <strong>absensi guru</strong> secara digital. Sistem ini bertujuan untuk memberikan proses akademik yang efisien, modern, dan transparan.
            </p>
            <ul class="mt-3 mb-2">
              <li>Mengelola nilai siswa dengan mudah</li>
              <li>Absensi guru digital dengan lokasi</li>
              <li>Dashboard pribadi guru</li>
            </ul>
          </div>
          <div class="col-md-6">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead class="thead-light">
                  <tr>
                    <th>Fitur Tersedia</th>
                  </tr>
                </thead>
                <tbody>
                  <tr><td><i class="fas fa-check-circle text-success mr-1"></i> Pengelolaan Nilai Harian</td></tr>
                  <tr><td><i class="fas fa-check-circle text-success mr-1"></i> Absensi Digital Guru</td></tr>
                  <tr><td><i class="fas fa-clock text-muted mr-1"></i> Fitur Lanjutan <em>(coming soon)</em></td></tr>
                  <tr><td><i class="fas fa-clock text-muted mr-1"></i> Fitur Lanjutan <em>(coming soon)</em></td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>
@endsection