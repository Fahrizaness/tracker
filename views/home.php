<?php require_once 'layouts/header.php'; ?>

<div class="container py-4 dashboard-container">
  <!-- Greeting Card -->
  <div class="card greeting-card mb-4">
    <div class="card-body p-4">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h2 class="greeting-title">Selamat <?= getTimeBasedGreeting() ?>, <?= $_SESSION['username'] ?? 'Pengguna' ?>!
            👋</h2>
          <p class="greeting-text mb-0">"<?= getRandomQuote() ?>"</p>
        </div>
        <div class="col-md-4 text-end d-none d-md-block">
          <img src="/tumbuh1%/assets/images/illustrations/welcome.svg" alt="Welcome" class="greeting-img" height="120">
        </div>
      </div>
    </div>
  </div>

  <!-- Stats Cards - Modern Design -->
  <div class="row mb-4 g-3">
    <div class="col-md-4">
      <div class="card stat-card stat-card-streak">
        <div class="card-body p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <div class="stat-icon-wrapper bg-primary-light">
                <i class="bi bi-fire text-primary"></i>
              </div>
              <div class="ms-3">
                <h6 class="text-muted mb-1">Ibadah Streak</h6>
                <h4 class="mb-0"><?= $ibadahStreak; ?> hari</h4>
              </div>
            </div>
            <div class="streak-fire">
              <i class="bi bi-fire text-danger"></i>
            </div>
          </div>
          <div class="progress mt-3" style="height: 4px;">
            <div class="progress-bar bg-primary" role="progressbar" style="width: <?= min($ibadahStreak, 100) ?>%"
              aria-valuenow="<?= $ibadahStreak ?>" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card stat-card stat-card-mood">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="stat-icon-wrapper bg-info-light">
              <?php $moodIcon = getMoodIcon(!empty($moodAnalysis) ? $moodAnalysis[0]['mood'] : ''); ?>
              <i class="bi <?= $moodIcon ?> text-info"></i>
            </div>
            <div class="ms-3">
              <h6 class="text-muted mb-1">Mood Terakhir</h6>
              <h4 class="mb-0 text-capitalize"><?= !empty($moodAnalysis) ? $moodAnalysis[0]['mood'] : '-' ?></h4>
            </div>
          </div>
          <div class="mood-emoji mt-2">
            <?= getMoodEmoji(!empty($moodAnalysis) ? $moodAnalysis[0]['mood'] : '') ?>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card stat-card stat-card-balance">
        <div class="card-body p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <div class="stat-icon-wrapper bg-success-light">
                <i class="bi bi-wallet2 text-success"></i>
              </div>
              <div class="ms-3">
                <h6 class="text-muted mb-1">Saldo</h6>
                <h4 class="mb-0">Rp <?= number_format($financeSummary['saldo'] ?? 0, 0, ',', '.') ?></h4>
              </div>
            </div>
            <div class="trend-indicator <?= ($financeSummary['trend'] ?? 0) >= 0 ? 'text-success' : 'text-danger' ?>">
              <i class="bi bi-<?= ($financeSummary['trend'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' ?>"></i>
              <small><?= abs($financeSummary['trend'] ?? 0) ?>%</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="row">
    <!-- Recent Activities -->
    <div class="col-lg-8">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Aktivitas Terakhir</h5>
          <a href="/tumbuh1%/ibadah" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
          <?php if (!empty($recentIbadah)): ?>
          <div class="list-group list-group-flush">
            <?php foreach ($recentIbadah as $ibadah): ?>
            <div class="list-group-item border-0 py-3 px-4">
              <div class="d-flex align-items-center">
                <div class="activity-icon-wrapper bg-<?= getIbadahColor($ibadah['jenis_ibadah']) ?>-light me-3">
                  <i
                    class="bi bi-<?= getIbadahIcon($ibadah['jenis_ibadah']) ?> text-<?= getIbadahColor($ibadah['jenis_ibadah']) ?>"></i>
                </div>
                <div class="flex-grow-1">
                  <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><?= ucfirst($ibadah['jenis_ibadah']) ?></h6>
                    <span
                      class="badge rounded-pill bg-<?= $ibadah['status'] === 'selesai' ? 'success' : 'warning' ?>-light text-<?= $ibadah['status'] === 'selesai' ? 'success' : 'warning' ?>">
                      <?= ucfirst($ibadah['status']) ?>
                    </span>
                  </div>
                  <small class="text-muted">
                    <i class="bi bi-calendar me-1"></i> <?= date('d M Y', strtotime($ibadah['tanggal'])) ?>
                    <span class="mx-2">•</span>
                    <i class="bi bi-clock me-1"></i> <?= $ibadah['waktu'] ?>
                  </small>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php else: ?>
          <div class="empty-state p-5 text-center">
            <div class="empty-state-icon mb-3">
              <i class="bi bi-journal-x fs-1 text-muted"></i>
            </div>
            <h5 class="empty-state-title">Belum ada aktivitas</h5>
            <p class="empty-state-text text-muted">Mulai catat ibadah pertamamu hari ini</p>
            <a href="/tumbuh1%/ibadah/create" class="btn btn-primary mt-3">
              <i class="bi bi-plus"></i> Tambah Aktivitas
            </a>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
      <!-- Mood Chart -->
      <?php if (!empty($moodAnalysis)): ?>
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">Mood 7 Hari Terakhir</h5>
        </div>
        <div class="card-body">
          <canvas id="moodChart" height="200"></canvas>
          <div class="text-center mt-3">
            <small class="text-muted">
              <i class="bi bi-info-circle"></i> Grafik menunjukkan distribusi moodmu
            </small>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <!-- Quick Actions -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">Aksi Cepat</h5>
        </div>
        <div class="card-body">
          <div class="d-grid gap-3">
            <a href="/tumbuh1%/ibadah/create" class="btn btn-action btn-ibadah">
              <div class="d-flex align-items-center">
                <div class="action-icon-wrapper bg-primary-light me-3">
                  <i class="bi bi-pray text-primary"></i>
                </div>
                <div class="text-start">
                  <h6 class="mb-0">Catat Ibadah</h6>
                  <small class="text-muted">Sholat, baca Quran, dll</small>
                </div>
              </div>
              <i class="bi bi-chevron-right"></i>
            </a>

            <a href="/tumbuh1%/mood/create" class="btn btn-action btn-mood">
              <div class="d-flex align-items-center">
                <div class="action-icon-wrapper bg-info-light me-3">
                  <i class="bi bi-emoji-smile text-info"></i>
                </div>
                <div class="text-start">
                  <h6 class="mb-0">Catat Mood</h6>
                  <small class="text-muted">Bagaimana perasaanmu?</small>
                </div>
              </div>
              <i class="bi bi-chevron-right"></i>
            </a>

            <a href="/tumbuh1%/finance/create" class="btn btn-action btn-finance">
              <div class="d-flex align-items-center">
                <div class="action-icon-wrapper bg-success-light me-3">
                  <i class="bi bi-cash text-success"></i>
                </div>
                <div class="text-start">
                  <h6 class="mb-0">Catat Keuangan</h6>
                  <small class="text-muted">Pemasukan/pengeluaran</small>
                </div>
              </div>
              <i class="bi bi-chevron-right"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Motivational Tip -->
      <div class="card tip-card">
        <div class="card-body">
          <div class="d-flex align-items-start">
            <div class="tip-icon me-3">
              <i class="bi bi-lightbulb"></i>
            </div>
            <div>
              <h6 class="tip-title">Tip Hari Ini</h6>
              <p class="tip-text mb-0">"Luangkan 5 menit untuk merenungkan pencapaian hari ini. Refleksi kecil dapat
                membawa pertumbuhan besar."</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if (!empty($moodAnalysis)): ?>
