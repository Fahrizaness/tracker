<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-lg border-0">
        <div class="card-body p-4">
          <h3 class="card-title text-primary mb-4">
            <i class="bi bi-plus-circle me-2"></i> Tambah Transaksi
          </h3>

          <form action="/tumbuh1%/finance/store" method="POST">
            <div class="mb-3">
              <label for="tanggal" class="form-label">Tanggal</label>
              <input type="date" class="form-control" id="tanggal" name="tanggal" required
                value="<?= date('Y-m-d'); ?>">
            </div>

            <div class="mb-3">
              <label for="jenis" class="form-label">Jenis Transaksi</label>
              <select class="form-select" id="jenis" name="jenis" required>
                <option value="">Pilih Jenis</option>
                <option value="pemasukan">Pemasukan</option>
                <option value="pengeluaran">Pengeluaran</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="kategori" class="form-label">Kategori</label>
              <select class="form-select" id="kategori" name="kategori" required>
                <option value="">Pilih Kategori</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="jumlah" class="form-label">Jumlah (Rp)</label>
              <input type="number" class="form-control" id="jumlah" name="jumlah" min="0" step="100" required
                placeholder="Contoh: 50000">
            </div>

            <div class="mb-3">
              <label for="deskripsi" class="form-label">Deskripsi (Opsional)</label>
              <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                placeholder="Contoh: Gaji bulan Juli atau beli makan siang"></textarea>
            </div>

            <div class="d-flex justify-content-between mt-4">
              <a href="/tumbuh1%/finance" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-success px-4">
                <i class="bi bi-save me-1"></i> Simpan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const categories = <?= json_encode($categories); ?>;

function updateCategories() {
  const jenis = document.getElementById('jenis').value;
  const kategori = document.getElementById('kategori');

  kategori.innerHTML = '<option value="">Pilih Kategori</option>';

  if (jenis && categories[jenis]) {
    categories[jenis].forEach(function(cat) {
      const option = document.createElement('option');
      option.value = cat;
      option.textContent = cat.charAt(0).toUpperCase() + cat.slice(1);
      kategori.appendChild(option);
    });
  }
}

document.addEventListener('DOMContentLoaded', function() {
  updateCategories(); // untuk inisialisasi jika value 'jenis' sudah dipilih
  document.getElementById('jenis').addEventListener('change', updateCategories);
});
</script>


<?php require_once __DIR__ . '/../layouts/footer.php'; ?>