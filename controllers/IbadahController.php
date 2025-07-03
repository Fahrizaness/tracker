<?php
require_once __DIR__ .'/../models/IbadahModel.php';

class IbadahController {
    private $model;

    public function __construct() {
        $this->model = new IbadahModel();
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /tumbuh1%/login');
            exit();
        }

        $ibadahRecords = $this->model->getAllByUserId($_SESSION['user_id']);
        $currentStreak = $this->model->getCurrentStreak($_SESSION['user_id']);
        
        require_once __DIR__ . '/../views/ibadah/index.php';
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /tumbuh1%/login');
            exit();
        }

        require_once __DIR__ . '/../views/ibadah/create.php';
    }

    public function store() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /tumbuh1%/login');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'user_id' => $_SESSION['user_id'],
            'jenis_ibadah' => $_POST['jenis_ibadah'],
            'waktu' => $_POST['waktu'],
            'keterangan' => $_POST['keterangan'],
            'tanggal' => $_POST['tanggal'],
            'status' => $_POST['status'] ?? 'selesai' // Default value
        ];

        if ($this->model->create($data)) {
            $_SESSION['success'] = 'Catatan ibadah berhasil disimpan!';
            header('Location: /tumbuh1%/ibadah');
            exit();
        } else {
            $_SESSION['error'] = 'Gagal menyimpan catatan ibadah';
            header('Location: /tumbuh1%/ibadah/create');
            exit();
        }
    }
}
public function delete()
{
    // Verifikasi CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'Token CSRF tidak valid';
        header('Location: /tumbuh1%/ibadah');
        exit;
    }

    // Verifikasi method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['_method'] ?? '') !== 'DELETE') {
        $_SESSION['error'] = 'Metode request tidak valid';
        header('Location: /tumbuh1%/ibadah');
        exit;
    }

    // Verifikasi user login
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = 'Anda harus login terlebih dahulu';
        header('Location: /tumbuh1%/login');
        exit;
    }

    $id = $_POST['id'] ?? null;
    
    if (!$id) {
        $_SESSION['error'] = 'ID ibadah tidak valid';
        header('Location: /tumbuh1%/ibadah');
        exit;
    }

    try {
        // Panggil model untuk delete
        $success = $this->model->deleteIbadah($id, $_SESSION['user_id']);
        
        if ($success) {
            $_SESSION['success'] = 'Catatan ibadah berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus catatan ibadah atau data tidak ditemukan';
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Terjadi kesalahan: ' . $e->getMessage();
    }
    
    header('Location: /tumbuh1%/ibadah');
    exit;
}
public function edit()
{
    // Verifikasi user sudah login
    if (!isset($_SESSION['user_id'])) {
        header('Location: /tumbuh1%/login');
        exit;
    }

    $id = $_GET['id'] ?? null;
    
    if (!$id || !is_numeric($id)) {
        $_SESSION['error'] = 'ID ibadah tidak valid';
        header('Location: /tumbuh1%/ibadah');
        exit;
    }

    try {
        // Ambil data berdasarkan ID dan user_id
        $ibadah = $this->model->getIbadahById($id, $_SESSION['user_id']);
        
        if (!$ibadah) {
            $_SESSION['error'] = 'Data ibadah tidak ditemukan';
            header('Location: /tumbuh1%/ibadah');
            exit;
        }

        // Tampilkan view edit
        require 'views/ibadah/edit.php';
        
    } catch (Exception $e) {
        $_SESSION['error'] = 'Terjadi kesalahan sistem';
        header('Location: /tumbuh1%/ibadah');
        exit;
    }
}

public function update()
{
    // Verifikasi CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'Token CSRF tidak valid';
        header('Location: /tumbuh1%/ibadah');
        exit;
    }

    // Verifikasi user login
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = 'Anda harus login terlebih dahulu';
        header('Location: /tumbuh1%/login');
        exit;
    }

    $id = $_POST['id'] ?? null;
    
    if (!$id) {
        $_SESSION['error'] = 'ID ibadah tidak valid';
        header('Location: /tumbuh1%/ibadah');
        exit;
    }

    // Validasi input
    $data = [
        'jenis_ibadah' => $_POST['jenis_ibadah'] ?? '',
        'tanggal' => $_POST['tanggal'] ?? '',
        'waktu' => $_POST['waktu'] ?? '',
        'status' => $_POST['status'] ?? 'selesai',
        'keterangan' => $_POST['keterangan'] ?? ''
    ];

    try {
        // Update data ibadah
        $success = $this->model->updateIbadah($id, $_SESSION['user_id'], $data);
        
        if ($success) {
            $_SESSION['success'] = 'Catatan ibadah berhasil diperbarui';
        } else {
            $_SESSION['error'] = 'Gagal memperbarui catatan ibadah';
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Terjadi kesalahan: ' . $e->getMessage();
    }
    
    header('Location: /tumbuh1%/ibadah');
    exit;
}

    public function report() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /tumbuh1%/login');
            exit();
        }

        $reports = $this->model->getMonthlyReport($_SESSION['user_id']);
        require_once __DIR__ . '/../views/ibadah/report.php';
    }
}