<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow">
        <div class="card-body p-4">
          <h2 class="card-title text-center mb-4">Login ke Tumbuh1%</h2>

          <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
          <?php endif; ?>

          <?php if (isset($_SESSION['success'])): ?>
          <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
          <?php endif; ?>

          <form action="/tumbuh1%/login" method="POST">
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" required
                value="<?= htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
              <div class="form-text">
                <a href="/tumbuh1%/forgot-password">Lupa password?</a>
              </div>
            </div>

            <div class="d-grid mb-3">
              <button type="submit" class="btn btn-primary">Login</button>
            </div>

            <div class="text-center">
              Belum punya akun? <a href="/tumbuh1%/register">Daftar disini</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>