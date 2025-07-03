<?php
require_once __DIR__ . '/../config/database.php';

class UserModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getUserByEmail($email) {
        $query = 'SELECT * FROM users WHERE email = :email LIMIT 1';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($data) {
    $query = 'INSERT INTO users (name, email, password) 
              VALUES (:name, :email, :password)';
    
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':password', $data['password']);

    if ($stmt->execute()) {
        return $this->db->lastInsertId();
    }
    return false;
}


    public function updateUser($userId, $data) {
        // Implementation for updating user profile
    }

    public function updatePassword($userId, $newPassword) {
        // Implementation for password update
    }
}