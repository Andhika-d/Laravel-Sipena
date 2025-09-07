@extends('guru.layout')

@section('title', 'Home')

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Absensi - SIPENA</h1>
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
                      <button type="button"
                              class="btn bg-transparent text-info position-absolute d-flex align-items-center justify-content-center gap-1"
                              style="top: 0; right: 0; padding: 0 0.5rem; border-radius: 999px;"
                              data-toggle="modal"
                              data-target="#infoModal"
                              title="Info">
                        <i class="fas fa-info-circle"></i>
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
                            <td style="font-weight: bold !important;">Mapel</td>
                            <td style="width: 1px" class="px-2">
                              :
                            </td>
                            <td>
                              @if ($guru->mapel && $guru->mapel->count())
                                {{ $guru->mapel->pluck('nama_mapel')->join(', ') }}
                              @else
                                -
                              @endif
                            </td>
                          </tr>
                          <tr>
                            <td style="font-weight: bold !important;">Kelas</td>
                            <td style="width: 1px" class="px-2">
                              :
                            </td>
                            <td>{{ $guru->kelas->nama ?? '-' }}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Absensi -->
          <div class="card-body">
            <div class="row">
              <div class="col">
                <div class="text-center" style="font-weight: bold !important;">PILIH ABSEN</div>
                @if (!$absenHariIni)
                <button type="button" class="btn btn-sm btn-outline-secondary float-left mt-4 mt-md-2" data-toggle="modal" data-target="#izinModal">
                  <i class="fas fa-notes-medical"></i> Izin/Sakit
                </button>
              @else
                <button type="button" class="btn btn-sm btn-outline-secondary float-left mt-4 mt-md-2" disabled>
                  <i class="fas fa-notes-medical"></i> Sudah Absen
                </button>
              @endif
              </div>
            </div>

            <div class="row my-3">
              {{-- ABSEN MASUK / TELAT --}}
              <div class="col-md-6" id="absen-masuk">
                @if ($absenHariIni && in_array($absenHariIni->status_kehadiran, ['izin', 'sakit']))
                  {{-- Sudah Izin / Sakit --}}
                  <div class="info-box">
                    <span class="info-box-icon elevation-1">
                      <i class="fas fa-user-slash"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-number">Tidak Perlu Absen</span>
                      <small class="text-muted">Anda mengajukan {{ $absenHariIni->status_kehadiran }} hari ini</small>
                    </div>
                  </div>

                  @elseif ((!$absenHariIni || !$absenHariIni->jam_masuk) && $now->gt(\Carbon\Carbon::createFromTime(15, 0)))
                  {{-- Terlambat Absen dan Lewat dari jam 15.00: Absen dikunci --}}
                  <div class="info-box">
                    <span class="info-box-icon bg-secondary elevation-1">
                      <i class="fas fa-user-lock"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-number">Absen Masuk (Terkunci)</span>
                      <small class="text-muted">
                        <i class="fa fa-clock"></i> Anda tidak melakukan absen sebelum pukul 15.00
                      </small>
                    </div>
                  </div>

                @elseif ($now->lt($jamMasukMulai))
                  {{-- Belum waktu absen --}}
                  <div class="info-box">
                    <span class="info-box-icon bg-primary elevation-1">
                      <i class="fas fa-user-tie"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-number">Absen Masuk</span>
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

                  <button id="btn-absen-masuk" type="submit" class="w-100 p-0 m-0 text-left" style="border: none; background: none;">
                    <div class="info-box">
                      <span class="info-box-icon bg-primary elevation-1">
                        <i class="fas fa-user-tie"></i>
                      </span>
                      <div class="info-box-content text-start">
                        <span class="info-box-number">Absen Masuk</span>
                        <small class="text-success">
                          <i class="fa fa-check-circle"></i> Klik untuk Absen Masuk
                        </small>
                        <small class="text-danger location-warning d-none">
                          <i class="fa fa-map-marker-alt"></i> Kamu berada di luar area absen
                        </small>
                      </div>
                    </div>
                  </button>
                </form>

                @elseif ($now->gt($jamMasukSelesai) && (!$absenHariIni || !$absenHariIni->jam_masuk))
                {{-- Telat --}}
                <form method="POST" action="{{ route('guru.absen.masuk') }}">
                  @csrf
                  <input type="hidden" name="latitude" id="latitude">
                  <input type="hidden" name="longitude" id="longitude">

                  <button id="btn-absen-telat" type="submit" class="w-100 p-0 m-0 text-left" style="border: none; background: none;">
                    <div class="info-box">
                      <span class="info-box-icon bg-warning elevation-1">
                        <i class="fas fa-user-tie"></i>
                      </span>
                      <div class="info-box-content text-start">
                        <span class="info-box-number">Absen Telat</span>
                        <small class="text-warning">
                          <i class="fa fa-exclamation-circle"></i> Klik untuk Absen Telat
                        </small>
                        <small class="text-danger location-warning d-none">
                          <i class="fa fa-map-marker-alt"></i> Kamu berada di luar area absen
                        </small>
                      </div>
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
              <div class="col-md-6" id="absen-pulang">
                @php
                  $sudahAbsenMasuk = $absenHariIni && $absenHariIni->jam_masuk;
                  $sudahAbsenPulang = $absenHariIni && $absenHariIni->jam_pulang;
                @endphp

                @if ($absenHariIni && in_array($absenHariIni->status_kehadiran, ['izin', 'sakit']))
                  {{-- Sudah Izin / Sakit --}}
                  <div class="info-box">
                    <span class="info-box-icon elevation-1">
                      <i class="fas fa-user-slash"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-number">Tidak Perlu Absen Pulang</span>
                      <small class="text-muted">Izin/Sakit: otomatis terkunci</small>
                    </div>
                  </div>

                @elseif (!$sudahAbsenMasuk)
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
                  @if ($sudahAbsenPulang)
                  {{-- TAMPILAN JIKA SUDAH ABSEN PULANG --}}
                  <div class="info-box">
                    <span class="info-box-icon bg-success elevation-1">
                      <i class="fas fa-user-check"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-number">Sudah Absen Pulang</span>
                      <small class="text-muted">
                        <i class="fa fa-check"></i> {{ $absenHariIni->jam_pulang->format('H:i') }}
                      </small>
                    </div>
                  </div>
                  @else
                    {{-- TAMPILAN JIKA BELUM ABSEN PULANG --}}
                    <form method="POST" action="{{ route('guru.absen.pulang') }}">
                      @csrf
                      <input type="hidden" name="latitude" id="latitude">
                      <input type="hidden" name="longitude" id="longitude">
                      <button type="submit"
                        class="info-box btn btn-link p-0 text-left"
                        style="border: none; background: none;">
                        <span class="info-box-icon bg-danger elevation-1">
                          <i class="fas fa-user-tie"></i>
                        </span>
                        <div class="info-box-content">
                          <span class="info-box-number">Absen Pulang</span>
                          <small class="text-danger">
                            <i class="fa fa-check-circle"></i> Belum Absen
                          </small>
                        </div>
                      </button>
                    </form>
                  @endif
                @endif
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  </section>

  @if(session('modal_error'))
