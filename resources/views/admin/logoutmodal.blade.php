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
<div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <div class="modal-header">
        <h5 class="modal-title w-100">QR Absen Hari Ini</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <iframe src="{{ route('qr.scan.image') }}" width="300" height="300" frameborder="0"></iframe>
        <a href="{{ route('qr.scan.image') }}" download="qr-absen.png" class="btn btn-primary">Download QR</a>
      </div>
    </div>
  </div>
</div>