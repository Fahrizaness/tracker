<?php
require_once __DIR__ . '/../models/IbadahModel.php';
require_once __DIR__ . '/../models/MoodModel.php';
require_once __DIR__ . '/../models/FinanceModel.php';

class HomeController {
    private $ibadahModel;
    private $moodModel;
    private $financeModel;

    public function __construct() {
        $this->ibadahModel = new IbadahModel();
        $this->moodModel = new MoodModel();
        $this->financeModel = new FinanceModel();
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /tumbuh1%/login');
            exit();
        }

        $userId = $_SESSION['user_id'];
        
        // Get data for all trackers
        $recentIbadah = $this->ibadahModel->getRecentRecords($userId, 3);
        $moodAnalysis = $this->moodModel->getRecentMoodAnalysis($userId);
        $financeSummary = $this->financeModel->getCurrentMonthSummary($userId);
        
        // Get streak data
        $ibadahStreak = $this->ibadahModel->getCurrentStreak($userId);
        
        require_once __DIR__ . '/../views/home.php';
    }

    public function dashboardData() {
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            exit();
        }

        $userId = $_SESSION['user_id'];
        
        // This method can be called via AJAX to get updated dashboard data
        $data = [
            'mood' => $this->moodModel->getWeeklyMoodTrend($userId, 4),
            'finance' => $this->financeModel->getMonthlyReports($userId, 6),
            'ibadah' => $this->ibadahModel->getMonthlyReport($userId, 6)
        ];
        
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}