<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard - SIPENA</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ secure_asset('images/favicon-32x32.png') }}" rel="icon">
  <link href="{{ secure_asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ secure_asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ secure_asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

  <link href="{{ secure_asset('assets/css/style.css') }}" rel="stylesheet">

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
      <!-- Tombol Info Pengumuman -->
      <div class="text-end mb-3">
        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#pengumumanModal">
          <i class="bi bi-bell"></i> Pengumuman
        </button>
      </div>

      <!-- Informasi Aplikasi -->
      <div class="card mt-4">
        <div class="card-body">
          <h5 class="card-title">Tentang Aplikasi</h5>
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

      <!-- Info Cards -->
      <div class="row">

        <!-- Total Siswa -->
        <div class="col-md-3">
          <div class="card info-card">
            <div class="card-body">
              <h5 class="card-title">Total Siswa</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle bg-primary text-white d-flex align-items-center justify-content-center">
                  <i class="bi bi-people"></i>
                </div>
                <div class="ps-3">
                  <h6>{{ $totalSiswa ?? '0' }}</h6>
                  <span class="text-muted small pt-2">Siswa terdaftar</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Total Guru -->
        <div class="col-md-3">
          <div class="card info-card">
            <div class="card-body">
              <h5 class="card-title">Total Guru</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle bg-success text-white d-flex align-items-center justify-content-center">
                  <i class="bi bi-person-badge-fill"></i>
                </div>
                <div class="ps-3">
                  <h6>{{ $totalGuru ?? '0' }}</h6>
                  <span class="text-muted small pt-2">Guru aktif</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Total Kelas -->
        <div class="col-md-3">
          <div class="card info-card">
            <div class="card-body">
              <h5 class="card-title">Jumlah Kelas</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle bg-warning text-white d-flex align-items-center justify-content-center">
                  <i class="bi bi-house-door"></i>
                </div>
                <div class="ps-3">
                  <h6>{{ $totalKelas ?? '0' }}</h6>
                  <span class="text-muted small pt-2">Kelas Aktif</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Total Mapel -->
        <div class="col-md-3">
          <div class="card info-card">
            <div class="card-body">
              <h5 class="card-title">Mata Pelajaran</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle bg-info text-white d-flex align-items-center justify-content-center">
                  <i class="bi bi-book-half"></i>
                </div>
                <div class="ps-3">
                  <h6>{{ $totalMapel ?? '0' }}</h6>
                  <span class="text-muted small pt-2">Mapel tersedia</span>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </section>
<!-- Modal Pengumuman -->
<div class="modal fade" id="pengumumanModal" tabindex="-1" aria-labelledby="pengumumanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pengumumanModalLabel">Pengumuman Sekolah</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-flush">
          <li class="list-group-item">📢 Upacara bendera hari Senin pukul 07.00 WIB. Semua siswa wajib hadir.</li>
          <li class="list-group-item">📝 Pendaftaran ekstrakurikuler ditutup tanggal 15 Juli.</li>
          <li class="list-group-item">💉 Mohon orang tua memperhatikan jadwal vaksin siswa minggu depan.</li>
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

</body>

</html>