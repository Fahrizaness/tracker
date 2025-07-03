<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>
    Tumbuh1% - <?= isset($title) ? htmlspecialchars($title) : 'Personal Growth Tracker' ?>
  </title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />

  <!-- Load CSS global -->
  <link rel="stylesheet" href="/assets/css/style.css" />
  <link rel="stylesheet" href="/assets/css/navbar.css" />
  <link rel="stylesheet" href="/assets/css/footer.css" />

  <link rel="icon" href="/assets/images/favicon.ico" />
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm fixed-top">
    <div class="container-fluid col-lg-10 col-xl-9">
      <a class="navbar-brand fw-bold d-flex align-items-center" href="/tumbuh1%">
        <i class="bi bi-graph-up me-2 fs-4"></i>
        <span class="fs-5">Tumbuh1%</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
        aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNavbar">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['REQUEST_URI']) == 'tumbuh1%' ? 'active' : '' ?>" href="/tumbuh1%">
              <i class="bi bi-house me-1"></i> Beranda
            </a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="trackerDropdown" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              <i class="bi bi-bar-chart me-1"></i> Tracker
            </a>
            <ul class="dropdown-menu border-0 shadow-sm animate__animated animate__fadeIn">
              <li>
                <a class="dropdown-item" href="/tumbuh1%/ibadah"><i class="bi bi-pray me-2 text-muted"></i> Ibadah</a>
              </li>
              <li>
                <a class="dropdown-item" href="/tumbuh1%/mood"><i class="bi bi-emoji-smile me-2 text-muted"></i>
                  Mood</a>
              </li>
              <li>
                <a class="dropdown-item" href="/tumbuh1%/finance"><i class="bi bi-cash-stack me-2 text-muted"></i>
                  Finansial</a>
              </li>
              <li>
                <a class="dropdown-item" href="/tumbuh1%/challenge"><i class="bi bi-flag me-2 text-muted"></i>
                  Tantangan</a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/tumbuh1%/reports">
              <i class="bi bi-graph-up-arrow me-1"></i> Laporan
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/tumbuh1%/goals">
              <i class="bi bi-bullseye me-1"></i> Target
            </a>
          </li>
        </ul>

        <div class="d-flex align-items-center ms-auto">
          <button class="btn btn-link text-white-50 p-0 me-3 d-flex align-items-center" id="themeToggle"
            aria-label="Toggle theme">
            <i class="bi bi-moon-stars fs-5"></i>
          </button>

          <?php if (isset($_SESSION['user_id'])): ?>
          <div class="dropdown">
            <button class="btn btn-outline-light dropdown-toggle rounded-pill px-3 py-2 d-flex align-items-center"
              id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle me-2"></i>
              <span class="d-none d-lg-inline"><?= htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm animate__animated animate__fadeIn">
              <li>
                <a class="dropdown-item" href="/tumbuh1%/profile"><i
                    class="bi bi-person me-2 text-muted"></i>Profile</a>
              </li>
              <li>
                <a class="dropdown-item" href="/tumbuh1%/settings"><i
                    class="bi bi-gear me-2 text-muted"></i>Settings</a>
              </li>
              <li>
                <hr class="dropdown-divider" />
              </li>
              <li>
                <a class="dropdown-item text-danger" href="/tumbuh1%/logout"><i
                    class="bi bi-box-arrow-right me-2"></i>Logout</a>
              </li>
            </ul>
          </div>
          <?php else: ?>
          <a href="/tumbuh1%/login" class="btn btn-light rounded-pill px-4 py-2">
            Masuk
          </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>

  <main class="container-fluid py-4 mt-5 pt-4">