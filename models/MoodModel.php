<?php
require_once __DIR__ . '/../config/database.php';

class MoodModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getAllByUserId($user_id) {
        $query = 'SELECT * FROM moods WHERE user_id = :user_id ORDER BY tanggal DESC, created_at DESC';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = 'INSERT INTO moods (user_id, mood, tingkat_energi, catatan, tanggal) 
                  VALUES (:user_id, :mood, :tingkat_energi, :catatan, :tanggal)';
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':mood', $data['mood']);
        $stmt->bindParam(':tingkat_energi', $data['tingkat_energi']);
        $stmt->bindParam(':catatan', $data['catatan']);
        $stmt->bindParam(':tanggal', $data['tanggal']);

        return $stmt->execute();
    }

    public function getMoodAnalysis($user_id) {
        $query = 'SELECT 
                    mood,
                    COUNT(*) as jumlah,
                    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM moods WHERE user_id = :user_id), 2) as persentase,
                    AVG(tingkat_energi) as rata_energi
                  FROM moods 
                  WHERE user_id = :user_id
                  GROUP BY mood
                  ORDER BY jumlah DESC';
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWeeklyMoodTrend($user_id) {
        $query = 'SELECT 
                    YEARWEEK(tanggal) as tahun_minggu,
                    MIN(tanggal) as tanggal_mulai,
                    MAX(tanggal) as tanggal_selesai,
                    COUNT(*) as total_catatan,
                    GROUP_CONCAT(mood SEPARATOR ",") as mood_list,
                    AVG(tingkat_energi) as rata_energi
                  FROM moods
                  WHERE user_id = :user_id
                  GROUP BY YEARWEEK(tanggal)
                  ORDER BY tahun_minggu DESC
                  LIMIT 8';
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getRecentMoodAnalysis($user_id, $days = 7) {
    $query = 'SELECT 
                mood,
                COUNT(*) as count,
                AVG(tingkat_energi) as avg_energy
              FROM moods
              WHERE user_id = :user_id 
              AND tanggal >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
              GROUP BY mood
              ORDER BY count DESC';
    
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':days', $days, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}