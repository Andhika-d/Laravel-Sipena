<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dashboard Guru')</title>
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
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    @include('guru.sidebar')

    @yield('content')
  </div>

  <!-- JS -->
  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
  <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Dapatkan elemen tombol dan status
            const toggleButton = document.getElementById('toggleButton');
            const toggleStatus = document.getElementById('toggleStatus');

            // Inisialisasi status tombol dari penyimpanan lokal
            let isOn = localStorage.getItem('darkMode') === 'true';

            // Fungsi untuk menyimpan status mode ke penyimpanan lokal
            function saveModeStatus() {
                localStorage.setItem('darkMode', isOn.toString());
            }

            function toggleMode() {
                document.body.classList.toggle('light-mode', !isOn);
                document.body.classList.toggle('dark-mode', isOn);

                // Sesuaikan navbar (jika ada)
                const navbar = document.querySelector('.navbar');
                if (navbar) {
                    navbar.classList.toggle('navbar-light', !isOn);
                    navbar.classList.toggle('navbar-dark', isOn);
                }

                // Simpan status mode ke penyimpanan lokal
                saveModeStatus();
            }

            // Tambahkan event listener untuk tombol
            toggleButton.addEventListener('click', function () {
                // Ubah status dan tampilan teks
                isOn = !isOn;
                toggleStatus.textContent = isOn ? 'Dark' : 'Light';
                // Ubah warna latar belakang tombol
                toggleButton.classList.toggle('btn-primary');
                toggleButton.classList.toggle('btn-secondary');
                // Ubah mode
                toggleMode();
            });

            // Pemanggilan awal untuk mengatur mode berdasarkan status awal
            toggleMode();
        });
    </script>
</body>
</html>