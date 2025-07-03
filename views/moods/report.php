<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="mb-1"><i class="bi bi-graph-up me-2"></i>Analisis Mood</h2>
      <p class="text-muted mb-0">Statistik dan perkembangan mood Anda</p>
    </div>
    <div>
      <a href="/tumbuh1%/mood" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
      </a>
    </div>
  </div>

  <div class="row">
    <!-- Mood Distribution -->
    <div class="col-lg-6 mb-4">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-white">
          <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Distribusi Mood</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <canvas id="moodChart" height="250"></canvas>
            </div>
            <div class="col-md-6">
              <div class="table-responsive">
                <table class="table table-sm">
                  <thead class="table-light">
                    <tr>
                      <th>Mood</th>
                      <th class="text-end">%</th>
                      <th class="text-end">Energi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($reports as $report): 
                      $moodConfig = [
                        'senang' => 'success',
                        'sedih' => 'primary',
                        'marah' => 'danger',
                        'netral' => 'secondary',
                        'lelah' => 'warning',
                        'stres' => 'dark',
                        'bersemangat' => 'info'
                      ];
                    ?>
                    <tr>
                      <td>
                        <span
                          class="badge bg-<?= $moodConfig[$report['mood']] ?> bg-opacity-10 text-<?= $moodConfig[$report['mood']] ?>">
                          <?= ucfirst($report['mood']) ?>
                        </span>
                      </td>
                      <td class="text-end"><?= $report['persentase'] ?>%</td>
                      <td class="text-end"><?= round($report['rata_energi']) ?>%</td>
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

    <!-- Weekly Trend -->
    <div class="col-lg-6 mb-4">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-white">
          <h5 class="mb-0"><i class="bi bi-calendar-week me-2"></i>Trend Mingguan</h5>
        </div>
        <div class="card-body">
          <canvas id="weeklyTrendChart" height="250"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Detailed Weekly Data -->
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
      <h5 class="mb-0"><i class="bi bi-table me-2"></i>Data Detail Mingguan</h5>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th class="ps-4">Minggu</th>
              <th class="text-end">Jumlah Catatan</th>
              <th class="text-end">Rata-rata Energi</th>
              <th>Mood Dominan</th>
              <th>Detail</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($weeklyReport as $week): 
              $moods = explode(',', $week['mood_list']);
              $moodCounts = array_count_values($moods);
              arsort($moodCounts);
              $topMood = key($moodCounts);
            ?>
            <tr>
              <td class="ps-4">
                <div class="fw-bold"><?= date('d M', strtotime($week['tanggal_mulai'])) ?> -
                  <?= date('d M Y', strtotime($week['tanggal_selesai'])) ?></div>
                <small class="text-muted">Minggu ke-<?= date('W', strtotime($week['tanggal_mulai'])) ?></small>
              </td>
              <td class="text-end"><?= $week['total_catatan'] ?></td>
              <td class="text-end">
                <span
                  class="badge bg-<?= $week['rata_energi'] > 70 ? 'success' : ($week['rata_energi'] > 40 ? 'primary' : 'danger') ?> bg-opacity-10 text-<?= $week['rata_energi'] > 70 ? 'success' : ($week['rata_energi'] > 40 ? 'primary' : 'danger') ?>">
                  <?= round($week['rata_energi']) ?>%
                </span>
              </td>
              <td>
                <span class="badge bg-<?= $moodConfig[$topMood] ?> bg-opacity-10 text-<?= $moodConfig[$topMood] ?>">
                  <?= ucfirst($topMood) ?>
                </span>
                <small class="text-muted">(<?= $moodCounts[$topMood] ?>x)</small>
              </td>
              <td>
                <a href="/tumbuh1%/mood/weekly/<?= date('Y-m-d', strtotime($week['tanggal_mulai'])) ?>"
                  class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-zoom-in"></i> Detail
                </a>
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
// Mood Distribution Pie Chart
const moodCtx = document.getElementById('moodChart');
new Chart(moodCtx, {
  type: 'pie',
  data: {
    labels: <?= json_encode(array_map(function($r) { return ucfirst($r['mood']); }, $reports)) ?>,
    datasets: [{
      data: <?= json_encode(array_column($reports, 'persentase')) ?>,
      backgroundColor: [
        '#28a745', '#007bff', '#dc3545', '#6c757d', '#ffc107', '#343a40', '#17a2b8'
      ],
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'bottom',
      },
      tooltip: {
        callbacks: {
          label: function(context) {
            return `${context.label}: ${context.raw}% (${<?= json_encode(array_column($reports, 'jumlah')) ?>[context.dataIndex]} catatan)`;
          }
        }
      }
    }
  }
});

// Weekly Trend Chart
const weeklyCtx = document.getElementById('weeklyTrendChart');
new Chart(weeklyCtx, {
  type: 'line',
  data: {
    labels: <?= json_encode(array_map(function($w) { 
      return date('d M', strtotime($w['tanggal_mulai'])); 
    }, $weeklyReport)) ?>,
    datasets: [{
      label: 'Rata-rata Energi',
      data: <?= json_encode(array_column($weeklyReport, 'rata_energi')) ?>,
      borderColor: '#ffc107',
      backgroundColor: 'rgba(255, 193, 7, 0.1)',
      tension: 0.3,
      fill: true
    }]
  },
  options: {
    responsive: true,
    scales: {
      y: {
        beginAtZero: false,
        min: 0,
        max: 100,
        ticks: {
          callback: function(value) {
            return value + '%';
          }
        }
      }
    },
    plugins: {
      tooltip: {
        callbacks: {
          label: function(context) {
            return `Energi: ${context.raw}%`;
          },
          afterLabel: function(context) {
            const week = <?= json_encode($weeklyReport) ?>[context.dataIndex];
            const moods = week.mood_list.split(',');
            const moodCounts = {};
            moods.forEach(mood => {
              moodCounts[mood] = (moodCounts[mood] || 0) + 1;
            });
            const topMood = Object.keys(moodCounts).reduce((a, b) => moodCounts[a] > moodCounts[b] ? a : b);
            return `Mood dominan: ${topMood}\nTotal catatan: ${week.total_catatan}`;
          }
        }
      }
    }
  }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>