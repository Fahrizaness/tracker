<?php
// controllers/ChallengeController.php
require_once __DIR__ . '/../models/ChallengeModel.php';

class ChallengeController {
    private $challengeModel;

    public function __construct() {
        $this->challengeModel = new ChallengeModel();
    }

    // Halaman utama challenge
  public function index() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit();
    }

    $userId = $_SESSION['user_id'];
    $today = date('Y-m-d');

    // Ambil challenge hari ini
    $challenge = $this->challengeModel->getTodayChallenge($userId, $today);
    if (!$challenge) {
        $templateId = $this->challengeModel->getRandomTemplateId();
        if ($templateId) {
            $this->challengeModel->assignDailyChallenge($userId, $templateId, $today);
            $challenge = $this->challengeModel->getTodayChallenge($userId, $today);
        }
    }

    // Ambil streak & badge
    $streak = $this->challengeModel->getUserStreak($userId);
    $badge = [
        'label' => 'Belum ada streak',
        'emoji' => '🕒',
        'class' => 'secondary'
    ];

    if ($streak >= 30) {
        $badge = ['label' => 'Master Streak', 'emoji' => '🔥', 'class' => 'danger'];
    } elseif ($streak >= 14) {
        $badge = ['label' => 'Legenda Harian', 'emoji' => '🥇', 'class' => 'warning'];
    } elseif ($streak >= 7) {
        $badge = ['label' => 'Pejuang Konsisten', 'emoji' => '🥈', 'class' => 'success'];
    } elseif ($streak >= 3) {
        $badge = ['label' => 'Disiplin Pemula', 'emoji' => '🥉', 'class' => 'primary'];
    }

    // Ambil data streak visual
    $completionData = $this->challengeModel->getLast7DaysCompletion($userId);

    // Kirim ke view
    require_once __DIR__ . '/../views/challenge/index.php';
}
public function history() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit();
    }

    $userId = $_SESSION['user_id'];
    $history = $this->challengeModel->getChallengeHistory($userId);

    require_once __DIR__ . '/../views/challenge/history.php';
}



    // Tandai selesai
    public function complete() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $this->challengeModel->markAsComplete($_POST['id']);
        $this->challengeModel->updateUserStreak($_SESSION['user_id']);
        header('Location: /challenge');
        exit();
    }
}

}