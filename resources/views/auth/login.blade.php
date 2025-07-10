<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login - SIPENA</title>

    <!-- Bootstrap & AdminLTE -->
    <link rel="stylesheet" href="{{ auto_asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ auto_asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ auto_asset('plugins/fontawesome-free/css/all.min.css') }}">
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
    </style>
</head>
<body class="hold-transition login-page school-bg">

<div class="login-box">
    <div class="login-logo">
        <img src="{{ asset('images/school-logo.png') }}" alt="Logo Sekolah" width="120">
        <br>
        <b>SIPENA</b> Sekolah
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Silakan login untuk mengakses sistem</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <!-- Optional: Remember Me -->
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </div>
                </div>
            </form>

            <!-- Optional links -->
            <!-- <p class="mb-1 mt-2 text-center">
                <a href="#">Lupa password?</a>
            </p> -->

        </div>
    </div>
</div>

<!-- JS Files -->
<script src="{{ auto_asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ auto_asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ auto_asset('dist/js/adminlte.min.js') }}"></script>
</body>
</html>
