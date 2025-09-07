<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Setting Jam - Admin</title>

  <link href="{{ asset('images/favicon-32x32.png') }}" rel="icon">
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>
  @include('admin.header')
  @include('admin.sidebar')

  <main id="main" class="main">

    <div class="pagetitle mb-3">
      <h1><i class="bi bi-clock-history me-2"></i> Pengaturan Jam Absen</h1>
      <p class="text-muted">Atur jam masuk, jam pulang, dan toleransi keterlambatan guru.</p>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-12">

          {{-- Notifikasi --}}
          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
              <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
              <strong>Terjadi kesalahan:</strong>
              <ul class="mb-0">
                @foreach($errors->all() as $err)
                  <li>{{ $err }}</li>
                @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          {{-- Card Form --}}
          <div class="card shadow-sm border-0">
            <div class="card-body p-4">
              <h5 class="card-title mb-3"><i class="bi bi-gear-wide-connected me-2"></i> Setting Jam</h5>
              <p class="small text-muted mb-4">Isi jam sesuai format 24 jam.</p>

              <form action="{{ route('admin.advance.updatejam') }}" method="POST" class="row g-4">
                @csrf
                @method('PUT')

                <div class="col-md-3">
                  <label class="form-label fw-semibold">Jam Masuk Mulai</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-alarm"></i></span>
                    <input type="time" name="jam_masuk_mulai" class="form-control"
                      value="{{ old('jam_masuk_mulai', $jam->jam_masuk_mulai ?? '') }}">
                  </div>
                </div>

                <div class="col-md-3">
                  <label class="form-label fw-semibold">Jam Masuk Selesai</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-hourglass-split"></i></span>
                    <input type="time" name="jam_masuk_selesai" class="form-control"
                      value="{{ old('jam_masuk_selesai', $jam->jam_masuk_selesai ?? '') }}">
                  </div>
                </div>

                <div class="col-md-3">
                  <label class="form-label fw-semibold">Jam Absen Ditutup</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-exclamation-triangle"></i></span>
                    <input type="time" name="jam_telat" class="form-control"
                      value="{{ old('jam_telat', $jam->jam_telat ?? '') }}">
                  </div>
                </div>

                <div class="col-md-3">
                  <label class="form-label fw-semibold">Jam Pulang</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-door-open"></i></span>
                    <input type="time" name="jam_pulang" class="form-control"
                      value="{{ old('jam_pulang', $jam->jam_pulang ?? '') }}">
                  </div>
                </div>

                <div class="text-end mt-4">
                  <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Simpan Pengaturan
                  </button>
                </div>
              </form>

            </div>
          </div>

        </div>
      </div>
    </section>

  </main>

  @include('admin.logoutmodal')

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>