// Mood Chart with better styling
const moodCtx = document.getElementById('moodChart');
new Chart(moodCtx, {
  type: 'doughnut',
  data: {
    labels: <?= json_encode(array_map(function($m) { return ucfirst($m['mood']); }, $moodAnalysis)) ?>,
    datasets: [{
      data: <?= json_encode(array_column($moodAnalysis, 'count')) ?>,
      backgroundColor: [
        '#2ecc71', // Happy
        '#3498db', // Normal
        '#e74c3c', // Sad
        '#95a5a6', // Tired
        '#f39c12' // Angry
      ],
      borderWidth: 0,
      hoverOffset: 10
    }]
  },
  options: {
    responsive: true,
    cutout: '70%',
    plugins: {
      legend: {
        position: 'bottom',
        labels: {
          padding: 20,
          usePointStyle: true,
          pointStyle: 'circle'
        }
      },
      tooltip: {
        callbacks: {
          label: function(context) {
            const label = context.label || '';
            const value = context.raw || 0;
            const total = context.dataset.data.reduce((a, b) => a + b, 0);
            const percentage = Math.round((value / total) * 100);
            return `${label}: ${value} (${percentage}%)`;
          }
        }
      }
    },
    animation: {
      animateScale: true,
      animateRotate: true
    }
  }
});
<?php endif; ?>
</script>

