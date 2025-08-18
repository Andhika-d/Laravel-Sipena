@extends('kepsek.layout')

@section('title', 'Dashboard')

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h1>Dashboard - SIPENA</h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- Main -->
  <section class="content">
    <div class="container-fluid">

      <!-- Sambutan -->
      <div class="row mb-4">
        <div class="col-md-12">
          <div class="alert alert-info">
            <h5>Halo, {{ $namakepsek }} 👋</h5>
            Selamat datang di dashboard Kepala Sekolah.
            <br>
            Klik tombol di bawah untuk melihat statistik absensi guru hari ini.
            <br><br>
            <button class="btn btn-primary" data-toggle="modal" data-target="#rekapModal">
              <i class="fas fa-chart-bar"></i> Lihat Rekap Absensi Hari Ini
            </button>
          </div>
        </div>
      </div>

      <!-- Penjelasan SIPENA -->
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-info-circle"></i> Tentang Aplikasi SIPENA</h3>
            </div>
            <div class="card-body">
              <p>
                SIPENA adalah sistem digital untuk mencatat dan memantau absensi guru secara real-time.
              </p>
              <ul>
                <li>Rekap absensi guru harian</li>
                <li>Notifikasi keterlambatan</li>
                <li>Monitoring status guru (izin/sakit/alfa)</li>
              </ul>
              <p class="text-muted">Fitur lanjutan sedang dikembangkan.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL: Rekap Absensi -->
      <div class="modal fade" id="rekapModal" tabindex="-1" role="dialog" aria-labelledby="rekapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="rekapModalLabel">
                <i class="fas fa-calendar-check mr-2"></i>
                Rekap Absensi Hari Ini ({{ \Carbon\Carbon::now()->format('d M Y') }})
              </h5>
              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span>&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <div class="row">
                @php
                  $boxes = [
                    ['count' => $jumlahHadir, 'label' => 'Guru Hadir', 'color' => 'success', 'icon' => 'user-check'],
                    ['count' => $jumlahIzin, 'label' => 'Izin', 'color' => 'warning', 'icon' => 'user-clock'],
                    ['count' => $jumlahSakit, 'label' => 'Sakit', 'color' => 'info', 'icon' => 'user-md'],
                    ['count' => $jumlahAlfa, 'label' => 'Alfa', 'color' => 'danger', 'icon' => 'user-times'],
                    ['count' => $jumlahBelumAbsen, 'label' => 'Belum Absen', 'color' => 'secondary', 'icon' => 'user-clock'],
                  ];
                @endphp
                {{-- Box Ringkasan --}}
                @foreach ($boxes as $box)
                  <div class="col-6 col-md-4 mb-3">
                    <div class="card bg-{{ $box['color'] }} text-white h-100 shadow-sm rounded">
                      <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                          <h3 class="mb-0 font-weight-bold">{{ $box['count'] }}</h3>
                          <p class="mb-0">{{ $box['label'] }}</p>
                        </div>
                        <i class="fas fa-{{ $box['icon'] }} fa-2x"></i>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>

              {{-- Tambahan: Daftar Nama Guru Hadir --}}
              @if (!empty($listGuruHadir) && count($listGuruHadir) > 0)
                <hr>
                <h6 class="text-primary font-weight-bold mt-3">
                  <i class="fas fa-user-check mr-1"></i> Daftar Guru Hadir:
                </h6>
                <ul class="list-group list-group-flush">
                  @foreach ($listGuruHadir as $nama)
                    <li class="list-group-item px-2 py-1">{{ $loop->iteration }}. {{ $nama }}</li>
                  @endforeach
                </ul>
              @endif
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Tutup</button>
            </div>
          </div>
        </div>
      </div>


    </div>
  </section>
</div>
@endsection