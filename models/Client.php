<?php

class Client {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Create a new client
    public function createClient($userId, $name, $email, $billingAddress) {
        $stmt = $this->db->prepare("INSERT INTO clients (user_id, name, email, billing_address) VALUES (:user_id, :name, :email, :billing_address)");
        $stmt->bindValue(':user_id', $userId, SQLITE3_INTEGER);
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':billing_address', $billingAddress, SQLITE3_TEXT);
        $stmt->execute();
        return $this->db->lastInsertRowID();
    }

    // Retrieve a client by ID
    public function getClientById($clientId) {
        $stmt = $this->db->prepare("SELECT * FROM clients WHERE id = :id");
        $stmt->bindValue(':id', $clientId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC);
    }

    // Update a client's information
    public function updateClient($clientId, $name, $email, $billingAddress) {
        $stmt = $this->db->prepare("UPDATE clients SET name = :name, email = :email, billing_address = :billing_address WHERE id = :id");
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':billing_address', $billingAddress, SQLITE3_TEXT);
        $stmt->bindValue(':id', $clientId, SQLITE3_INTEGER);
        return $stmt->execute();
    }

    // Delete a client
    public function deleteClient($clientId) {
        $stmt = $this->db->prepare("DELETE FROM clients WHERE id = :id");
        $stmt->bindValue(':id', $clientId, SQLITE3_INTEGER);
        return $stmt->execute();
    }
}

?>
