@extends('guru.layout')

@section('title', 'Home')

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Dashboard - SIPENA</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="invoice p-3 mb-3">
            <!-- title row -->
            <div class="row">
              <div class="col-12">
                <h4><i class="fas fa-globe"></i> SIPENA </h4>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <p class="lead">Halo Selamat Datang - {{ $namaGuru }}</p>
                <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                  Apa itu Aplikasi SIPENA ?<br>
                  Aplikasi berbasis Web ini membantu sekolah dalam mencatat dan mengelola data nilai harian siswa serta absensi guru secara digital dan efisien.
                  <br>
                  Tujuan SIPENA adalah untuk membantu menyediakan sistem terintegrasi untuk memudahkan pengelolaan akademik 
                  di lingkungan sekolah secara transparan dan efisien.
                  <br><br>
                  Beberapa fitur yang sekarang sudah tersedia di Aplikasi Sipena ini bisa di lihat pada table
                  di samping yaa!
                </p>
              </div>
              <div class="col-6">
                <div class="table-responsive">
                  <table class="table">
                    <tbody>
                      <tr><th style="width:50%">Pengelola Nilai Harian Siswa </th><td></td></tr>
                      <tr><th>Absensi digital untuk Guru (QR) </th><td></td></tr>
                      <tr><th>- Coming Soon -</th><td></td></tr>
                      <tr><th>- Coming Soon -</th><td></td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <!-- /.row -->
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection