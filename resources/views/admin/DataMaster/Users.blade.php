<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Data Akun - Admin</title>
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
      <h1>Data Pengguna</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item active">Data Pengguna</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
      <div class="card">
            <div class="card-body">
              <h5 class="card-title">Tabel Akun Pengguna</h5>
              <div class="col-md-4">
                <input type="text" id="searchInput" class="form-control form-control-sm mb-3" placeholder="Cari pengguna...">
              </div>
              <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahAkun">
              <i class="bi bi-plus-lg"></i> Akun</button>

              <!-- Modal Tambah Akun -->
              <div class="modal fade" id="modalTambahAkun" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <form autocomplete="off" action="{{ route('admin.guru-akun.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                      <h5 class="modal-title">Tambah Akun Guru</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                      {{-- Pilih Guru --}}
                      <div class="form-floating mb-3">
                        <select name="guru_id" class="form-select">
                        <option value="" disabled selected>-- Pilih Guru --</option>
                        @foreach ($gurus as $guru)
                          <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                        @endforeach
                        </select>
                        <label for="floatingGuru">Nama Guru</label>
                      </div>

                      {{-- Email --}}
                      <div class="form-floating mb-3 position-relative">
                        <input type="text" class="form-control pe-5" id="floatingEmail" name="email" placeholder="Email" required>
                        <label for="floatingEmail">Email (tanpa @sipena.com)</label>
                        <span class="position-absolute top-50 end-0 translate-middle-y text-muted me-3">@sipena.com</span>
                      </div>

                      {{-- Password --}}
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="floatingPassword" name="password" placeholder="Password" required>
                        <label for="floatingPassword">Password</label>
                      </div>

                      {{-- Role --}}
                      <div class="form-floating mb-3">
                        <select class="form-select" id="floatingRole" name="role" required>
                          <option value="guru">Guru</option>
                          <option value="kepsek">Kepala Sekolah</option>
                        </select>
                        <label for="floatingRole">Role</label>
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

              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr class="align-middle">
                      <th scope="col">No</th>
                      <th scope="col">Nama</th>
                      <th scope="col">Email</th>
                      <th scope="col">Akun dibuat</th>
                      <th scope="col">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($users as $index => $user)
                    <tr>
                      <th scope="row">{{ $index + 1 }}</th>
                      <td>{{ $user->name }}</td>
                      <td>{{ $user->email }}</td>
                      <td>{{ $user->created_at->format('d M Y') }}</td>
                      <td class="align-middle">
                        <div class="d-flex justify-content-center gap-1 flex-wrap">
                          <!-- Tombol Edit -->
                          <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditAkun{{ $user->id }}">
                            <i class="bi bi-pencil-square"></i>
                          </button>
                          <form action="{{ route('admin.guru-akun.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                              <i class="bi bi-trash"></i>
                            </button>
                          </form>
                        </div>
                        <!-- Modal Edit (dimasukkan ke dalam foreach) -->
                            <div class="modal fade" id="modalEditAkun{{ $user->id }}" tabindex="-1">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                  <form action="{{ route('admin.guru-akun.update', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                      <h5 class="modal-title">Edit Akun</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                      <!-- Email -->
                                      <div class="form-floating mb-3 position-relative">
                                        <input type="text" class="form-control pe-5" name="email_prefix" value="{{ explode('@', $user->email)[0] }}" required>
                                        <label>Email (tanpa @sipena.com)</label>
                                        <span class="position-absolute top-50 end-0 translate-middle-y text-muted me-3">@sipena.com</span>
                                      </div>

                                      <!-- Password -->
                                      <div class="form-floating mb-3">
                                        <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak diubah">
                                        <label>Password Baru (opsional)</label>
                                      </div>

                                      <!-- Role -->
                                      <div class="form-floating mb-3">
                                        <select class="form-select" name="role" required>
                                          <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru</option>
                                          <option value="kepsek" {{ $user->role == 'kepsek' ? 'selected' : '' }}>Kepala Sekolah</option>
                                        </select>
                                        <label>Role</label>
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
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
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

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const form = document.querySelector('#modalTambahAkun form');
      const emailInput = form.querySelector('input[name="email"]');

      form.addEventListener('submit', function () {
        const val = emailInput.value.trim();
        if (val && !val.includes('@')) {
          emailInput.value = val + '@sipena.com';
        }
      });
    });
  </script>

  
</body>

</html>