<style>
  /* Biar modal pas di HP, gak terlalu lebar */
  .custom-modal .modal-dialog {
    max-width: 90%;
    margin: auto;
  }

  .custom-modal .modal-content {
    border-radius: 1rem;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
  }

  .custom-modal .modal-header {
    border-bottom: none;
    justify-content: center;
    background: #dc3545;
    color: #fff;
    border-top-left-radius: 1rem;
    border-top-right-radius: 1rem;
  }

  .custom-modal .modal-body {
    padding: 1.5rem;
    text-align: center;
  }

  .custom-modal .modal-body p {
    margin-bottom: 0.75rem;
  }
</style>

<div class="modal fade custom-modal" id="lokasiModal" tabindex="-1" aria-labelledby="lokasiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="lokasiModalLabel">
          <i class="fas fa-exclamation-circle me-2"></i> Absen Gagal
        </h5>
      </div>

      <div class="modal-body">
        <p class="text-muted">{{ session('error_message') }}</p>
        @if(session('jarak'))
          <p><strong>Jarak Anda:</strong><br> 
            <span class="text-danger fs-5">
              {{ number_format(session('jarak') * 1000, 0, ',', '.') }} meter
            </span>
          </p>
        @endif
        <small class="text-muted">Klik di luar untuk menutup</small>
      </div>

    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(document.getElementById('lokasiModal'));
    modal.show();
  });
</script>
@endif

