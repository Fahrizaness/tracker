<?php
require_once __DIR__ .  '/../config/database.php';

class IbadahModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getAllByUserId($user_id) {
        $query = 'SELECT * FROM ibadah WHERE user_id = :user_id ORDER BY tanggal DESC';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
    $query = 'INSERT INTO ibadah 
              (user_id, jenis_ibadah, waktu, keterangan, tanggal, status) 
              VALUES 
              (:user_id, :jenis_ibadah, :waktu, :keterangan, :tanggal, :status)';
    
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':user_id', $data['user_id']);
    $stmt->bindParam(':jenis_ibadah', $data['jenis_ibadah']);
    $stmt->bindParam(':waktu', $data['waktu']);
    $stmt->bindParam(':keterangan', $data['keterangan']);
    $stmt->bindParam(':tanggal', $data['tanggal']);
    $stmt->bindParam(':status', $data['status']);
    
    return $stmt->execute();
}

    public function getMonthlyReport($user_id) {
    $query = 'SELECT 
                MONTH(tanggal) as bulan,
                YEAR(tanggal) as tahun,
                COUNT(*) as total_ibadah,
                SUM(status = "selesai") as tepat_waktu,
                SUM(status = "terlewat") as qadha
              FROM ibadah 
              WHERE user_id = :user_id
              GROUP BY YEAR(tanggal), MONTH(tanggal)
              ORDER BY tahun DESC, bulan DESC';
    
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}    public function getRecentRecords($user_id, $limit = 5) {
    $query = 'SELECT * FROM ibadah 
             WHERE user_id = :user_id 
             ORDER BY tanggal DESC, waktu DESC 
             LIMIT :limit';
    
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function deleteIbadah($id, $user_id)
{
    $stmt = $this->db->prepare("DELETE FROM ibadah WHERE id = ? AND user_id = ?");
    return $stmt->execute([$id, $user_id]);
}
public function getIbadahById($id, $user_id)
{
    $stmt = $this->db->prepare("SELECT * FROM ibadah WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function updateIbadah($id, $user_id, $data)
{
    $stmt = $this->db->prepare("UPDATE ibadah SET 
        jenis_ibadah = ?, 
        tanggal = ?, 
        waktu = ?, 
        status = ?, 
        keterangan = ?,
        updated_at = NOW()
        WHERE id = ? AND user_id = ?");
    
    return $stmt->execute([
        $data['jenis_ibadah'],
        $data['tanggal'],
        $data['waktu'],
        $data['status'],
        $data['keterangan'],
        $id,
        $user_id
    ]);
}
public function getCurrentStreak($user_id) {
    $query = 'WITH dates AS (
                SELECT DISTINCT tanggal 
                FROM ibadah 
                WHERE user_id = :user_id
                ORDER BY tanggal DESC
              ),
              streaks AS (
                SELECT 
                  tanggal,
                  @streak := IF(DATEDIFF(@prev_date, tanggal) = 1, @streak + 1, 1) AS streak,
                  @prev_date := tanggal
                FROM dates, (SELECT @streak := 0, @prev_date := NULL) AS vars
              )
              SELECT MAX(streak) as current_streak
              FROM streaks';
    
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['current_streak'] ?? 0;
}
}