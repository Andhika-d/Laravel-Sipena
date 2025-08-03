@extends('guru.layout')

@section('title', 'Mengambil Lokasi...')

@section('content')
<div class="d-flex flex-column justify-content-center align-items-center vh-100 text-center px-3">
    <div class="spinner-border text-primary mb-3" role="status"></div>
    <h5 class="mb-1">Mohon tunggu...</h5>
    <p class="text-muted">Kami sedang mengambil lokasi Anda untuk melakukan absen.</p>

    <form id="qrForm" method="POST" action="{{ route('qr.absen.handle') }}">
      @csrf
      <input type="hidden" name="latitude" id="latitude">
      <input type="hidden" name="longitude" id="longitude">
    </form>
</div>
@endsection

@section('scripts')
<script>
    let retryCount = 0;

function ambilLokasiDanKirim() {
    navigator.geolocation.getCurrentPosition(
        function (position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const accuracy = position.coords.accuracy;

            if (isNaN(lat) || isNaN(lng) || lat === 0 || lng === 0 || accuracy > 500) {
                if (retryCount < 2) {
                    retryCount++;
                    console.warn("Lokasi tidak valid, mencoba ulang... (" + retryCount + ")");
                    setTimeout(ambilLokasiDanKirim, 1000); // Ulang setelah 1 detik
                    return;
                } else {
                    alert("Gagal mendapatkan lokasi yang valid. Silakan coba ulangi scan.");
                    window.location.href = "{{ route('guru.absensi') }}";
                    return;
                }
            }

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            document.getElementById('qrForm').submit();
        },
        function (error) {
            alert("Gagal mendapatkan lokasi: " + error.message);
            window.location.href = "{{ route('guru.absensi') }}";
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
}

document.addEventListener("DOMContentLoaded", function () {
    if (navigator.geolocation) {
        ambilLokasiDanKirim();
    } else {
        alert("Browser tidak mendukung geolocation.");
        window.location.href = "{{ route('guru.absensi') }}";
    }
});

</script>
@endsection