@if(session('modal_success'))
<style>
  .custom-modal .modal-dialog {
    max-width: 90%;
    margin: auto;
  }

  .custom-modal .modal-content {
    border-radius: 1rem;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
  }

  .custom-modal .modal-header {
    border-bottom: none;
    justify-content: center;
    background: #28a745;
    color: #fff;
    border-top-left-radius: 1rem;
    border-top-right-radius: 1rem;
  }

  .custom-modal .modal-body {
    padding: 1.5rem;
    text-align: center;
  }

  .custom-modal .modal-body p {
    margin-bottom: 0.75rem;
  }
</style>

<div class="modal fade custom-modal" id="suksesModal" tabindex="-1" aria-labelledby="suksesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="suksesModalLabel">
          <i class="fas fa-check-circle me-2"></i> Absen Berhasil
        </h5>
      </div>

      <div class="modal-body">
        <p class="text-muted">{{ session('success_message') ?? 'Anda telah berhasil melakukan absen.' }}</p>

        @if(session('jarak'))
          <p><strong>Jarak Anda:</strong><br>
            <span class="text-success fs-5">
              {{ number_format(session('jarak') * 1000, 0, ',', '.') }} meter
            </span>
          </p>
        @endif

        <small class="text-muted">Klik di luar untuk menutup</small>
      </div>

    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(document.getElementById('suksesModal'));
    modal.show();
  });
</script>
@endif

@if(session('modal_info'))
<style>
  .custom-modal .modal-dialog {
    max-width: 90%;
    margin: auto;
  }

  .custom-modal .modal-content {
    border-radius: 1rem;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
  }

  .custom-modal .modal-header {
    border-bottom: none;
    justify-content: center;
    background: #17a2b8;
    color: #fff;
    border-top-left-radius: 1rem;
    border-top-right-radius: 1rem;
  }

  .custom-modal .modal-body {
    padding: 1.5rem;
    text-align: center;
  }

  .custom-modal .modal-body p {
    margin-bottom: 0.75rem;
  }
</style>

<div class="modal fade custom-modal" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="infoModalLabel">
          <i class="fas fa-info-circle me-2"></i> Informasi
        </h5>
      </div>

      <div class="modal-body">
        <p class="text-muted">
          {{ session('info_message') ?? 'Anda sudah melakukan absen hari ini.' }}
        </p>

        @if(session('jarak'))
        <p><strong>Jarak Anda:</strong><br>
          <span class="text-info fs-5">
            {{ number_format(session('jarak') * 1000, 0, ',', '.') }} meter
          </span>
        </p>
        @endif

        <small class="text-muted">Klik di luar untuk menutup</small>
      </div>

    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(document.getElementById('infoModal'));
    modal.show();
  });
