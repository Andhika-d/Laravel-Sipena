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
                      <div class="position-relative mb-3">
                      <!-- Tombol Info -->
                      <button type="button" class="btn btn-sm btn-light text-info position-absolute" 
                              style="top: 0; right: 0; z-index: 1;" 
                              data-toggle="modal" data-target="#infoModal">
                        <i class="fas fa-info-circle"> Info</i>
                      </button>
                      <table class="table-borderless">
                        <tbody>
                          <tr>
                            <td style="font-weight: bold !important;">Nama</td>
                            <td style="width: 1px" class="px-2">
                              :
                            </td>
                            <td>{{ $guru->nama }}</td>
                          </tr>
                          <tr>
                            <td style="font-weight: bold !important;">Prodi</td>
                            <td style="width: 1px" class="px-2">
                              :
                            </td>
                            <td>{{ $guru->jurusan_prodi }}</td>
                          </tr>
                          <tr>
                            <td style="font-weight: bold !important;">Mapel Mengajar</td>
                            <td style="width: 1px" class="px-2">
                              :
                            </td>
                            <td>{{ $guru->mengajar }}</td>
                          </tr>
                          <tr>
                            <td style="font-weight: bold !important;">Kelas</td>
                            <td style="width: 1px" class="px-2">
                              :
                            </td>
                            <td>{{ $guru->kelas }}</td>
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
            {{-- ABSEN MASUK / TELAT --}}
            <div class="col-md-6">
              @if ($now->lt($jamMasukMulai))
                {{-- Belum waktu absen --}}
                <div class="info-box">
                  <span class="info-box-icon bg-primary elevation-1">
                    <i class="fas fa-user-tie"></i>
                  </span>
                  <div class="info-box-content">
                    <span class="info-box-number">Absen Masuk (Manual)</span>
                    <small class="text-muted">
                      <i class="fa fa-clock"></i> 06.00 - 08.00 WIB
                    </small>
                  </div>
                </div>
              @elseif ($now->between($jamMasukMulai, $jamMasukSelesai) && (!$absenHariIni || !$absenHariIni->jam_masuk))
                {{-- Absen Masuk --}}
                <form method="POST" action="{{ route('guru.absen.masuk') }}">
                  @csrf
                  <input type="hidden" name="latitude" id="latitude">
                  <input type="hidden" name="longitude" id="longitude">
                  <button type="submit" class="info-box btn btn-link p-0 text-left " style="border:none; background:none;">
                    <span class="info-box-icon bg-primary elevation-1">
                      <i class="fas fa-user-tie"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-number">Absen Masuk (Manual)</span>
                      <small class="text-success">
                        <i class="fa fa-check-circle"></i> Klik untuk Absen Masuk
                      </small>
                      <small class="text-danger location-warning d-none">
                          <i class="fa fa-map-marker-alt"></i> Kamu berada di luar area absen
                      </small>
                    </div>
                  </button>
                </form>
                @elseif ($now->gt($jamMasukSelesai) && (!$absenHariIni || !$absenHariIni->jam_masuk))
                {{-- Telat --}}
                <form method="POST" action="{{ route('guru.absen.masuk') }}">
                  @csrf
                  <input type="hidden" name="latitude" id="latitude">
                  <input type="hidden" name="longitude" id="longitude">
                  <button type="submit" class="info-box btn btn-link p-0 text-left" style="border:none; background:none;">
                    <span class="info-box-icon bg-warning elevation-1">
                      <i class="fas fa-user-tie"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-number">Absen Telat</span>
                      <small class="text-warning">
                        <i class="fa fa-exclamation-circle"></i> Klik untuk Absen Telat
                      </small>
                      <small class="text-danger location-warning d-none">
                          <i class="fa fa-map-marker-alt"></i> Kamu berada di luar area absen
                      </small>
                    </div>
                  </button>
                </form>
              @else
                {{-- Sudah Absen --}}
                <div class="info-box">
                  <span class="info-box-icon bg-success elevation-1">
                    <i class="fas fa-user-check"></i>
                  </span>
                  <div class="info-box-content">
                    <span class="info-box-number">
                      {{ $absenHariIni->is_telat ? 'Sudah Absen Telat' : 'Sudah Absen Masuk' }}
                    </span>
                    <small class="text-muted">
                      <i class="fa fa-check"></i> {{ $absenHariIni->jam_masuk->format('H:i') }}
                    </small>
                  </div>
                </div>
              @endif
            </div>
            {{-- ABSEN PULANG --}}
            <div class="col-md-6">
              @php
                $sudahAbsenMasuk = $absenHariIni && $absenHariIni->jam_masuk;
                $sudahAbsenPulang = $absenHariIni && $absenHariIni->jam_pulang;
              @endphp

              @if (!$sudahAbsenMasuk)
                {{-- Belum Absen Masuk --}}
                <div class="info-box">
                  <span class="info-box-icon bg-secondary elevation-1">
                    <i class="fas fa-user-tie"></i>
                  </span>
                  <div class="info-box-content">
                    <span class="info-box-number">Absen Pulang (Terkunci)</span>
                    <small class="text-muted"><i class="fa fa-clock"></i> Belum Absen Masuk</small>
                  </div>
                </div>

              @elseif ($now->lt($jamPulang))
                {{-- Sudah Absen Masuk tapi Belum Waktu Pulang --}}
                <div class="info-box">
                  <span class="info-box-icon bg-secondary elevation-1">
                    <i class="fas fa-user-tie"></i>
                  </span>
                  <div class="info-box-content">
                    <span class="info-box-number">Absen Pulang (Terkunci)</span>
                    <small class="text-muted"><i class="fa fa-clock"></i> Belum Waktu Pulang</small>
                  </div>
                </div>

              @else
                {{-- Sudah Waktu Pulang --}}
                <form method="POST" action="{{ route('guru.absen.pulang') }}">
                  @csrf
                  <input type="hidden" name="latitude" id="latitude">
                  <input type="hidden" name="longitude" id="longitude">
                  <button type="submit"
                    class="info-box btn btn-link p-0 text-left"
                    style="border: none; background: none;"
                    {{ $sudahAbsenPulang ? 'disabled' : '' }}>

                    <span class="info-box-icon bg-danger elevation-1">
                      <i class="fas fa-user-tie"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-number">Absen Pulang</span>
                      <small class="{{ $sudahAbsenPulang ? 'text-success' : 'text-danger' }}">
                        <i class="fa fa-check-circle"></i>
                        {{ $sudahAbsenPulang ? 'Sudah Absen Pulang' : 'Belum Absen' }}
                      </small>
                    </div>
                  </button>
                </form>
              @endif
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
  <!-- Modal -->
  <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="infoModalLabel">Informasi Absensi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h5 class="mb-3"><i class="fas fa-info-circle text-primary"></i> Panduan Absensi</h5>

          <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <strong>Absen Masuk</strong>
              <span class="badge badge-primary badge-pill">06:00 – 08:00 WIB</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <strong>Absen Telat</strong>
              <span class="badge badge-warning badge-pill">Setelah 08:00 WIB</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <strong>Absen Pulang</strong>
              <span class="badge badge-success badge-pill">Setelah 14:00 WIB</span>
            </li>
          </ul>

          <div class="alert alert-info" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            Guru yang sudah menekan atau scan <strong>Absen Masuk</strong> / <strong>Telat</strong> wajib menekan / scan <strong>Absen Pulang</strong> agar kehadiran terverifikasi.
          </div>

          <p class="text-muted mb-0">
            <i class="fas fa-info-circle"></i> Hanya bisa absen masuk <strong>sekali per hari</strong>.
          </p>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    if (navigator.geolocation) {
      // Script Temu Lokasi
      // navigator.geolocation.getCurrentPosition(
      // function(position) {
      //   const latitude = position.coords.latitude;
      //   const longitude = position.coords.longitude;
      //   console.log("Lokasi lo sekarang:", latitude, longitude); // Tambahin ini
      // },
      // function(error) {
      //   console.error("Gagal dapet lokasi:", error); // Buat liat errornya
      // }
      // );
      navigator.geolocation.getCurrentPosition(
        function (position) {
          const userLat = position.coords.latitude;
          const userLng = position.coords.longitude;
          

          // Set ke input hidden
          document.getElementById("latitude").value = userLat;
          document.getElementById("longitude").value = userLng;

          // Lokasi sekolah/kantor (ganti dengan koordinat aslinya nanti)
          const kantorLat = -6.0757615; // latitude
          const kantorLng = 106.0934919; // longitude

          const distance = getDistanceFromLatLonInMeters(userLat, userLng, kantorLat, kantorLng);

          // Optional: validasi jarak maksimal (contoh: 100 meter)
          const maxDistance = 800;

          console.log("Lokasi user:", userLat, userLng);
          console.log("Lokasi kantor:", kantorLat, kantorLng);
          console.log("Jarak ke kantor:", distance, "meter");

          if (distance > maxDistance) {
          window.isLocationValid = false;

          document.querySelectorAll('form button[type="submit"]').forEach(btn => {
            // Sembunyikan tombolnya biar ga bikin layout aneh
            btn.closest('form').classList.add('d-none');
          });

          // Tampilkan card khusus “di luar area”
          const lokasiInvalidBox = document.createElement('div');
          lokasiInvalidBox.className = 'info-box';
          lokasiInvalidBox.innerHTML = `
            <span class="info-box-icon bg-danger">
              <i class="fas fa-map-marker-alt"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-number text-danger">Lokasi Tidak Valid</span>
              <small class="text-muted">
                Kamu berada di luar area Sekolah (${maxDistance}m)
              </small>
            </div>
          `;

          const targetCol = document.querySelector('.col-md-6'); // ganti selector biar tepat
          targetCol.appendChild(lokasiInvalidBox);
        }
        },
        function (error) {
          alert("Gagal mengambil lokasi. Pastikan kamu mengizinkan akses lokasi di browser.");
        });
    } else {
      alert("Browser tidak mendukung Geolocation.");
    }

    function getDistanceFromLatLonInMeters(lat1, lon1, lat2, lon2) {
      const R = 6371e3; // Radius bumi dalam meter
      const φ1 = lat1 * Math.PI / 180;
      const φ2 = lat2 * Math.PI / 180;
      const Δφ = (lat2 - lat1) * Math.PI / 180;
      const Δλ = (lon2 - lon1) * Math.PI / 180;

      const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ/2) * Math.sin(Δλ/2);
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

      const d = R * c;
      return d;
    }
  });
</script>
@endsection