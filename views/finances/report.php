<?php 
require_once __DIR__ .'/../layouts/header.php'; ?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Laporan Keuangan</h2>
    <div>
      <a href="/tumbuh1%/finance" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
      <button onclick="window.print()" class="btn btn-outline-primary ml-2">
        <i class="fas fa-print"></i> Cetak
      </button>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="row mb-4">
    <?php 
        $currentMonth = $this->model->getCurrentMonthSummary($_SESSION['user_id']);
        ?>
    <div class="col-md-4 mb-3">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Pemasukan Bulan Ini</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                Rp <?= number_format($currentMonth['pemasukan'] ?? 0, 0, ',', '.') ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-money-bill-wave fa-2x text-success"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4 mb-3">
      <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                Pengeluaran Bulan Ini</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                Rp <?= number_format($currentMonth['pengeluaran'] ?? 0, 0, ',', '.') ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-shopping-cart fa-2x text-danger"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4 mb-3">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Saldo Bulan Ini</div>
              <div
                class="h5 mb-0 font-weight-bold <?= ($currentMonth['saldo'] ?? 0) >= 0 ? 'text-success' : 'text-danger' ?>">
                Rp <?= number_format($currentMonth['saldo'] ?? 0, 0, ',', '.') ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-wallet fa-2x text-primary"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Monthly Reports -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <h6 class="m-0 font-weight-bold text-primary">Laporan Bulanan</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" width="100%" cellspacing="0">
          <thead class="thead-light">
            <tr>
              <th>Bulan</th>
              <th class="text-right">Pemasukan</th>
              <th class="text-right">Pengeluaran</th>
              <th class="text-right">Saldo</th>
              <th class="text-right">Transaksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($monthlyReports as $report): 
                            $balance = $report['total_pemasukan'] - $report['total_pengeluaran'];
                        ?>
            <tr>
              <td><?= date('F Y', strtotime($report['bulan'] . '-01')) ?></td>
              <td class="text-right text-success">Rp <?= number_format($report['total_pemasukan'], 0, ',', '.') ?></td>
              <td class="text-right text-danger">Rp <?= number_format($report['total_pengeluaran'], 0, ',', '.') ?></td>
              <td class="text-right font-weight-bold <?= $balance >= 0 ? 'text-success' : 'text-danger' ?>">
                Rp <?= number_format($balance, 0, ',', '.') ?>
              </td>
              <td class="text-right"><?= $report['total_transaksi'] ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Category Spending -->
    <div class="col-lg-6 mb-4">
      <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">Pengeluaran per Kategori</h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
              <thead class="thead-light">
                <tr>
                  <th>Kategori</th>
                  <th class="text-right">Transaksi</th>
                  <th class="text-right">Total</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($categorySpending as $category): ?>
                <tr>
                  <td><?= ucfirst($category['kategori']) ?></td>
                  <td class="text-right"><?= $category['jumlah_transaksi'] ?></td>
                  <td class="text-right text-danger">Rp
                    <?= number_format($category['total_pengeluaran'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Yearly Summary -->
    <div class="col-lg-6 mb-4">
      <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">Ringkasan Tahunan</h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
              <thead class="thead-light">
                <tr>
                  <th>Tahun</th>
                  <th class="text-right">Pemasukan</th>
                  <th class="text-right">Pengeluaran</th>
                  <th class="text-right">Saldo</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($yearlySummary as $yearly): ?>
                <tr>
                  <td><?= $yearly['tahun'] ?></td>
                  <td class="text-right text-success">Rp <?= number_format($yearly['pemasukan'], 0, ',', '.') ?></td>
                  <td class="text-right text-danger">Rp <?= number_format($yearly['pengeluaran'], 0, ',', '.') ?></td>
                  <td class="text-right font-weight-bold <?= $yearly['saldo'] >= 0 ? 'text-success' : 'text-danger' ?>">
                    Rp <?= number_format($yearly['saldo'], 0, ',', '.') ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php 
require_once __DIR__ .'/../layouts/footer.php'; ?>

<style>
.card {
  border-radius: 0.35rem;
}

.card-header {
  background-color: #f8f9fc;
  border-bottom: 1px solid #e3e6f0;
}

.table {
  font-size: 0.9rem;
}

.table th {
  border-top: none;
  font-weight: 600;
  color: #4e73df;
}

.border-left-success {
  border-left: 0.25rem solid #1cc88a !important;
}

.border-left-danger {
  border-left: 0.25rem solid #e74a3b !important;
}

.border-left-primary {
  border-left: 0.25rem solid #4e73df !important;
}

.shadow {
  box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

@media print {

  .btn,
  .card-header {
    display: none;
  }

  .card {
    border: 1px solid #ddd;
    box-shadow: none;
  }
}
</style>