<?php
require_once __DIR__ . '/../models/MoodModel.php';

class MoodController {
    private $model;

    public function __construct() {
        $this->model = new MoodModel();
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /tumbuh1%/login');
            exit();
        }

        $moodRecords = $this->model->getAllByUserId($_SESSION['user_id']);
        require_once __DIR__ . '/../views/moods/index.php';
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /tumbuh1%/login');
            exit();
        }

        require_once __DIR__ . '/../views/moods/create.php';
    }

    public function store() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /tumbuh1%/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'mood' => $_POST['mood'],
                'tingkat_energi' => $_POST['tingkat_energi'],
                'catatan' => $_POST['catatan'],
                'tanggal' => $_POST['tanggal']
            ];

            if ($this->model->create($data)) {
                $_SESSION['success'] = 'Catatan mood berhasil disimpan';
                header('Location: /tumbuh1%/mood');
                exit();
            } else {
                $_SESSION['error'] = 'Gagal menyimpan catatan mood';
                header('Location: /tumbuh1%/mood/create');
                exit();
            }
        }
    }

    public function report() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /tumbuh1%/login');
            exit();
        }

        $reports = $this->model->getMoodAnalysis($_SESSION['user_id']);
        $weeklyReport = $this->model->getWeeklyMoodTrend($_SESSION['user_id']);
        require_once __DIR__ . '/../views/moods/report.php';
    }
}