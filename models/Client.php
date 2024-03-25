<?php
require_once 'models/Invoice.php';

class Client {
    private $db;
    private $id;
    public $userId;
    public $name;
    public $email;
    public $billingAddress;
    public $invoices;


    public function __construct($db, $id=0) {
        $this->db = $db;
        if ($id > 0) {
            $this->id = $id;
            $stmt = $this->db->prepare("SELECT * FROM clients WHERE id = :id");
            $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
            $result = $stmt->execute();

            $row = $result->fetchArray(SQLITE3_ASSOC);
            $this->userId = $row['user_id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->billingAddress = $row['billing_address'];
            $this->invoices = $this->getInvoices();
        } else {
            $this->id = 0;
            $this->userId = 0;
            $this->name = '';
            $this->email = '';
            $this->billingAddress = '';
        }
    }

    public function getId() {
        return $this->id;
    }

    // Save or update a client
    public function save($id) {
        $userId = $this->userId;
        $name = $this->name;
        $email = $this->email;
        $billingAddress = $this->billingAddress;
        $id = $this->id;
        if ($this->exists($id)) {
            return $this->update($id, $name, $email, $billingAddress);
        } else {
            
            return $this->create($userId, $name, $email, $billingAddress);
        }
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

    public function all(){
        $stmt = $this->db->prepare("SELECT * FROM clients");
        $result = $stmt->execute();
        $clients = array();
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $clients[] = $row;
        }
        return $clients;
    }

    public function getInvoices() {
        $stmt = $this->db->prepare("SELECT * FROM invoices WHERE client_id = :client_id");
        $stmt->bindValue(':client_id', $this->id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $invoices = array();
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $invoices[] = new Invoice($this->db, $row['id']);
        }
        return $invoices;
    }
}

?>
