<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Data Siswa - Admin</title>

  <link href="{{ asset('images/favicon-32x32.png') }}" rel="icon">
  <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <link href="https://fonts.googleapis.com/css?family=Open+Sans|Nunito|Poppins" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>

@include('admin.header')
@include('admin.sidebar')

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Data Siswa</h1>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body pt-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
          <h5 class="card-title mb-2 mb-sm-0">Daftar Siswa</h5>
          <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-success btn-sm d-flex align-items-center"
                    data-bs-toggle="modal" data-bs-target="#modalUploadSiswa">
              <i class="bi bi-upload me-1"></i> Upload
            </button>
            <button class="btn btn-primary btn-sm d-flex align-items-center"
                    data-bs-toggle="modal" data-bs-target="#modalTambahSiswa">
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

        @php $errorsImport = session()->pull('import_errors'); @endphp
        @if(session('import_errors'))
        <!-- Modal Import Error -->
        <div class="modal fade" id="modalImportError" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-warning">
              <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Beberapa Data Gagal Diimpor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <p>Beberapa baris gagal diimpor karena masalah berikut:</p>
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

        <div class="table-responsive">
          <form method="GET" action="{{ route('admin.siswa.index') }}" class="mb-3">
              <div class="input-group">
                  <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama atau NIS">
                  <button class="btn btn-primary">Cari</button>
              </div>
          </form>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>NIS</th>
              <th>Kelas</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($siswas as $index => $siswa)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{ $siswa->nama }}</td>
              <td>{{ $siswa->nis }}</td>
              <td>{{ $siswa->kelas->nama ?? '-' }}</td>
              <td>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditSiswa{{ $siswa->id }}">
                  <i class="bi bi-pencil-square"></i>
                </button>
                <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-danger">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="modalEditSiswa{{ $siswa->id }}" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <form action="{{ route('admin.siswa.update', $siswa->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Siswa</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <div class="form-floating mb-3">
                        <input type="text" name="nama" class="form-control" value="{{ $siswa->nama }}" required>
                        <label>Nama</label>
                      </div>
                      <div class="form-floating mb-3">
                        <input type="text" name="nis" class="form-control" value="{{ $siswa->nis }}" required>
                        <label>NIS</label>
                      </div>
                      <div class="form-floating">
                        <select name="kelas_id" class="form-select" required>
                          <option value="">Pilih Kelas</option>
                          @foreach($kelases as $kelas)
                            <option value="{{ $kelas->id }}" {{ $siswa->kelas_id == $kelas->id ? 'selected' : '' }}>
                              {{ $kelas->nama }}
                            </option>
                          @endforeach
                        </select>
                        <label>Kelas</label>
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
        <div>
            {{ $siswas->links() }}
        </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Modal Tambah -->
  <div class="modal fade" id="modalTambahSiswa" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="{{ route('admin.siswa.store') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Tambah Siswa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="form-floating mb-3">
              <input type="text" name="nama" class="form-control" placeholder="Nama Siswa" required>
              <label>Nama</label>
            </div>
            <div class="form-floating mb-3">
              <input type="text" name="nis" class="form-control" placeholder="NIS" required>
              <label>NIS</label>
            </div>
            <div class="form-floating">
              <select name="kelas_id" class="form-select" required>
                <option value="">Pilih Kelas</option>
                @foreach($kelases as $kelas)
                  <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                @endforeach
              </select>
              <label>Kelas</label>
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

  <!-- Modal Upload Siswa -->
<div class="modal fade" id="modalUploadSiswa" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Upload Excel Siswa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="file" class="form-label">Pilih file Excel (.xlsx)</label>
            <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls" required>
          </div>
          <div class="alert alert-info">
            Format: <strong>nama, nis, kelas</strong><br>
            <small>Contoh kelas: <code>1A</code>, <code>2B</code></small>
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

<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
@if(session('success') && !session('import_errors'))
<script>
  window.addEventListener('load', function () {
    var modal = new bootstrap.Modal(document.getElementById('modalSuccess'));
    modal.show();
  });
</script>
@endif

@if(session('import_errors'))
<script>
  window.addEventListener('load', function () {
    var modal = new bootstrap.Modal(document.getElementById('modalImportError'));
    modal.show();
  });
</script>
@endif

</body>
</html>
