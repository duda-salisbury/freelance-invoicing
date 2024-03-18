<?php

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Create a new user
    public function createUser($email, $passwordHash) {
        $stmt = $this->db->prepare("INSERT INTO users (email, password_hash) VALUES (:email, :password_hash)");
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':password_hash', $passwordHash, SQLITE3_TEXT);
        $stmt->execute();
        return $this->db->lastInsertRowID();
    }

    // Retrieve a user by email
    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC);
    }

    // Update a user's password
    public function updateUserPassword($userId, $newPasswordHash) {
        $stmt = $this->db->prepare("UPDATE users SET password_hash = :password_hash WHERE id = :id");
        $stmt->bindValue(':password_hash', $newPasswordHash, SQLITE3_TEXT);
        $stmt->bindValue(':id', $userId, SQLITE3_INTEGER);
        return $stmt->execute();
    }

    // Delete a user
    public function deleteUser($userId) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindValue(':id', $userId, SQLITE3_INTEGER);
        return $stmt->execute();
    }
}

?>
