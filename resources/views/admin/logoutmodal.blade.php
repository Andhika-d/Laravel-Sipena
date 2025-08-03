<head>
  <style>
@media print {
  body * {
    visibility: hidden;
  }

  #qrModal, #qrModal * {
    visibility: visible;
  }

  #qrModal {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }

  .modal-header, .modal-footer, .d-print-none {
    display: none !important;
  }
}
</style>
</head>
<!-- components/logout-modal.blade.php -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        Yakin ingin logout?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
      </div>
    </div>
  </div>
</div>

<!-- Modal QR Absen -->
<!-- Modal QR Absen -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 shadow-lg border-0">
      
      <div class="modal-header bg-primary text-white rounded-top-4 d-block text-center">
        <h5 class="modal-title fw-semibold">
          <i class="bi bi-qr-code me-2"></i>QR Absen Hari Ini
        </h5>
        <button type="button" class="btn-close btn-close-white position-absolute end-0 top-0 mt-3 me-3 d-print-none" data-bs-dismiss="modal"></button>
      </div>
      
      <div class="modal-body text-center">
        <div class="d-flex justify-content-center mb-4">
          <img src="{{ route('qr.scan.image') }}"
               alt="QR Absen"
               class="p-3 bg-white border rounded shadow-sm"
               style="max-width: 300px;">
        </div>

        <div class="d-print-none">
          <a href="{{ route('qr.scan.image') }}" download="qr-absen.svg" class="btn btn-outline-primary me-2">
            <i class="bi bi-download me-1"></i> Download QR
          </a>
          <button onclick="window.print()" class="btn btn-secondary">
            <i class="bi bi-printer me-1"></i> Cetak QR
          </button>
        </div>
      </div>

    </div>
  </div>
</div>
