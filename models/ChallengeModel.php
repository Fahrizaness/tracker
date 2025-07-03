<?php
// models/ChallengeModel.php
require_once __DIR__ . '/../config/database.php';

class ChallengeModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    // Ambil challenge user untuk hari ini
    public function getTodayChallenge($userId, $date) {
        $stmt = $this->db->prepare("
            SELECT dc.*, ct.nama 
            FROM daily_challenges dc
            JOIN challenge_templates ct ON ct.id = dc.template_id
            WHERE dc.user_id = :user_id AND dc.tanggal = :tanggal
            LIMIT 1
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':tanggal' => $date
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Ambil 1 challenge random dari template
    public function getRandomTemplateId() {
        $stmt = $this->db->query("SELECT id FROM challenge_templates ORDER BY RAND() LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['id'] : null;
    }

    // Buat challenge baru untuk user hari ini
    public function assignDailyChallenge($userId, $templateId, $date) {
        $stmt = $this->db->prepare("
            INSERT INTO daily_challenges (user_id, template_id, tanggal, status)
            VALUES (:user_id, :template_id, :tanggal, 'aktif')
        ");
        return $stmt->execute([
            ':user_id' => $userId,
            ':template_id' => $templateId,
            ':tanggal' => $date
        ]);
    }

    // Tandai challenge selesai
    public function markAsComplete($challengeId) {
        $stmt = $this->db->prepare("
            UPDATE daily_challenges 
            SET status = 'selesai' 
            WHERE id = :id
        ");
        return $stmt->execute([':id' => $challengeId]);
    }

    // Update streak user saat menyelesaikan challenge
    public function updateUserStreak($userId) {
        $today = date('Y-m-d');

        $stmt = $this->db->prepare("SELECT last_completed_date, streak FROM users WHERE id = :id");
        $stmt->execute([':id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $lastDate = $user['last_completed_date'];
            $streak = (int) $user['streak'];

            if ($lastDate === date('Y-m-d', strtotime('-1 day'))) {
                $streak++;
            } else {
                $streak = 1;
            }

            $updateStmt = $this->db->prepare("
                UPDATE users 
                SET streak = :streak, last_completed_date = :today 
                WHERE id = :id
            ");
            $updateStmt->execute([
                ':streak' => $streak,
                ':today' => $today,
                ':id' => $userId
            ]);
        }
    }

    // Ambil streak user
    public function getUserStreak($userId) {
        $stmt = $this->db->prepare("SELECT streak FROM users WHERE id = :id");
        $stmt->execute([':id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int) $row['streak'] : 0;
    }

    // Ambil status challenge 7 hari terakhir
    public function getLast7DaysCompletion($userId) {
        $stmt = $this->db->prepare("
            SELECT tanggal, status 
            FROM daily_challenges 
            WHERE user_id = :user_id 
            AND tanggal >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
            ORDER BY tanggal ASC
        ");
        $stmt->execute([':user_id' => $userId]);

        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[$row['tanggal']] = $row['status'];
        }

        $result = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $result[$date] = $data[$date] ?? 'none';
        }

        return $result;
    }
    public function getChallengeHistory($userId, $limit = 30) {
    $stmt = $this->db->prepare("
        SELECT dc.tanggal, ct.nama, dc.status
        FROM daily_challenges dc
        JOIN challenge_templates ct ON ct.id = dc.template_id
        WHERE dc.user_id = :user_id
        ORDER BY dc.tanggal DESC
        LIMIT :limit
    ");
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}