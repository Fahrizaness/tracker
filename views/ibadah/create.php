<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 pt-4">
          <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0"><i class="bi bi-journal-plus me-2"></i>Catat Ibadah</h2>
            <a href="/tumbuh1%/ibadah" class="btn btn-sm btn-outline-secondary">
              <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
          </div>
        </div>

        <div class="card-body">
          <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          <?php endif; ?>

          <?php if (isset($_SESSION['success'])): ?>
          <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          <?php endif; ?>

          <form action="/tumbuh1%/ibadah/store" method="POST" class="needs-validation" novalidate>
            <div class="mb-4">
              <label for="jenis_ibadah" class="form-label fw-bold">Sholat</label>
              <select name="jenis_ibadah" id="jenis_ibadah" class="form-select form-select-lg" required>
                <option value="" disabled selected>-- Sholat --</option>
                <option value="Subuh">Subuh</option>
                <option value="Dzuhur">Dzuhur</option>
                <option value="Ashar">Ashar</option>
                <option value="Maghrib">Maghrib</option>
                <option value="Isya">Isya</option>
                <option value="Sunah">Dhuha/Tahajud</option>
              </select>
              <div class="invalid-feedback">Silakan pilih jenis ibadah</div>
            </div>

            <div class="row mb-4">
              <div class="col-md-6 mb-3 mb-md-0">
                <label for="tanggal" class="form-label fw-bold">Tanggal</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                  <input type="date" name="tanggal" id="tanggal" class="form-control form-control-lg" required
                    value="<?= date('Y-m-d') ?>">
                </div>
              </div>
              <div class="col-md-6">
                <label for="waktu" class="form-label fw-bold">Waktu</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-clock"></i></span>
                  <input type="time" name="waktu" id="waktu" class="form-control form-control-lg" required
                    value="<?= date('H:i') ?>">
                </div>
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold">Status Ibadah</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="status1" value="selesai" checked
                  required>
                <label class="form-check-label" for="status1">
                  <i class="bi bi-check-circle-fill text-success me-1"></i> Sudah Dilaksanakan
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="status2" value="terlewat">
                <label class="form-check-label" for="status2">
                  <i class="bi bi-exclamation-circle-fill text-warning me-1"></i> Terlewat (Qadha)
                </label>
              </div>
            </div>
            <div class="mb-4">
              <label for="keterangan" class="form-label fw-bold">Keterangan <span
                  class="text-muted">(Opsional)</span></label>
              <textarea name="keterangan" id="keterangan" rows="3" class="form-control"
                placeholder="Catatan tambahan tentang ibadah ini..."></textarea>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
              <button type="reset" class="btn btn-outline-secondary btn-lg me-md-2">
                <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
              </button>
              <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-save me-2"></i>Simpan Catatan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Client-side form validation
(function() {
  'use strict'

  const forms = document.querySelectorAll('.needs-validation')

  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})()
</script>
<script>
document.querySelector('form').addEventListener('submit', function(e) {
  const statusSelected = document.querySelector('input[name="status"]:checked');
  if (!statusSelected) {
    e.preventDefault();
    alert('Pilih status ibadah!');
  }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>