<style>
/* Base Styles */
.dashboard-container {
  max-width: 1200px;
}

/* Greeting Card */
.greeting-card {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  color: white;
  border-radius: 12px;
  overflow: hidden;
}

.greeting-title {
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.greeting-text {
  font-style: italic;
  opacity: 0.9;
  font-size: 0.95rem;
}

.greeting-img {
  opacity: 0.9;
}

/* Stat Cards */
.stat-card {
  border: none;
  border-radius: 12px;
  transition: all 0.3s ease;
  overflow: hidden;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.stat-icon-wrapper {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.bg-primary-light {
  background-color: rgba(13, 110, 253, 0.1);
}

.bg-info-light {
  background-color: rgba(13, 202, 240, 0.1);
}

.bg-success-light {
  background-color: rgba(25, 135, 84, 0.1);
}

.streak-fire {
  font-size: 1.5rem;
  animation: pulse 2s infinite;
}

.mood-emoji {
  font-size: 1.8rem;
  text-align: right;
}

.trend-indicator {
  font-size: 0.9rem;
  font-weight: 500;
}

/* Activities */
.activity-icon-wrapper {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Quick Actions */
.btn-action {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem;
  border-radius: 10px;
  border: 1px solid #f0f0f0;
  background: white;
  text-align: left;
  transition: all 0.3s ease;
}

.btn-action:hover {
  background: #f8f9fa;
  transform: translateX(5px);
}

.action-icon-wrapper {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Tip Card */
.tip-card {
  border: none;
  background-color: #f8f9fa;
  border-radius: 12px;
}

.tip-icon {
  font-size: 1.5rem;
  color: #ffc107;
}

.tip-title {
  font-weight: 600;
  color: #343a40;
}

.tip-text {
  font-size: 0.9rem;
  color: #6c757d;
}

/* Animations */
@keyframes pulse {
  0% {
    transform: scale(1);
  }

  50% {
    transform: scale(1.1);
  }

  100% {
    transform: scale(1);
  }
}

/* Empty State */
.empty-state {
  padding: 2rem;
}

.empty-state-icon {
  font-size: 3rem;
  color: #dee2e6;
}

.empty-state-title {
  margin-top: 1rem;
  font-weight: 600;
}

.empty-state-text {
  margin-bottom: 1.5rem;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
  .greeting-img {
    display: none;
  }

  .stat-card {
    margin-bottom: 1rem;
  }
}
</style>

<?php
// Helper functions that should be defined in your PHP code
function getTimeBasedGreeting() {
  $hour = date('G');
  if ($hour < 11) return 'pagi';
  if ($hour < 15) return 'siang';
  if ($hour < 19) return 'sore';
  return 'malam';
}

function getRandomQuote() {
  $quotes = [
    "Konsistensi kecil yang dilakukan setiap hari akan membawa hasil besar",
    "Pertumbuhan 1% setiap hari akan menjadi 37x lipat dalam setahun",
    "Setiap langkah kecil membawa kita lebih dekat kepada tujuan",
    "Kebiasaan baik dimulai dari tindakan kecil yang konsisten",
    "Hari ini adalah kesempatan untuk menjadi 1% lebih baik"
  ];
  return $quotes[array_rand($quotes)];
}

function getMoodIcon($mood) {
  $icons = [
    'happy' => 'emoji-smile',
    'normal' => 'emoji-neutral',
    'sad' => 'emoji-frown',
    'tired' => 'emoji-dizzy',
    'angry' => 'emoji-angry'
  ];
  return $icons[strtolower($mood)] ?? 'emoji-smile';
}

function getMoodEmoji($mood) {
  $emojis = [
    'happy' => '😊',
    'normal' => '😐',
    'sad' => '😔',
    'tired' => '😩',
    'angry' => '😠'
  ];
  return $emojis[strtolower($mood)] ?? '😊';
}

function getIbadahIcon($jenis) {
  $icons = [
    'sholat' => 'pray',
    'quran' => 'book',
    'dzikir' => 'chat-quote',
    'puasa' => 'droplet',
    'sedekah' => 'gift'
  ];
  return $icons[strtolower($jenis)] ?? 'pray';
}

function getIbadahColor($jenis) {
  $colors = [
    'sholat' => 'primary',
    'quran' => 'info',
    'dzikir' => 'warning',
    'puasa' => 'danger',
    'sedekah' => 'success'
  ];
  return $colors[strtolower($jenis)] ?? 'primary';
}
?>

<?php require_once 'layouts/footer.php'; ?>