</script>
@endif

  <!-- Modal Info -->
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
              <span class="badge badge-primary badge-pill pulse-badge">06:00 – 08:00 WIB</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <strong>Absen Telat</strong>
              <span class="badge badge-warning badge-pill pulse-badge">Setelah 08:00 WIB</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <strong>Absen Pulang</strong>
              <span class="badge badge-success badge-pill pulse-badge">Setelah 14:00 WIB</span>
            </li>
          </ul>

          <div class="alert alert-info" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            Guru yang sudah menekan atau scan <strong>Absen Masuk</strong> / <strong>Telat</strong> wajib menekan / scan <strong>Absen Pulang</strong> agar kehadiran terverifikasi.
          </div>

          <div class="alert alert-danger mt-3" role="alert">
            <i class="fas fa-times-circle"></i>
            Guru yang belum absen hingga <strong>15:00 WIB</strong> akan dianggap <strong>tidak hadir (alfa)</strong>.
          </div>

          <p class="text-muted mb-0">
            <i class="fas fa-info-circle"></i> Hanya bisa absen masuk <strong>sekali per hari</strong>.
          </p>
          
          @if ($absenHariIni)
          <div class="mt-3 d-flex align-items-center">
            <i class="fas fa-user-check mr-2 text-info"></i>
            <div>
              <span class="text-muted">Status absensi hari ini:</span><br>
              @if ($absenHariIni->status_kehadiran === 'izin')
                <span class="badge badge-warning"><i class="fas fa-notes-medical"></i> Izin</span>
              @elseif ($absenHariIni->status_kehadiran === 'sakit')
                <span class="badge badge-warning"><i class="fas fa-bed"></i> Sakit</span>
              @elseif ($absenHariIni->jam_masuk)
                @if ($absenHariIni->is_telat)
                  <span class="badge badge-danger"><i class="fas fa-clock"></i> Telat - {{ $absenHariIni->jam_masuk->format('H:i') }}</span>
                @else
                  <span class="badge badge-success"><i class="fas fa-check-circle"></i> Hadir - {{ $absenHariIni->jam_masuk->format('H:i') }}</span>
                @endif
              @else
                <span class="badge badge-secondary">Belum Absen</span>
              @endif
            </div>
          </div>
        @endif

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Izin / Sakit -->
  <div class="modal fade" id="izinModal" tabindex="-1" role="dialog" aria-labelledby="izinModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalIzinSakitLabel">Izin/Sakit</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('guru.absen.izin') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <!-- Nama Guru (readonly dropdown look) -->
          <div class="form-group mb-3">
            <label for="guru_id">Nama Guru</label>
            <select class="form-control" disabled>
              <option>{{ Auth::user()->guru->nama }}</option>
            </select>
          </div>

          <!-- user_id dikirim sebagai hidden -->
          <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

          <!-- Tanggal -->
          <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
          </div>

          <!-- Jenis Izin -->
          <div class="form-group">
            <label for="status_kehadiran">Jenis Izin</label>
            <select class="form-control" id="status_kehadiran" name="status_kehadiran" required>
              <option value="">-- Pilih --</option>
              <option value="izin">Izin</option>
              <option value="sakit">Sakit</option>
            </select>
          </div>

          <!-- Deskripsi -->
          <div class="form-group">
            <label for="deskripsi">Keterangan</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Tuliskan keterangan tambahan (opsional)"></textarea>
          </div>

          <!-- File Bukti -->
          <div class="form-group">
            <label for="file_pendukung">Upload Bukti (jika ada)</label>
            <input type="file" class="form-control-file" id="file_pendukung" name="file_pendukung">
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Kirim</button>
      </div>
      </form>
    </div>
  </div>
</div>

</div>

<script id="lokasi-kantor" type="application/json">
  {!! json_encode($lokasiKantor) !!}
</script>

<script>
  const lokasiKantor = JSON.parse(
    document.getElementById("lokasi-kantor").textContent
  );

  document.addEventListener("DOMContentLoaded", function () {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        function (position) {
          const userLat = position.coords.latitude;
          const userLng = position.coords.longitude;
          const accuracy = position.coords.accuracy;
          
          console.log("Latitude:", userLat);
          console.log("Longitude:", userLng);
          console.log("Akurasi lokasi:", accuracy + " meter");

          document.getElementById("latitude").value = userLat;
          document.getElementById("longitude").value = userLng;

          const distance = getDistanceFromLatLonInKm(
            lokasiKantor.latitude,
            lokasiKantor.longitude,
            userLat,
            userLng
          ) * 1000;

          // Menentukan batas radius lokasi kantor (misalnya 0.2 km = 200 meter)
          const radiusMaksimum = 200;

          if (distance > radiusMaksimum) {
            document.querySelectorAll(".location-warning").forEach(el => {
              el.classList.remove("d-none");
            });

            const forms = document.querySelectorAll("form[action*='absen']");
            forms.forEach(form => {
              form.addEventListener("submit", function (e) {
                e.preventDefault();
                alert("Gagal absen: Anda berada di luar radius kantor.");
              });

              const button = form.querySelector("button[type='submit']");
              if (button) {
                button.disabled = true;
                button.style.cursor = "not-allowed";
                button.title = "Lokasi kamu di luar radius";
              }
            });
          }
        },
        function (error) {
          console.error("Gagal mendapatkan lokasi:", error);
        },
        {
          enableHighAccuracy: true,
          timeout: 10000,
          maximumAge: 0
        }
      );
    } else {
      console.error("Geolocation tidak didukung oleh browser ini.");
    }
  });

  function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius bumi dalam KM
    const dLat = deg2rad(lat2 - lat1);
    const dLon = deg2rad(lon2 - lon1);
    const a =
      Math.sin(dLat / 2) * Math.sin(dLat / 2) +
      Math.cos(deg2rad(lat1)) *
        Math.cos(deg2rad(lat2)) *
        Math.sin(dLon / 2) *
        Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    const d = R * c; // Jarak dalam KM
    return d;
  }

  function deg2rad(deg) {
    return deg * (Math.PI / 180);
  }
</script>
@endsection