<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="mb-1"><i class="bi bi-plus-circle me-2"></i>Tambah Catatan Mood</h2>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/tumbuh1%/mood">Mood Tracker</a></li>
          <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
        </ol>
      </nav>
    </div>
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
      <form action="/tumbuh1%/mood/store" method="POST" class="needs-validation" novalidate>
        <!-- Date Field -->
        <div class="mb-4">
          <label for="tanggal" class="form-label fw-bold">Tanggal</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
            <input type="date" class="form-control form-control-lg" id="tanggal" name="tanggal" required
              value="<?= date('Y-m-d'); ?>">
          </div>
        </div>

        <!-- Mood Selection -->
        <div class="mb-4">
          <label for="mood" class="form-label fw-bold">Mood Anda</label>
          <div class="row g-3 mood-selector">
            <?php 
            $moodOptions = [
              'senang' => ['emoji' => '😊', 'label' => 'Senang', 'color' => 'success'],
              'sedih' => ['emoji' => '😢', 'label' => 'Sedih', 'color' => 'primary'],
              'marah' => ['emoji' => '😠', 'label' => 'Marah', 'color' => 'danger'],
              'netral' => ['emoji' => '😐', 'label' => 'Netral', 'color' => 'secondary'],
              'lelah' => ['emoji' => '🥱', 'label' => 'Lelah', 'color' => 'warning'],
              'stres' => ['emoji' => '😫', 'label' => 'Stres', 'color' => 'dark'],
              'bersemangat' => ['emoji' => '🤩', 'label' => 'Bersemangat', 'color' => 'info']
            ];
            ?>
            <?php foreach ($moodOptions as $value => $option): ?>
            <div class="col-4 col-md-3">
              <input type="radio" class="btn-check" name="mood" id="mood-<?= $value ?>" value="<?= $value ?>" required>
              <label class="btn btn-outline-<?= $option['color'] ?> d-flex flex-column align-items-center py-3 w-100"
                for="mood-<?= $value ?>">
                <span class="emoji fs-3 mb-1"><?= $option['emoji'] ?></span>
                <span><?= $option['label'] ?></span>
              </label>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Energy Level -->
        <div class="mb-4">
          <label class="form-label fw-bold d-flex justify-content-between">
            <span>Tingkat Energi</span>
            <span id="energyValue" class="badge bg-primary">50%</span>
          </label>
          <input type="range" class="form-range" id="tingkat_energi" name="tingkat_energi" min="0" max="100" value="50"
            oninput="updateEnergyValue(this.value)">
          <div class="d-flex justify-content-between mt-1">
            <small class="text-muted">Lemah</small>
            <small class="text-muted">Normal</small>
            <small class="text-muted">Tinggi</small>
          </div>
        </div>

        <!-- Notes -->
        <div class="mb-4">
          <label for="catatan" class="form-label fw-bold">
            Catatan <small class="text-muted">(Apa yang mempengaruhi mood Anda?)</small>
          </label>
          <textarea class="form-control" id="catatan" name="catatan" rows="4"
            placeholder="Deskripsikan apa yang membuat Anda merasa seperti ini..."></textarea>
        </div>

        <!-- Form Actions -->
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          <a href="/tumbuh1%/mood" class="btn btn-outline-secondary btn-lg me-md-2">
            <i class="bi bi-arrow-left me-1"></i> Kembali
          </a>
          <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-save me-1"></i> Simpan Catatan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
.mood-selector .btn {
  transition: all 0.2s;
}

.mood-selector .btn:hover {
  transform: translateY(-3px);
}

.mood-selector .btn-check:checked+.btn {
  box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
  border-width: 2px;
}
</style>

<script>
// Update energy value display
function updateEnergyValue(val) {
  const energyValue = document.getElementById('energyValue');
  energyValue.textContent = val + '%';

  // Update badge color based on value
  if (val > 70) {
    energyValue.className = 'badge bg-success';
  } else if (val > 40) {
    energyValue.className = 'badge bg-primary';
  } else {
    energyValue.className = 'badge bg-danger';
  }
}

// Form validation
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

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>