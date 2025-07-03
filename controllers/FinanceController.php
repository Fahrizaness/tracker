<?php
require_once __DIR__ . '/../models/FinanceModel.php';

class FinanceController {
    private $model;

    public function __construct() {
        $this->model = new FinanceModel();
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /tumbuh1%/login');
            exit();
        }

        $transactions = $this->model->getRecentTransactions($_SESSION['user_id']);
        $balance = $this->model->getCurrentBalance($_SESSION['user_id']);
        $monthlySummary = $this->model->getMonthlySummary($_SESSION['user_id']);
        
       require_once __DIR__. '/../views/finances/index.php';
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /tumbuh1%/login');
            exit();
        }

        $categories = $this->model->getAllCategories();
        require_once __DIR__ . '/../views/finances/create.php';
    }

    public function store() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /tumbuh1%/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'jenis' => $_POST['jenis'],
                'kategori' => $_POST['kategori'],
                'jumlah' => $_POST['jumlah'],
                'deskripsi' => $_POST['deskripsi'],
                'tanggal' => $_POST['tanggal']
            ];

            if ($this->model->create($data)) {
                $_SESSION['success'] = 'Transaksi berhasil dicatat';
                header('Location: /tumbuh1%/finance');
                exit();
            } else {
                $_SESSION['error'] = 'Gagal mencatat transaksi';
                header('Location: /tumbuh1%/finance/create');
                exit();
            }
        }
    }

    public function report() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /tumbuh1%/login');
            exit();
        }

        $monthlyReports = $this->model->getMonthlyReports($_SESSION['user_id']);
        $categorySpending = $this->model->getCategorySpending($_SESSION['user_id']);
        $yearlySummary = $this->model->getYearlySummary($_SESSION['user_id']);
        
        require_once __DIR__ .'/../views/finances/report.php';
    }
}