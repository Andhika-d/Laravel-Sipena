<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard - SIPENA</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset('images/favicon-32x32.png') }}" rel="icon">
  <link href="{{ asset('images/favicon-32x32.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

</head>

<body>

  @include('admin.header')

  @include('admin.sidebar')

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div>
    <!-- End Page Title -->

    <section class="section dashboard">
    <div class="row">

      <!-- Informasi Aplikasi -->
      <div class="card mt-4">
        <div class="card-body">
          <h5 class="card-title d-flex justify-content-between align-items-center">
            Tentang Aplikasi
            <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal" class="text-muted" data-bs-toggle="tooltip" title="Lihat ringkasan">
              <i class="bi bi-info-circle-fill fs-5"></i>
            </a>
          </h5>
          <p>Sistem ini digunakan untuk mengelola data siswa, guru, kelas, dan mata pelajaran di lingkungan Sekolah Dasar. Admin dapat melakukan input dan update data dengan mudah.</p>
          <ul>
            <li>📚 Manajemen data siswa dan guru</li>
            <li>🗓️ Kelola kelas dan jadwal pelajaran</li>
            <li>📝 Input nilai harian dan absensi guru</li>
            <li>🔒 Role akses berdasarkan pengguna (Admin, Guru, dll)</li>
          </ul>
          <p class="text-muted small">Versi Aplikasi: <strong>1.0.0</strong></p>
        </div>
      </div>
    </div>
  </section>

  <!-- Modal Info -->
  <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 600px; width: 90%;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="infoModalLabel">Ringkasan Data Sekolah</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">

          {{-- Histori Absen & Nilai --}}
          <div class="row g-2 mb-2">
            {{-- Histori Absen --}}
            <div class="col-6 d-flex flex-column">
              <div class="card h-100">
                <div class="card-body p-2">
                  <h6 class="text-muted mb-2 d-flex justify-content-between align-items-center">
                    Absen Terakhir
                    <a href="#" class="ms-2 text-secondary" onclick="openAbsenHistori()">
                      <i class="bi bi-clock-history"></i>
                    </a>
                  </h6>
                  <ul class="list-group small">
                    @forelse ($historiAbsensi as $absen)
                      <li class="list-group-item py-2 px-2 d-flex align-items-start gap-2">
                        <i class="bi bi-calendar-check-fill text-success fs-5 mt-1"></i>
                        <div>
                          <span class="fw-medium">{{ $absen->guru->nama ?? 'Guru telah dihapus' }}</span><br>
                          <small class="text-muted">{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('d F Y') }}</small>
                        </div>
                      </li>
                    @empty
                      <li class="list-group-item text-muted">Belum ada data absen.</li>
                    @endforelse
                  </ul>
                </div>
              </div>
            </div>

            {{-- Histori Nilai --}}
            <div class="col-6 d-flex flex-column">
              <div class="card h-100">
                <div class="card-body p-2">
                  <h6 class="text-muted mb-2 d-flex justify-content-between align-items-center">
                    Penilaian Terakhir
                    <a href="#" class="ms-2 text-secondary" onclick="openNilaiHistori()">
                      <i class="bi bi-clock-history"></i>
                    </a>
                  </h6>
                  <ul class="list-group small">
                    @forelse ($historiNilai as $nilai)
                      <li class="list-group-item py-2 px-2 d-flex align-items-start gap-2">
                        <i class="bi bi-pencil-square text-info fs-5 mt-1"></i>
                        <div>
                          <span class="fw-medium">{{ $nilai->siswa->nama ?? 'Siswa dihapus' }}</span><br>
                          <small class="text-muted">
                            {{ \Carbon\Carbon::parse($nilai->tanggal)->translatedFormat('d F Y') }}<br>
                            Guru: {{ $nilai->guru->nama ?? 'Guru dihapus' }}
                          </small>
                        </div>
                      </li>
                    @empty
                      <li class="list-group-item text-muted">Belum ada data penilaian.</li>
                    @endforelse
                  </ul>
                </div>
              </div>
            </div>
          </div>

          {{-- Info Kartu Statistik --}}
          <div class="row g-2">
            <!-- Total Siswa -->
            <div class="col-6">
              <div class="card info-card mb-1">
                <div class="card-body p-3">
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="width: 36px; height: 36px;">
                      <i class="bi bi-people fs-5"></i>
                    </div>
                    <div class="ps-2">
                      <h6 class="mb-0">{{ $totalSiswa ?? '0' }}</h6>
                      <small class="text-muted">Siswa terdaftar</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Total Guru -->
            <div class="col-6">
              <div class="card info-card mb-1">
                <div class="card-body p-3">
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white" style="width: 36px; height: 36px;">
                      <i class="bi bi-person-badge-fill fs-5"></i>
                    </div>
                    <div class="ps-2">
                      <h6 class="mb-0">{{ $totalGuru ?? '0' }}</h6>
                      <small class="text-muted">Guru aktif</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Total Kelas -->
            <div class="col-6">
              <div class="card info-card mb-1">
                <div class="card-body p-3">
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning text-white" style="width: 36px; height: 36px;">
                      <i class="bi bi-house-door fs-5"></i>
                    </div>
                    <div class="ps-2">
                      <h6 class="mb-0">{{ $totalKelas ?? '0' }}</h6>
                      <small class="text-muted">Kelas aktif</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Total Mapel -->
            <div class="col-6">
              <div class="card info-card mb-1">
                <div class="card-body p-3">
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info text-white" style="width: 36px; height: 36px;">
                      <i class="bi bi-book-half fs-5"></i>
                    </div>
                    <div class="ps-2">
                      <h6 class="mb-0">{{ $totalMapel ?? '0' }}</h6>
                      <small class="text-muted">Mapel tersedia</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  
  <!-- Modal Histori Absen Lengkap -->
  <div class="modal fade" id="modalHistoriAbsenLengkap" tabindex="-1" aria-labelledby="modalHistoriAbsenLabel" aria-hidden="true"
  data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 700px;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalHistoriAbsenLabel">Histori Absensi Guru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
          <ul class="list-group mb-3">
            @forelse ($semuaHistoriAbsensi as $absen)
              <li class="list-group-item py-2 px-2 d-flex align-items-start gap-2">
                <i class="bi bi-calendar-check-fill text-success" style="font-size: 1.1rem; margin-top: 2px;"></i>
                <div>
                  <span class="fw-medium">{{ $absen->guru->nama ?? 'Guru telah dihapus' }}</span><br>
                  <small class="text-muted">{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('d F Y') }}</small>
                </div>
              </li>
            @empty
              <li class="list-group-item text-muted">Belum ada histori absen.</li>
            @endforelse
          </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Histori Penilaian Lengkap -->
  <div class="modal fade" id="modalHistoriNilaiLengkap" tabindex="-1" aria-labelledby="modalHistoriNilaiLabel" aria-hidden="true"
  data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 700px;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalHistoriNilaiLabel">Histori Penilaian Siswa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
          <ul class="list-group mb-3">
            @forelse ($semuaHistoriNilai as $nilai)
              <li class="list-group-item py-2 px-2 d-flex align-items-start gap-2">
                <i class="bi bi-pencil-fill text-info" style="font-size: 1.1rem; margin-top: 2px;"></i>
                <div>
                  <span class="fw-medium">{{ $nilai->siswa->nama ?? 'Siswa dihapus' }}</span><br>
                  <small class="text-muted">
                    {{ \Carbon\Carbon::parse($nilai->tanggal)->translatedFormat('d F Y') }}<br>
                    Guru: {{ $nilai->guru->nama ?? 'Guru dihapus' }}
                  </small>
                </div>
              </li>
            @empty
              <li class="list-group-item text-muted">Belum ada histori penilaian.</li>
            @endforelse
          </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  </main>
  <!-- End #main -->
    
  @include('admin.logoutmodal')

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
  <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>
  <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('assets/js/main.js') }}"></script>

  <script>
    function openNilaiHistori() {
      const infoModal = bootstrap.Modal.getInstance(document.getElementById('infoModal'));
      const nilaiModal = new bootstrap.Modal(document.getElementById('modalHistoriNilaiLengkap'));
      
      infoModal.hide();
      nilaiModal.show();

      document.getElementById('modalHistoriNilaiLengkap').addEventListener('hidden.bs.modal', function () {
        infoModal.show();
      }, { once: true });
    }

    function openAbsenHistori() {
      const infoModal = bootstrap.Modal.getInstance(document.getElementById('infoModal'));
      const absenModal = new bootstrap.Modal(document.getElementById('modalHistoriAbsenLengkap'));
      
      infoModal.hide();
      absenModal.show();

      document.getElementById('modalHistoriAbsenLengkap').addEventListener('hidden.bs.modal', function () {
        infoModal.show();
      }, { once: true });
    }
  </script>

</body>

</html>