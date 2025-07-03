<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-4">
  <h2 class="mb-4">📜 Riwayat Challenge</h2>

  <table class="table table-bordered table-striped">
    <thead class="table-light">
      <tr>
        <th>Tanggal</th>
        <th>Nama Challenge</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($history as $item): ?>
      <tr>
        <td><?= date('D, d M Y', strtotime($item['tanggal'])) ?></td>
        <td><?= htmlspecialchars($item['nama']) ?></td>
        <td>
          <span class="badge <?= $item['status'] === 'selesai' ? 'bg-success' : 'bg-warning text-dark' ?>">
            <?= ucfirst($item['status']) ?>
          </span>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>