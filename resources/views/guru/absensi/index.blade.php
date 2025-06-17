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
            <li class="breadcrumb-item active">Absensi</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- Main Conten -->
  <section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-header">
            <div class="callout callout-warning my-1">
              <div class="row">
                <div class="col">
                  <div class="row">
                    <div class="table-responsive">
                      <table class="table-borderless">
                        <tbody>
                          <tr>
                            <td style="font-weight: bold !important;">Nama</td>
                            <td style="width: 1px" class="px-2">
                              :
                            </td>
                            <td>Nama Guru</td>
                          </tr>
                          <tr>
                            <td style="font-weight: bold !important;">Tahun Pelajaran</td>
                            <td style="width: 1px" class="px-2">
                              :
                            </td>
                            <td>2024/2025</td>
                          </tr>
                          <tr>
                            <td style="font-weight: bold !important;">Semester</td>
                            <td style="width: 1px" class="px-2">
                              :
                            </td>
                            <td>Ganjil</td>
                          </tr>
                          <tr>
                            <td style="font-weight: bold !important;">Tanggal</td>
                            <td style="width: 1px" class="px-2">
                              :
                            </td>
                            <td>17-06-2025</td>
                          </tr>
                          <tr>
                            <td style="font-weight: bold !important;">QR Code</td>
                            <td style="width: 1px" class="px-2">
                              :
                            </td>
                            <td>
                              <a
                                href="#"
                                class="text-primary "
                                target="_blank"
                              >
                                Download
                              </a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Absen Manual -->
          <div class="card-body">
            <div class="row">
              <div class="col">
                <div class="text-center" style="font-weight: bold !important;">PILIH ABSEN </div>
              </div>
            </div>
            <div class="row my-3">
              <div class="col-md-6">
                <a href="#">
                  <div class="info-box">
                    <span class="info-box-icon bg-primary elevation-1">
                      <i class="fas fa-user-tie"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-number">Absen Masuk (Manual)</span>
                      <small class="text-danger">
                        <i class="fa fa-question-circle"></i>
                        Belum Absen
                      </small>
                    </div>
                  </div>
                </a>
              </div>

              <div class="col-md-6">
                <a href="#">
                  <div class="info-box">
                    <span class="info-box-icon bg-primary elevation-1">
                      <i class="fas fa-user-tie"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-number">Absen Pulang (Manual)</span>
                      <small class="text-danger">
                        <i class="fa fa-question-circle"></i>
                        Belum Absen
                      </small>
                    </div>
                  </div>
                </a>
              </div>
            </div>

            <!-- Absen QR -->
            <!-- <div class="row my-3">
              <div class="col-md-6">
                <a href="#">
                  <div class="info-box">
                    <span class="info-box-icon bg-primary elevation-1">
                      <i class="fas fa-camera"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-number">Absen Masuk (QR)</span>
                      <small class="text-danger">
                        <i class="fa fa-question-circle"></i>
                        Belum Absen
                      </small>
                    </div>
                  </div>
                </a>
              </div>

              <div class="col-md-6">
                <a href="#">
                  <div class="info-box">
                    <span class="info-box-icon bg-primary elevation-1">
                      <i class="fas fa-camera"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-number">Absen Pulang</span>
                      <small class="text-danger">
                        <i class="fa fa-question-circle"></i>
                        Belum Absen
                      </small>
                    </div>
                  </div>
                </a>
              </div>
            </div> -->
          </div>
        </div>
      </div>
    </div>
  </div>
    </section>
  </div>
@endsection