<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Data Guru - Admin</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset('images/favicon-32x32.png') }}" rel="icon">
  <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

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
      <h1>Data Guru</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item active">Data Guru</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Tabel Data Guru</h5>
              <div class="col-md-4">
                <input type="text" id="searchGuru" class="form-control form-control-sm mb-3" placeholder="Cari Guru...">
              </div>
              <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahGuru">
              <i class="bi bi-plus-lg"></i> Guru</button>

              <!-- Modal Tambah Guru -->
              <div class="modal fade" id="modalTambahGuru" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <form autocomplete="off" action="{{ route('admin.guru.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                      <h5 class="modal-title">Tambah Data Guru</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="floatingNama" name="nama" placeholder="Nama">
                      <label for="floatingNama">Nama</label>
                    </div>
                    <div class="form-floating mb-3">
                      <select class="form-select" id="floatingJk" name="jk" aria-label="Pilih jenis kelamin">
                        <option selected disabled>-</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                      </select>
                      <label for="floatingJk">Jenis Kelamin</label>
                    </div>
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="floatingJurusan" name="jurusan" placeholder="Jurusan">
                      <label for="floatingJurusan">Jurusan</label>
                    </div>
                    <div class="mb-3">
                      <label for="floatingMengajar" class="form-label">Mengajar</label>
                      <select name="mapel[]" id="floatingMengajar" class="form-select" multiple>
                        @foreach ($mapels as $m)
                          <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-floating mb-3">
                    <select class="form-select" id="floatingKelas" name="kelas_id" required>
                      <option selected disabled>Pilih Kelas</option>
                      @foreach ($kelases as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                      @endforeach
                    </select>
                    <label for="floatingKelas">Kelas</label>
                    </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                  </form>
                </div>
              </div>
              </div>

              <!-- Default Table -->
               <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Jk</th>
                    <th scope="col">Jurusan</th>
                    <th scope="col">Mengajar</th>
                    <th scope="col">Kelas</th>
                    <th scope="col">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($gurus as $key => $guru)
                <tr>
                  <td class="align-middle">{{ $key + 1 }}</td>
                  <td class="align-middle">{{ $guru->nama }}</td>
                  <td class="align-middle">
                    {{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                  </td>
                  <td class="align-middle">{{ $guru->jurusan_prodi }}</td>
                  <td class="align-middle" style="max-width: 300px; white-space: normal;">
                    @foreach ($guru->mapel as $m)
                      <span class="badge bg-primary me-1 mb-1">{{ $m->nama_mapel }}</span>
                    @endforeach
                  </td>
                  <td class="align-middle">{{ $guru->kelas->nama ?? '-' }}</td>
                  <td class="text-center align-middle">
                  <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <!-- Tombol Edit -->
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditGuru{{ $guru->id }}">
                      <i class="bi bi-pencil-square"></i>
                    </button>

                    <!-- Form Hapus -->
                    <form action="{{ route('admin.guru.destroy', $guru->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEditGuru{{ $guru->id }}" tabindex="-1" aria-labelledby="modalEditGuruLabel{{ $guru->id }}" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <form autocomplete="off" action="{{ route('admin.guru.update', $guru->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalEditGuruLabel{{ $guru->id }}">Edit Data Guru</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">

                          <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="nama" value="{{ $guru->nama }}" placeholder="Nama">
                            <label>Nama</label>
                          </div>

                          <div class="form-floating mb-3">
                            <select class="form-select" name="jenis_kelamin" aria-label="Pilih jenis kelamin">
                              <option value="L" {{ $guru->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                              <option value="P" {{ $guru->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            <label>Jenis Kelamin</label>
                          </div>

                          <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="jurusan_prodi" value="{{ $guru->jurusan_prodi }}" placeholder="Jurusan">
                            <label>Jurusan</label>
                          </div>

                          <div class="mb-3">
                            <label for="editMapelSelect{{ $guru->id }}" class="form-label">Mapel yang Diampu</label>
                            <select class="form-select" name="mapel_ids[]" multiple id="editMapelSelect{{ $guru->id }}">
                              @foreach ($mapels as $mapel)
                                <option value="{{ $mapel->id }}" {{ $guru->mapel->contains($mapel->id) ? 'selected' : '' }}>
                                  {{ $mapel->nama_mapel }}
                                </option>
                              @endforeach
                            </select>
                          </div>

                          <div class="mb-3">
                            <label for="editKelasSelect{{ $guru->id }}" class="form-label">Kelas</label>
                            <select class="form-select" name="kelas_id" id="editKelasSelect{{ $guru->id }}" required>
                              <option disabled>Pilih Kelas</option>
                              @foreach ($kelases as $kelas)
                                <option value="{{ $kelas->id }}" {{ $guru->kelas_id == $kelas->id ? 'selected' : '' }}>
                                  {{ $kelas->nama }}
                                </option>
                              @endforeach
                            </select>
                          </div>

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

                @endforeach
                </tbody>
              </table>
              </div>
              <!-- End Default Table Example -->
            </div>
          </div>
      </div>
    </section>
    
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
  <!-- Manual JS -->
  <script>
  document.getElementById('searchGuru').addEventListener('keyup', function () {
    const keyword = this.value.toLowerCase();
    const rows = document.querySelectorAll('table tbody tr');

    rows.forEach(row => {
      const rowText = row.textContent.toLowerCase();
      row.style.display = rowText.includes(keyword) ? '' : 'none';
      });
    });
  </script>

</body>

</html>