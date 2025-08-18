<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login - SIPENA</title>

    <!-- Bootstrap & AdminLTE -->
    <link rel="stylesheet" href="{{ auto_asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ auto_asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ auto_asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link href="{{ asset('images/favicon-32x32.png') }}" rel="icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            background: linear-gradient(to right, #e3f2fd, #ffffff);
        }

        .login-box {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .login-logo img {
            width: 120px;
            max-width: 100%;
            transition: transform 0.3s ease;
        }

        .login-logo img:hover {
            transform: scale(1.05);
        }

        .login-logo b {
            font-weight: 600;
            color: #007bff;
        }

        .login-card-body {
            border-top: 3px solid #007bff;
        }

        .school-bg {
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        }

        .btn-animate {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-animate:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
        }

    </style>
</head>
<body class="hold-transition login-page school-bg">

<div class="login-box">
    <div class="login-logo mb-4" style="margin-top: 30px;">
        <img src="{{ asset('images/school-logo.png') }}" alt="Logo Sekolah" width="100" class="mb-2">
        <h4 style="font-weight: 700; color: #007bff; margin: 0;">
            SIPENA <span style="font-weight: 400; color: #333;">SDN Pelamunan</span>
        </h4>
    </div>
    <div class="card shadow-sm border-0 rounded-lg mt-3">
        <div class="card-body login-card-body p-4">
            <p class="login-box-msg text-muted mb-2">Silakan login untuk mengakses sistem</p>
            <hr class="mt-1 mb-4">

            @if ($errors->any())
                <div class="alert alert-danger py-2 px-3 small">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('qr_absen_redirect'))
                <div class="alert alert-primary d-flex align-items-center small px-3 py-2 mb-3 shadow-sm rounded fade show" role="alert" style="background-color: #e7f3ff; border-left: 4px solid #007bff;">
                    <i class="fas fa-info-circle mr-2 text-secondary"></i>
                    <div class="flex-grow-1 text-secondary">
                        Silakan login terlebih dahulu untuk melanjutkan absensi melalui <strong>QR Code</strong>.
                    </div>
                    <button type="button" class="close ml-3" data-dismiss="alert" aria-label="Close" style="outline: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf
                <div class="form-group mb-3">
                    <label for="email" class="text-sm text-muted">Email</label>
                    <div class="input-group">
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="password" class="text-sm text-muted">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-lock"></i></div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block rounded-pill btn-animate">
                    <i class="fas fa-sign-in-alt mr-1"></i> Login
                </button>
            </form>

            <!-- Optional footer -->
            <!--
            <p class="mt-3 mb-0 text-center">
                <a href="#" class="text-muted small">Lupa password?</a>
            </p>
            -->
        </div>
    </div>
</div>


<!-- JS Files -->
<script src="{{ auto_asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ auto_asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ auto_asset('dist/js/adminlte.min.js') }}"></script>
</body>
</html>
