<?php 
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="mb-1"><i class="bi bi-praying-hands me-2"></i>Ibadah Tracker</h2>
      <p class="text-muted mb-0">Catatan ibadah harian Anda</p>
    </div>
    <div class="d-flex">
      <a href="/tumbuh1%/ibadah/create" class="btn btn-primary me-2">
        <i class="bi bi-plus-circle me-1"></i> Tambah Catatan
      </a>
      <a href="/tumbuh1%/ibadah/report" class="btn btn-outline-info">
        <i class="bi bi-graph-up me-1"></i> Laporan
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

  <!-- Stats Cards -->
  <div class="row mb-4 g-3">
    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-muted mb-2">Total Ibadah</h6>
              <h3 class="mb-0"><?= count($ibadahRecords) ?></h3>
            </div>
            <div class="bg-primary bg-opacity-10 p-3 rounded">
              <i class="bi bi-calendar-check text-primary fs-4"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-muted mb-2">Streak</h6>
              <h3 class="mb-0"><?= $currentStreak ?> hari</h3>
            </div>
            <div class="bg-success bg-opacity-10 p-3 rounded">
              <i class="bi bi-fire text-success fs-4"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-muted mb-2">Tingkat Konsistensi</h6>
              <h3 class="mb-0">
                <?php 
            // Hitung jumlah ibadah yang dilakukan tepat waktu
            $tepatWaktu = array_reduce($ibadahRecords, function($total, $ibadah) {
              return $total + ($ibadah['status'] === 'selesai' ? 1 : 0);
            }, 0);
            
            // Hitung persentase konsistensi
            $totalIbadah = count($ibadahRecords);
            $persentase = $totalIbadah > 0 ? round(($tepatWaktu / $totalIbadah) * 100) : 0;
            
            echo $persentase;
            ?>%
              </h3>
            </div>
            <div class="bg-info bg-opacity-10 p-3 rounded">
              <i class="bi bi-check-all text-info fs-4"></i>
            </div>
          </div>
          <small class="text-muted">
            <?= $tepatWaktu ?> dari <?= $totalIbadah ?> ibadah tepat waktu
          </small>
        </div>
      </div>
    </div>

    <div class="card shadow-sm border-0">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th class="ps-4">Tanggal</th>
                <th>Jenis Ibadah</th>
                <th>Waktu</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($ibadahRecords)): ?>
              <?php foreach ($ibadahRecords as $record): ?>
              <tr>
                <td class="ps-4">
                  <div class="fw-bold"><?= date('d M Y', strtotime($record['tanggal'])) ?></div>
                  <small class="text-muted"><?= date('l', strtotime($record['tanggal'])) ?></small>
                </td>
                <td>
                  <span class="badge bg-primary bg-opacity-10 text-primary">
                    <?= htmlspecialchars($record['jenis_ibadah']) ?>
                  </span>
                </td>
                <td><?= htmlspecialchars($record['waktu']) ?></td>
                <td>
                  <?php if ($record['status'] === 'selesai'): ?>
                  <span class="badge bg-success bg-opacity-10 text-success">
                    <i class="bi bi-check-circle me-1"></i> Tepat Waktu
                  </span>
                  <?php else: ?>
                  <span class="badge bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-clock-history me-1"></i> Qadha
                  </span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if (!empty($record['keterangan'])): ?>
                  <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="popover"
                    title="Keterangan" data-bs-content="<?= htmlspecialchars($record['keterangan']) ?>">
                    <i class="bi bi-chat-square-text"></i>
                  </button>
                  <?php else: ?>
                  <span class="text-muted">-</span>
                  <?php endif; ?>
                </td>
                <td>
                  <div class="d-flex">
                    <!-- Ganti link edit menjadi menyertakan ID -->
                    <!-- Pastikan link mengirim parameter id -->
                    <a href="/tumbuh1%/ibadah/edit?id=<?= $record['id'] ?>" class="btn btn-sm btn-outline-primary me-2">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <form action="/tumbuh1%/ibadah/delete" method="POST" class="delete-form">
                      <input type="hidden" name="id" value="<?= $record['id'] ?>">
                      <input type="hidden" name="_method" value="DELETE">
                      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                      <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php else: ?>
              <tr>
                <td colspan="6" class="text-center py-4">
                  <i class="bi bi-journal-x fs-1 text-muted"></i>
                  <h5 class="text-muted mt-3">Belum ada catatan ibadah</h5>
                  <p class="text-muted">Mulai tambahkan catatan ibadah Anda</p>
                  <a href="/tumbuh1%/ibadah/create" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Catatan
                  </a>
                </td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
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
      if (confirm('Apakah Anda yakin ingin menghapus catatan ibadah ini?')) {
        this.submit()
      }
    })
  })
  </script>

  <?php 
require_once __DIR__ . '/../layouts/footer.php';
?>