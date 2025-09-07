<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Data Kelas - Admin</title>
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
  <h1>Data Kelas</h1>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body pt-4">
        <div class="d-flex align-items-center mb-3">
          <h5 class="card-title mb-0">Daftar Kelas</h5>
          <div class="d-flex flex-wrap gap-2 ms-auto">
            <button class="btn btn-success btn-sm d-flex align-items-center" 
                    data-bs-toggle="modal" data-bs-target="#modalImportKelas">
              <i class="bi bi-file-earmark-excel me-1"></i> Upload
            </button>
            <button class="btn btn-primary btn-sm d-flex align-items-center" 
                    data-bs-toggle="modal" data-bs-target="#modalTambahKelas">
              <i class="bi bi-plus-lg me-1"></i> Tambah
            </button>
          </div>
        </div>

        @if(session('success'))
          <!-- Modal Sukses -->
          <div class="modal fade" id="modalSuccess" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content border-success">
                <div class="modal-header bg-success text-white">
                  <h5 class="modal-title">Berhasil</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <p>{{ session('success') }}</p>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-success" data-bs-dismiss="modal">Oke</button>
                </div>
              </div>
            </div>
          </div>
          @endif

          @if(session('import_errors'))
          <!-- Modal Error -->
          <div class="modal fade" id="modalImportError" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content border-warning">
                <div class="modal-header bg-warning text-dark">
                  <h5 class="modal-title">Beberapa Data Gagal Diimpor</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <p>Masalah berikut ditemukan:</p>
                  <ul class="list-group">
                    @foreach(session('import_errors') as $error)
                      <li class="list-group-item list-group-item-warning">{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-warning" data-bs-dismiss="modal">Tutup</button>
                </div>
              </div>
            </div>
          </div>
          @endif

        <table id="tabelKelas" class="table table-bordered">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Kelas</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($kelases as $index => $kelas)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{ $kelas->nama }}</td>
              <td>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditKelas{{ $kelas->id }}">
                  <i class="bi bi-pencil-square"></i>
                </button>
                <form action="{{ route('admin.kelas.destroy', $kelas->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-danger">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="modalEditKelas{{ $kelas->id }}" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <form action="{{ route('admin.kelas.update', $kelas->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Kelas</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <div class="form-floating">
                        <input type="text" name="nama" class="form-control" value="{{ $kelas->nama }}" required>
                        <label>Nama Kelas</label>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      <button class="btn btn-primary">Simpan</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- Modal Tambah -->
  <div class="modal fade" id="modalTambahKelas" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="{{ route('admin.kelas.store') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Tambah Kelas</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="form-floating">
              <input type="text" name="nama" class="form-control" placeholder="X IPA 1" required>
              <label>Nama Kelas</label>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Modal Import Kelas -->
  <div class="modal fade" id="modalImportKelas" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="{{ route('admin.kelas.import') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Upload Excel Kelas</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="file" class="form-label">Pilih file Excel (.xlsx)</label>
              <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls" required>
            </div>
            <div class="alert alert-info">
              Format kolom: <strong>nama_kelas</strong><br>
              <small>Contoh isi file: <code>6A</code>, <code>6B</code></small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success">Upload</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  </main>

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

    @if(session('success') && !session('import_errors'))
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var modal = new bootstrap.Modal(document.getElementById('modalSuccess'));
        modal.show();
      });
    </script>
    @endif

  @if(session('import_errors'))
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var modal = new bootstrap.Modal(document.getElementById('modalImportError'));
      modal.show();
    });
  </script>
  @endif

  <script>
  document.addEventListener("DOMContentLoaded", () => {
  new simpleDatatables.DataTable("#tabelKelas", {
    searchable: true,
    fixedHeight: true,
    perPage: 5,
    labels: {
      perPage: "",          // kosongkan tulisan "entries"
      noRows: "Tidak ada data",
      info: "Menampilkan {start} sampai {end} dari {rows} nama kelas",
      search: "Cari:",
    }
  });
});
</script>

</body>
</html>


