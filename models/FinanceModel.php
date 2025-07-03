    <?php
    require_once __DIR__ .  '/../config/database.php';

    class FinanceModel {
        private $db;

        public function __construct() {
            $database = new Database();
            $this->db = $database->connect();
        }

        public function getRecentTransactions($user_id, $limit = 10) {
            $query = 'SELECT * FROM finances 
                    WHERE user_id = :user_id 
                    ORDER BY tanggal DESC, created_at DESC 
                    LIMIT :limit';
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getCurrentBalance($user_id) {
            $query = 'SELECT 
                        SUM(CASE WHEN jenis = "pemasukan" THEN jumlah ELSE 0 END) as total_pemasukan,
                        SUM(CASE WHEN jenis = "pengeluaran" THEN jumlah ELSE 0 END) as total_pengeluaran,
                        SUM(CASE WHEN jenis = "pemasukan" THEN jumlah ELSE -jumlah END) as saldo
                    FROM finances
                    WHERE user_id = :user_id';
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function create($data) {
            $query = 'INSERT INTO finances 
                        (user_id, jenis, kategori, jumlah, deskripsi, tanggal) 
                    VALUES 
                        (:user_id, :jenis, :kategori, :jumlah, :deskripsi, :tanggal)';
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $data['user_id']);
            $stmt->bindParam(':jenis', $data['jenis']);
            $stmt->bindParam(':kategori', $data['kategori']);
            $stmt->bindParam(':jumlah', $data['jumlah']);
            $stmt->bindParam(':deskripsi', $data['deskripsi']);
            $stmt->bindParam(':tanggal', $data['tanggal']);

            return $stmt->execute();
        }

        public function getAllCategories() {
            return [
                'pemasukan' => [
                    'gaji', 'bonus', 'investasi', 'hadiah', 'lainnya'
                ],
                'pengeluaran' => [
                    'makanan', 'transportasi', 'hiburan', 'kesehatan', 
                    'pendidikan', 'tagihan', 'belanja', 'lainnya'
                ]
            ];
        }

        public function getMonthlySummary($user_id) {
            $query = 'SELECT 
                        DATE_FORMAT(tanggal, "%Y-%m") as bulan,
                        SUM(CASE WHEN jenis = "pemasukan" THEN jumlah ELSE 0 END) as pemasukan,
                        SUM(CASE WHEN jenis = "pengeluaran" THEN jumlah ELSE 0 END) as pengeluaran,
                        SUM(CASE WHEN jenis = "pemasukan" THEN jumlah ELSE -jumlah END) as saldo
                    FROM finances
                    WHERE user_id = :user_id
                    GROUP BY DATE_FORMAT(tanggal, "%Y-%m")
                    ORDER BY bulan DESC
                    LIMIT 3';
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getMonthlyReports($user_id) {
            $query = 'SELECT 
                        DATE_FORMAT(tanggal, "%Y-%m") as bulan,
                        SUM(CASE WHEN jenis = "pemasukan" THEN jumlah ELSE 0 END) as total_pemasukan,
                        SUM(CASE WHEN jenis = "pengeluaran" THEN jumlah ELSE 0 END) as total_pengeluaran,
                        COUNT(*) as total_transaksi
                    FROM finances
                    WHERE user_id = :user_id
                    GROUP BY DATE_FORMAT(tanggal, "%Y-%m")
                    ORDER BY bulan DESC';
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getCategorySpending($user_id) {
            $query = 'SELECT 
                        kategori,
                        COUNT(*) as jumlah_transaksi,
                        SUM(jumlah) as total_pengeluaran
                    FROM finances
                    WHERE user_id = :user_id AND jenis = "pengeluaran"
                    GROUP BY kategori
                    ORDER BY total_pengeluaran DESC';
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getYearlySummary($user_id) {
            $query = 'SELECT 
                        YEAR(tanggal) as tahun,
                        SUM(CASE WHEN jenis = "pemasukan" THEN jumlah ELSE 0 END) as pemasukan,
                        SUM(CASE WHEN jenis = "pengeluaran" THEN jumlah ELSE 0 END) as pengeluaran,
                        SUM(CASE WHEN jenis = "pemasukan" THEN jumlah ELSE -jumlah END) as saldo
                    FROM finances
                    WHERE user_id = :user_id
                    GROUP BY YEAR(tanggal)
                    ORDER BY tahun DESC';
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        public function getCurrentMonthSummary($user_id) {
        $query = 'SELECT 
                    SUM(CASE WHEN jenis = "pemasukan" THEN jumlah ELSE 0 END) as pemasukan,
                    SUM(CASE WHEN jenis = "pengeluaran" THEN jumlah ELSE 0 END) as pengeluaran,
                    SUM(CASE WHEN jenis = "pemasukan" THEN jumlah ELSE -jumlah END) as saldo
                FROM finances
                WHERE user_id = :user_id
                AND DATE_FORMAT(tanggal, "%Y-%m") = DATE_FORMAT(CURDATE(), "%Y-%m")';
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    }