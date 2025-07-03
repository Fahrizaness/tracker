<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="container challenge-container">
  <h1 class="text-center mb-4">Daily Challenge</h1>
  <div class="row">
    <div class="col-md-8 offset-md-2">
      <div class="card shadow-sm challenge-card">
        <!-- Header -->
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
          <h2 class="h5 mb-0">Daily Challenge</h2>
          <div class="d-flex align-items-center">
            <span class="badge bg-<?= $badge['class'] ?> streak-badge" data-bs-toggle="tooltip" data-bs-placement="left"
              title="Streak kamu: <?= $streak ?> hari">
              <?= $badge['emoji'] ?> <?= $badge['label'] ?> (<?= $streak ?> hari)
            </span>
            <button class="btn btn-sm btn-outline-light ms-2 info-btn" id="streakInfoBtn">
              <i class="bi bi-info-circle"></i>
            </button>
          </div>
        </div>

        <!-- Body -->
        <div class="card-body">
          <?php if (empty($challenge)): ?>
          <div class="alert alert-info fade-in">
            <p class="mb-0">No challenge for today. Check back tomorrow!</p>
          </div>
          <?php else: ?>
          <div class="challenge-content">
            <h3 class="h4 text-primary challenge-title"><?= htmlspecialchars($challenge['nama']) ?></h3>

            <div class="status-container mb-4">
              <p class="mb-1">Status:
                <span
                  class="badge status-badge <?= $challenge['status'] === 'aktif' ? 'bg-warning text-dark' : 'bg-success' ?>">
                  <?= ucfirst($challenge['status']) ?>
                </span>
              </p>
            </div>

            <?php if ($challenge['status'] === 'aktif'): ?>
            <form method="POST" action="/challenge/complete" id="completeForm">
              <input type="hidden" name="id" value="<?= $challenge['id'] ?>">
              <button type="submit" class="btn btn-success complete-btn">
                <i class="bi bi-check-circle"></i> Tandai Selesai
              </button>
            </form>
            <?php else: ?>
            <div class="completion-message alert alert-success d-flex align-items-center fade-in">
              <i class="bi bi-check-circle-fill me-2"></i>
              <span>🎉 Challenge completed! Great job!</span>
            </div>
            <?php endif; ?>
          </div>
          <?php endif; ?>

          <!-- Streak Visual -->
          <div class="mt-4 streak-section">
            <h6 class="d-flex align-items-center">
              <i class="bi bi-fire me-2"></i>
              <span>Streak Visual (7 Hari Terakhir)</span>
            </h6>
            <div class="d-flex gap-2 streak-grid">
              <?php foreach ($completionData as $date => $status): ?>
              <?php
                $color = 'bg-secondary';
                if ($status === 'selesai') $color = 'bg-success';
                elseif ($status === 'aktif') $color = 'bg-warning';
              ?>
              <div class="streak-box <?= $color ?> streak-tooltip" data-bs-toggle="tooltip" data-bs-placement="top"
                title="<?= date('D, M j', strtotime($date)) ?> - <?= ucfirst($status) ?>">
                <?php if ($status === 'selesai'): ?>
                <i class="bi bi-check"></i>
                <?php elseif ($status === 'aktif'): ?>
                <i class="bi bi-arrow-right"></i>
                <?php endif; ?>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="card-footer text-muted small text-end">
          <i class="bi bi-calendar-event me-1"></i>
          <?= date('l, F j, Y') ?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="mt-3 text-end">
  <a href="/challenge/history" class="btn btn-outline-secondary btn-sm history-btn">
    <i class="bi bi-clock-history me-1"></i> Lihat Riwayat
  </a>
</div>

<!-- Streak Info Modal -->
<div class="modal fade" id="streakInfoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">About Streaks</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Your streak represents how many consecutive days you've completed challenges.</p>
        <ul>
          <li>🔥 1-3 days: Newbie</li>
          <li>🚀 4-6 days: Getting there</li>
          <li>🏆 7+ days: Champion!</li>
        </ul>
        <p class="mb-0">Keep it going! Completing challenges daily increases your streak.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Got it!</button>
      </div>
    </div>
  </div>
</div>

<style>
.challenge-container {
  margin-top: 2rem;
  margin-bottom: 2rem;
  animation: fadeIn 0.5s ease-in-out;
}

.challenge-card {
  border: none;
  border-radius: 12px;
  overflow: hidden;
  transition: transform 0.3s ease;
}

.challenge-card:hover {
  transform: translateY(-5px);
}

.challenge-content {
  padding: 1.5rem;
}

.completion-message {
  padding: 1rem;
  border-radius: 8px;
  animation: bounceIn 0.5s;
}

.status-container {
  background-color: #f8f9fa;
  padding: 1rem;
  border-radius: 8px;
  border-left: 4px solid var(--bs-primary);
}

.streak-grid {
  margin-top: 1rem;
}

.streak-grid .streak-box {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 0.9rem;
  transition: all 0.3s ease;
}

.streak-grid .streak-box:hover {
  transform: scale(1.1);
}

.streak-badge {
  font-size: 0.9rem;
  padding: 0.5rem 0.75rem;
  border-radius: 20px;
}

.complete-btn {
  padding: 0.5rem 1.5rem;
  border-radius: 8px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.complete-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.history-btn {
  transition: all 0.3s ease;
}

.history-btn:hover {
  transform: translateX(5px);
}

.fade-in {
  animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}

@keyframes bounceIn {
  0% {
    transform: scale(0.9);
    opacity: 0;
  }

  50% {
    transform: scale(1.05);
  }

  100% {
    transform: scale(1);
    opacity: 1;
  }
}

.streak-section {
  background-color: #f9f9f9;
  padding: 1rem;
  border-radius: 8px;
  margin-top: 2rem;
}

.info-btn {
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  padding: 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Initialize tooltips
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Streak info modal
  const streakInfoBtn = document.getElementById('streakInfoBtn');
  if (streakInfoBtn) {
    streakInfoBtn.addEventListener('click', function() {
      const modal = new bootstrap.Modal(document.getElementById('streakInfoModal'));
      modal.show();
    });
  }

  // Confirmation for completing challenge
  const completeForm = document.getElementById('completeForm');
  if (completeForm) {
    completeForm.addEventListener('submit', function(e) {
      const btn = this.querySelector('.complete-btn');
      btn.innerHTML =
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyelesaikan...';
      btn.disabled = true;
    });
  }

  // Animate streak boxes on load
  const streakBoxes = document.querySelectorAll('.streak-box');
  streakBoxes.forEach((box, index) => {
    setTimeout(() => {
      box.style.opacity = '1';
      box.style.transform = 'scale(1)';
    }, index * 100);
  });

  // Add pulse animation to active challenge status
  const activeBadge = document.querySelector('.status-badge.bg-warning');
  if (activeBadge) {
    setInterval(() => {
      activeBadge.classList.toggle('pulse');
    }, 2000);
  }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>