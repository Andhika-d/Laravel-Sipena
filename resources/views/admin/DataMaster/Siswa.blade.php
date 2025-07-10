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
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="card-title">Daftar Siswa</h5>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahSiswa">
            <i class="bi bi-plus-lg"></i> Tambah
          </button>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

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
</main>

  @include('admin.logoutmodal')

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

</body>
</html>
