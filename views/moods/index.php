<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="mb-1"><i class="bi bi-emoji-smile me-2"></i>Mood Tracker</h2>
      <p class="text-muted mb-0">Pantau perkembangan mood dan energi harian Anda</p>
    </div>
    <div>
      <a href="/tumbuh1%/mood/create" class="btn btn-primary me-2">
        <i class="bi bi-plus-circle me-1"></i> Catat Mood
      </a>
      <a href="/tumbuh1%/mood/report" class="btn btn-outline-info">
        <i class="bi bi-graph-up me-1"></i> Analisis
      </a>
    </div>
  </div>

  <?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle-fill me-2"></i>
    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger alert-dismissible fade show">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php endif; ?>

  <?php if (!empty($moodRecords)): ?>
  <!-- Mood Statistics Cards -->
  <div class="row mb-4">
    <?php 
    $recentMoodStats = $this->model->getRecentMoodAnalysis($_SESSION['user_id']);
    $mostFrequentMood = !empty($recentMoodStats) ? $recentMoodStats[0]['mood'] : '-';
    $avgEnergy = !empty($recentMoodStats) ? round(array_reduce($recentMoodStats, function($carry, $item) {
      return $carry + $item['avg_energy'];
    }, 0) / count($recentMoodStats)) : 0;
    ?>
    <div class="col-md-4 mb-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center py-4">
          <div class="text-primary mb-3">
            <i class="bi bi-emoji-laughing fs-1"></i>
          </div>
          <h5 class="card-title">Mood Terakhir</h5>
          <p class="card-text h3 fw-bold text-capitalize">
            <?= !empty($moodRecords) ? $moodRecords[0]['mood'] : '-' ?>
          </p>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center py-4">
          <div class="text-success mb-3">
            <i class="bi bi-lightning-charge fs-1"></i>
          </div>
          <h5 class="card-title">Energi Hari Ini</h5>
          <p class="card-text h3 fw-bold">
            <?= !empty($moodRecords) ? $moodRecords[0]['tingkat_energi'] . '%' : '-' ?>
          </p>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body text-center py-4">
          <div class="text-info mb-3">
            <i class="bi bi-calendar-check fs-1"></i>
          </div>
          <h5 class="card-title">Total Pencatatan</h5>
          <p class="card-text h3 fw-bold">
            <?= count($moodRecords) ?> hari
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Mood Records Table -->
  <div class="card shadow-sm border-0">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th class="ps-4">Tanggal</th>
              <th>Mood</th>
              <th>Tingkat Energi</th>
              <th>Catatan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($moodRecords as $record): ?>
            <tr>
              <td class="ps-4">
                <div class="fw-bold"><?= date('d M Y', strtotime($record['tanggal'])) ?></div>
                <small class="text-muted"><?= date('l', strtotime($record['tanggal'])) ?></small>
              </td>
              <td>
                <?php 
                $moodConfig = [
                  'senang' => ['icon' => 'emoji-laughing', 'color' => 'success'],
                  'sedih' => ['icon' => 'emoji-frown', 'color' => 'primary'],
                  'marah' => ['icon' => 'emoji-angry', 'color' => 'danger'],
                  'netral' => ['icon' => 'emoji-neutral', 'color' => 'secondary'],
                  'lelah' => ['icon' => 'emoji-dizzy', 'color' => 'warning'],
                  'bersemangat' => ['icon' => 'emoji-heart-eyes', 'color' => 'info']
                ];
                $mood = $record['mood'];
                ?>
                <span
                  class="badge bg-<?= $moodConfig[$mood]['color'] ?> bg-opacity-10 text-<?= $moodConfig[$mood]['color'] ?> p-2">
                  <i class="bi bi-<?= $moodConfig[$mood]['icon'] ?> me-1"></i>
                  <?= ucfirst($mood); ?>
                </span>
              </td>
              <td>
                <div class="d-flex align-items-center">
                  <div class="progress flex-grow-1" style="height: 20px;">
                    <div class="progress-bar 
                                  <?= $record['tingkat_energi'] > 70 ? 'bg-success' : 
                                     ($record['tingkat_energi'] > 40 ? 'bg-warning' : 'bg-danger') ?>"
                      role="progressbar" style="width: <?= $record['tingkat_energi']; ?>%"
                      aria-valuenow="<?= $record['tingkat_energi']; ?>" aria-valuemin="0" aria-valuemax="100">
                      <?php if ($record['tingkat_energi'] > 15): ?>
                      <?= $record['tingkat_energi']; ?>%
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </td>
              <td>
                <?php if (!empty($record['catatan'])): ?>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="popover"
                  title="Catatan Mood" data-bs-content="<?= htmlspecialchars($record['catatan']) ?>">
                  <i class="bi bi-chat-square-text"></i> Lihat
                </button>
                <?php else: ?>
                <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
              <td>
                <div class="d-flex">
                  <a href="/tumbuh1%/mood/edit/<?= $record['id'] ?>" class="btn btn-sm btn-outline-primary me-2">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form action="/tumbuh1%/mood/delete/<?= $record['id'] ?>" method="POST" class="delete-form">
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php else: ?>
  <div class="card shadow-sm">
    <div class="card-body text-center py-5">
      <i class="bi bi-emoji-neutral fs-1 text-muted"></i>
      <h5 class="mt-3 text-muted">Belum ada data mood</h5>
      <p class="text-muted">Mulai catat mood harian Anda untuk melihat perkembangan</p>
      <a href="/tumbuh1%/mood/create" class="btn btn-primary mt-2">
        <i class="bi bi-plus-circle me-1"></i> Tambah Catatan
      </a>
    </div>
  </div>
  <?php endif; ?>
</div>

<script>
// Enable popovers
const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
const popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl)
})

// Confirm before delete
document.querySelectorAll('.delete-form').forEach(form => {
  form.addEventListener('submit', function(e) {
    e.preventDefault()
    if (confirm('Apakah Anda yakin ingin menghapus catatan mood ini?')) {
      this.submit()
    }
  })
})
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>