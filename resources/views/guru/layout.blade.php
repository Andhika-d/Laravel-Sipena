<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dashboard Guru')</title>
  <link rel="icon" href="{{ asset('images/favicon-32x32.png') }}" type="image/png">
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
  <style type="">
    .info-button {
        display: inline-block;
        padding: 10px 20px; /* Sesuaikan dengan ukuran yang diinginkan */
        background-color: #27ae60; /* Warna latar belakang */
        color: #ffffff; /* Warna teks */
        font-size: 16px;
        text-decoration: none;
        border-radius: 5px; /* Untuk sudut melengkung */
        transition: background-color 0.3s ease; /* Efek transisi perubahan warna latar belakang */
    }

    .info-button:hover {
        background-color: #218c53; /* Warna latar belakang saat dihover */
    }

    body.light-mode {
            background-color: #fff;
            color: #000;
        }

    /* Dark Mode */
    body.dark-mode {
            background-color: #000000;
            color: #fff;
        }

    .typing-css {
        display: inline-block;
        overflow: hidden;
        white-space: nowrap;
        /* border-right: 2px solid #333; */
        font-family: monospace;
        animation: typing 4s steps(18, end) infinite;
        }

        @keyframes typing {
        0%   { max-width: 0; opacity: 1; }
        60%  { max-width: 18ch; opacity: 1; }  /* selesai ngetik */
        80%  { max-width: 18ch; opacity: 1; }  /* tahan sebentar */
        100% { max-width: 0; opacity: 0; }     /* langsung ilang */
        }

        @keyframes blink-caret {
        0%, 100% { border-color: transparent; }
        50%      { border-color: #333; }
        }

        .pulse-badge {
            animation: pulse 1.2s ease-in-out infinite;
            }

            @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            }

            #toggleButton {
                outline: none !important;
                box-shadow: none !important;
            }

            #toggleButton:focus,
            #toggleButton:active {
                outline: none !important;
                box-shadow: none !important;
                background-color: transparent !important;
            }

            #toggleButton:focus-visible {
                outline: none !important;
            }

            .dark-mode {
                background-color: #1e1e2f;
                color: #e0e0e0;
            }

            .light-mode {
                background-color: #ffffff;
                color: #212529;
            }

            .transition-icon {
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            transition: transform 0.4s ease, opacity 0.4s ease;
            }

            #sunIcon.show {
            transform: translateX(0);
            opacity: 1;
            }
            #sunIcon.hide {
            transform: translateX(-100%);
            opacity: 0;
            }

            #moonIcon.show {
            transform: translateX(0);
            opacity: 1;
            }
            #moonIcon.hide {
            transform: translateX(100%);
            opacity: 0;
            }

  </style>
  @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    @include('guru.sidebar')

    @yield('content')
  </div>
    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="logoutModalLabel">
            <i class="fas fa-exclamation-triangle mr-2"></i> Konfirmasi Logout
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        
        <div class="modal-body text-center">
            <p class="mb-0">Apakah kamu yakin ingin keluar dari akun ini?</p>
            <small class="text-muted">Aksi ini akan mengakhiri sesi kamu saat ini.</small>
        </div>
        
        <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Batal
            </button>
            <a href="{{ route('logout') }}" class="btn btn-danger">
            <i class="fas fa-sign-out-alt mr-1"></i> Logout
            </a>
        </div>
        </div>
    </div>
    </div>

  <!-- JS -->
  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('toggleButton');
    const toggleStatus = document.getElementById('toggleStatus');
    const sunIcon = document.getElementById('sunIcon');
    const moonIcon = document.getElementById('moonIcon');

    let isOn = localStorage.getItem('darkMode') === 'true';

    function saveModeStatus() {
        localStorage.setItem('darkMode', isOn.toString());
    }

    function toggleMode() {
        // Body class
        document.body.classList.toggle('dark-mode', isOn);
        document.body.classList.toggle('light-mode', !isOn);

        // Navbar (AdminLTE)
        const navbar = document.querySelector('.main-header');
        if (navbar) {
        navbar.classList.toggle('navbar-dark', isOn);
        navbar.classList.toggle('navbar-light', !isOn);
        navbar.classList.toggle('bg-dark', isOn);
        navbar.classList.toggle('bg-white', !isOn);
        }

        // Update ikon dengan animasi
        if (isOn) {
        // Mode dark → tampilkan moon
        sunIcon.classList.add('hide');
        sunIcon.classList.remove('show');
        moonIcon.classList.add('show');
        moonIcon.classList.remove('hide');
        toggleStatus.textContent = 'Dark';
        } else {
        // Mode light → tampilkan sun
        sunIcon.classList.add('show');
        sunIcon.classList.remove('hide');
        moonIcon.classList.add('hide');
        moonIcon.classList.remove('show');
        toggleStatus.textContent = 'Light';
        }

        // Warna tombol
        toggleButton.classList.toggle('btn-outline-dark', !isOn);
        toggleButton.classList.toggle('btn-outline-light', isOn);

        saveModeStatus();
    }

    toggleButton.addEventListener('click', function () {
        isOn = !isOn;
        toggleMode();
    });

    // Set awal saat page load
    toggleMode();
    });
   </script>
    @yield('scripts')
   @stack('scripts')
</body>
</html>