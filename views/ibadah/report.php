<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="mb-1"><i class="bi bi-bar-chart-line me-2"></i>Laporan Ibadah Bulanan</h2>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/tumbuh1%/ibadah">Ibadah</a></li>
          <li class="breadcrumb-item active" aria-current="page">Laporan</li>
        </ol>
      </nav>
    </div>
    <div>
      <a href="/tumbuh1%/ibadah" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
      </a>
    </div>
  </div>

  <?php if (isset($reports) && count($reports) > 0): ?>
  <div class="card shadow-sm border-0">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th class="ps-4">Bulan/Tahun</th>
              <th>Total Ibadah</th>
              <th>Tepat Waktu</th>
              <th>Qadha</th>
              <th>Detail</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reports as $row): 
              $monthName = date('F', mktime(0, 0, 0, $row['bulan'], 10));
            ?>
            <tr>
              <td class="ps-4">
                <div class="fw-bold"><?= $monthName ?></div>
                <small class="text-muted"><?= htmlspecialchars($row['tahun']) ?></small>
              </td>
              <td>
                <span class="badge bg-primary bg-opacity-10 text-primary">
                  <?= htmlspecialchars($row['total_ibadah']) ?> ibadah
                </span>
              </td>
              <td>
                <span class="badge bg-success bg-opacity-10 text-success">
                  <?= htmlspecialchars($row['tepat_waktu']) ?>
                  (<?= round(($row['tepat_waktu']/$row['total_ibadah'])*100) ?>%)
                </span>
              </td>
              <td>
                <span class="badge bg-warning bg-opacity-10 text-warning">
                  <?= htmlspecialchars($row['qadha']) ?> (<?= round(($row['qadha']/$row['total_ibadah'])*100) ?>%)
                </span>
              </td>
              <td>
                <a href="/tumbuh1%/ibadah/monthly/<?= $row['tahun'] ?>/<?= $row['bulan'] ?>"
                  class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-eye"></i> Lihat
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Grafik Statistik -->
  <div class="card shadow-sm mt-4">
    <div class="card-header bg-white">
      <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Statistik Ibadah</h5>
    </div>
    <div class="card-body">
      <canvas id="ibadahChart" height="200"></canvas>
    </div>
  </div>
  <?php else: ?>
  <div class="card shadow-sm">
    <div class="card-body text-center py-5">
      <i class="bi bi-journal-x fs-1 text-muted"></i>
      <h5 class="mt-3 text-muted">Belum ada data ibadah</h5>
      <p class="text-muted">Mulai catat ibadah Anda untuk melihat laporan</p>
      <a href="/tumbuh1%/ibadah/create" class="btn btn-primary mt-2">
        <i class="bi bi-plus-circle me-1"></i> Tambah Catatan
      </a>
    </div>
  </div>
  <?php endif; ?>
</div>

<?php if (isset($reports) && count($reports) > 0): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Data untuk chart
const ctx = document.getElementById('ibadahChart');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: <?= json_encode(array_map(function($report) { 
        return date('M Y', mktime(0, 0, 0, $report['bulan'], 1, $report['tahun'])); 
      }, $reports)) ?>,
    datasets: [{
        label: 'Tepat Waktu',
        data: <?= json_encode(array_column($reports, 'tepat_waktu')) ?>,
        backgroundColor: '#28a745',
      },
      {
        label: 'Qadha',
        data: <?= json_encode(array_column($reports, 'qadha')) ?>,
        backgroundColor: '#ffc107',
      }
    ]
  },
  options: {
    responsive: true,
    scales: {
      x: {
        stacked: true,
      },
      y: {
        stacked: true,
        beginAtZero: true
      }
    }
  }
});
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>