<?php

class Client {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Save or update a client
    public function save($id, $userId, $name, $email, $billingAddress) {
        if ($this->exists($id)) {
            return $this->update($id, $name, $email, $billingAddress);
        } else {
            return $this->create($userId, $name, $email, $billingAddress);
        }
    }

    // Find a client by ID
    public function find($clientId) {
        $stmt = $this->db->prepare("SELECT * FROM clients WHERE id = :id");
        $stmt->bindValue(':id', $clientId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC);
    }

    // Check if a client exists for a user with a given name
    private function exists($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM clients WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        return $result->fetchArray()[0] > 0;
    }

    // Create a new client
    private function create($userId, $name, $email, $billingAddress) {
        $stmt = $this->db->prepare("INSERT INTO clients (user_id, name, email, billing_address) VALUES (:user_id, :name, :email, :billing_address)");
        $stmt->bindValue(':user_id', $userId, SQLITE3_INTEGER);
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':billing_address', $billingAddress, SQLITE3_TEXT);
        $stmt->execute();
        return $this->db->lastInsertRowID();
    }

    // Update a client
    private function update($id, $name, $email, $billingAddress) {
    
        $stmt = $this->db->prepare("UPDATE clients SET name = :name, email = :email, billing_address = :billing_address WHERE id = :id");
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':billing_address', $billingAddress, SQLITE3_TEXT);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        return $stmt->execute();
    }

    // Delete a client
    public function delete($clientId) {
        $stmt = $this->db->prepare("DELETE FROM clients WHERE id = :id");
        $stmt->bindValue(':id', $clientId, SQLITE3_INTEGER);
        return $stmt->execute();
    }
}

?>
