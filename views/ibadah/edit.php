<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-pencil-square me-2"></i>Edit Catatan Ibadah</h2>
    <a href="/tumbuh1%/ibadah" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
  </div>

  <?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger alert-dismissible fade show">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body">
      <form action="/tumbuh1%/ibadah/update" method="POST">
        <input type="hidden" name="id" value="<?= $ibadah['id'] ?>">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <div class="mb-3">
          <label for="jenis_ibadah" class="form-label">Jenis Ibadah</label>
          <input type="text" class="form-control" id="jenis_ibadah" name="jenis_ibadah"
            value="<?= htmlspecialchars($ibadah['jenis_ibadah']) ?>" required>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal"
              value="<?= htmlspecialchars($ibadah['tanggal']) ?>" required>
          </div>
          <div class="col-md-6">
            <label for="waktu" class="form-label">Waktu</label>
            <input type="time" class="form-control" id="waktu" name="waktu"
              value="<?= htmlspecialchars($ibadah['waktu']) ?>" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Status</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="status-selesai" value="selesai"
              <?= $ibadah['status'] === 'selesai' ? 'checked' : '' ?>>
            <label class="form-check-label" for="status-selesai">
              Selesai Tepat Waktu
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" id="status-qadha" value="qadha"
              <?= $ibadah['status'] === 'qadha' ? 'checked' : '' ?>>
            <label class="form-check-label" for="status-qadha">
              Qadha
            </label>
          </div>
        </div>

        <div class="mb-3">
          <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
          <textarea class="form-control" id="keterangan" name="keterangan"
            rows="3"><?= htmlspecialchars($ibadah['keterangan']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
          <i class="bi bi-save me-1"></i> Simpan Perubahan
        </button>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>