<?php 
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="mb-1"><i class="bi bi-wallet2 me-2"></i>Manajemen Keuangan</h2>
      <p class="text-muted mb-0">Pantau pemasukan dan pengeluaran Anda</p>
    </div>
    <div>
      <a href="/tumbuh1%/finance/create" class="btn btn-primary me-2">
        <i class="bi bi-plus-circle me-1"></i> Transaksi Baru
      </a>
      <a href="/tumbuh1%/finance/report" class="btn btn-outline-info">
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

  <!-- Statistik Keuangan -->
  <div class="row mb-4 g-3">
    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-muted mb-2">Total Pemasukan</h6>
              <h3 class="mb-0 text-success">Rp <?= number_format($balance['total_pemasukan'], 0, ',', '.'); ?></h3>
            </div>
            <div class="bg-success bg-opacity-10 p-3 rounded">
              <i class="bi bi-arrow-down-circle text-success fs-4"></i>
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
              <h6 class="text-muted mb-2">Total Pengeluaran</h6>
              <h3 class="mb-0 text-danger">Rp <?= number_format($balance['total_pengeluaran'], 0, ',', '.'); ?></h3>
            </div>
            <div class="bg-danger bg-opacity-10 p-3 rounded">
              <i class="bi bi-arrow-up-circle text-danger fs-4"></i>
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
              <h6 class="text-muted mb-2">Saldo Saat Ini</h6>
              <h3 class="mb-0 <?= $balance['saldo'] >= 0 ? 'text-success' : 'text-danger'; ?>">
                Rp <?= number_format($balance['saldo'], 0, ',', '.'); ?>
              </h3>
            </div>
            <div class="bg-primary bg-opacity-10 p-3 rounded">
              <i class="bi bi-wallet2 text-primary fs-4"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Grafik Ringkasan -->
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-white border-0 pt-3 pb-2">
      <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Ringkasan Bulanan</h5>
    </div>
    <div class="card-body">
      <canvas id="financeChart" height="200"></canvas>
    </div>
  </div>

  <!-- Transaksi Terakhir -->
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-white border-0 pt-3 pb-2">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Transaksi Terakhir</h5>
        <a href="/tumbuh1%/finance" class="btn btn-sm btn-outline-primary">
          Lihat Semua <i class="bi bi-arrow-right"></i>
        </a>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th class="ps-4">Tanggal</th>
              <th>Jenis</th>
              <th>Kategori</th>
              <th class="text-end">Jumlah</th>
              <th>Deskripsi</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($transactions)): ?>
            <?php foreach ($transactions as $transaction): ?>
            <tr>
              <td class="ps-4">
                <div class="fw-bold"><?= date('d M Y', strtotime($transaction['tanggal'])) ?></div>
                <small class="text-muted"><?= date('H:i', strtotime($transaction['created_at'])) ?></small>
              </td>
              <td>
                <span
                  class="badge bg-<?= $transaction['jenis'] == 'pemasukan' ? 'success' : 'danger' ?> bg-opacity-10 text-<?= $transaction['jenis'] == 'pemasukan' ? 'success' : 'danger' ?>">
                  <?= ucfirst($transaction['jenis']) ?>
                </span>
              </td>
              <td><?= ucfirst($transaction['kategori']) ?></td>
              <td class="text-end fw-bold <?= $transaction['jenis'] == 'pemasukan' ? 'text-success' : 'text-danger' ?>">
                <?= $transaction['jenis'] == 'pemasukan' ? '+' : '-' ?>
                Rp <?= number_format($transaction['jumlah'], 0, ',', '.') ?>
              </td>
              <td>
                <?php if (!empty($transaction['deskripsi'])): ?>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="popover"
                  title="Deskripsi" data-bs-content="<?= htmlspecialchars($transaction['deskripsi']) ?>">
                  <i class="bi bi-chat-square-text"></i>
                </button>
                <?php else: ?>
                <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="/tumbuh1%/finance/edit/<?= $transaction['id'] ?>" class="btn btn-sm btn-outline-primary me-1">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="/tumbuh1%/finance/delete/<?= $transaction['id'] ?>" method="POST"
                  class="d-inline delete-form">
                  <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
              <td colspan="6" class="text-center py-4">
                <i class="bi bi-journal-x fs-1 text-muted"></i>
                <h5 class="text-muted mt-3">Belum ada transaksi</h5>
                <p class="text-muted">Mulai catat transaksi keuangan Anda</p>
                <a href="/tumbuh1%/finance/create" class="btn btn-primary">
                  <i class="bi bi-plus-circle me-1"></i> Tambah Transaksi
                </a>
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Ringkasan Bulanan -->
  <div class="card shadow-sm">
    <div class="card-header bg-white border-0 pt-3 pb-2">
      <h5 class="mb-0"><i class="bi bi-calendar-month me-2"></i>Riwayat Bulanan</h5>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th class="ps-4">Bulan</th>
              <th class="text-end">Pemasukan</th>
              <th class="text-end">Pengeluaran</th>
              <th class="text-end">Saldo</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($monthlySummary as $summary): ?>
            <tr>
              <td class="ps-4 fw-bold"><?= date('F Y', strtotime($summary['bulan'] . '-01')) ?></td>
              <td class="text-end text-success">Rp <?= number_format($summary['pemasukan'], 0, ',', '.') ?></td>
              <td class="text-end text-danger">Rp <?= number_format($summary['pengeluaran'], 0, ',', '.') ?></td>
              <td class="text-end fw-bold <?= $summary['saldo'] >= 0 ? 'text-success' : 'text-danger' ?>">
                Rp <?= number_format($summary['saldo'], 0, ',', '.') ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Finance Chart
const financeCtx = document.getElementById('financeChart');
new Chart(financeCtx, {
  type: 'bar',
  data: {
    labels: <?= json_encode(array_map(function($m) { 
      return date('M Y', strtotime($m['bulan'] . '-01')); 
    }, $monthlySummary)) ?>,
    datasets: [{
        label: 'Pemasukan',
        data: <?= json_encode(array_column($monthlySummary, 'pemasukan')) ?>,
        backgroundColor: '#28a745',
        borderColor: '#28a745',
        borderWidth: 1
      },
      {
        label: 'Pengeluaran',
        data: <?= json_encode(array_column($monthlySummary, 'pengeluaran')) ?>,
        backgroundColor: '#dc3545',
        borderColor: '#dc3545',
        borderWidth: 1
      }
    ]
  },
  options: {
    responsive: true,
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          callback: function(value) {
            return 'Rp ' + value.toLocaleString('id-ID');
          }
        }
      }
    },
    plugins: {
      tooltip: {
        callbacks: {
          label: function(context) {
            let label = context.dataset.label || '';
            if (label) {
              label += ': ';
            }
            label += 'Rp ' + context.raw.toLocaleString('id-ID');
            return label;
          }
        }
      }
    }
  }
});

// Confirm before delete
document.querySelectorAll('.delete-form').forEach(form => {
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    if (confirm('Apakah Anda yakin ingin menghapus transaksi ini?')) {
      this.submit();
    }
  });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>