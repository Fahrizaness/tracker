<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow">
        <div class="card-body p-4">
          <h2 class="card-title text-center mb-4">Daftar Akun Baru</h2>

          <?php if (!empty($_SESSION['errors'])): ?>
          <div class="alert alert-danger">
            <ul class="mb-0">
              <?php foreach ($_SESSION['errors'] as $error): ?>
              <li><?= $error; ?></li>
              <?php endforeach; unset($_SESSION['errors']); ?>
            </ul>
          </div>
          <?php endif; ?>

          <form action="/tumbuh1%/register" method="POST">
            <div class="mb-3">
              <label for="name" class="form-label">Nama Lengkap</label>
              <input type="text" class="form-control" id="name" name="name" required
                value="<?= htmlspecialchars($_SESSION['old_input']['name'] ?? ''); unset($_SESSION['old_input']['name']); ?>">
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" required
                value="<?= htmlspecialchars($_SESSION['old_input']['email'] ?? ''); unset($_SESSION['old_input']['email']); ?>">
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
              <div class="form-text">Minimal 8 karakter</div>
            </div>

            <div class="mb-3">
              <label for="confirm_password" class="form-label">Konfirmasi Password</label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>

            <div class="d-grid mb-3">
              <button type="submit" class="btn btn-primary">Daftar</button>
            </div>

            <div class="text-center">
              Sudah punya akun? <a href="/tumbuh1%/login">Login disini</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>