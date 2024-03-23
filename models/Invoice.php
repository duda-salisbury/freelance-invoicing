<?php

class Invoice {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Save or update an invoice
    public function save($id, $clientId, $invoiceDate, $dueDate, $total) {
        if ($this->exists($id)) {
            echo "Updating invoice";
            return $this->update($id, $clientId, $invoiceDate, $dueDate, $total);
        } else {
            echo "Creating invoice";
            return $this->create($clientId, $invoiceDate, $dueDate, $total);
        }
    }

    // Find an invoice by ID
    public function find($invoiceId) {
        $stmt = $this->db->prepare("SELECT * FROM invoices WHERE id = :id");
        $stmt->bindValue(':id', $invoiceId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC);
    }

    // Check if an invoice exists
    private function exists($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM invoices WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        return $result->fetchArray()[0] > 0;
    }

    // Create a new invoice
    private function create($clientId, $invoiceDate, $dueDate, $total) {
        echo "insert into invoices (client_id, invoice_date, due_date, total) values ($clientId, $invoiceDate, $dueDate, $total)";
        $stmt = $this->db->prepare("INSERT INTO invoices (client_id, invoice_date, due_date, total_amount) VALUES (:client_id, :invoice_date, :due_date, :total)");
        $stmt->bindValue(':client_id', $clientId, SQLITE3_INTEGER);
        $stmt->bindValue(':invoice_date', $invoiceDate, SQLITE3_TEXT);
        $stmt->bindValue(':due_date', $dueDate, SQLITE3_TEXT);
        $stmt->bindValue(':total', $total, SQLITE3_FLOAT);
        $stmt->execute();
        if ($stmt->execute()) {
            return $this->db->lastInsertRowID();
        } else {
            return false;
        }
    }

    // Update an invoice
    private function update($id, $clientId, $invoiceDate, $dueDate, $total) {
        $stmt = $this->db->prepare("UPDATE invoices SET client_id = :client_id, invoice_date = :invoice_date, due_date = :due_date, total = :total WHERE id = :id");
        $stmt->bindValue(':client_id', $clientId, SQLITE3_INTEGER);
        $stmt->bindValue(':invoice_date', $invoiceDate, SQLITE3_TEXT);
        $stmt->bindValue(':due_date', $dueDate, SQLITE3_TEXT);
        $stmt->bindValue(':total', $total, SQLITE3_FLOAT);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        return $stmt->execute();
    }

    // Delete an invoice
    public function delete($invoiceId) {
        $stmt = $this->db->prepare("DELETE FROM invoices WHERE id = :id");
        $stmt->bindValue(':id', $invoiceId, SQLITE3_INTEGER);
        return $stmt->execute();
    }

    // Get all invoices
    public function all() {
        $stmt = $this->db->prepare("SELECT * FROM invoices");
        $result = $stmt->execute();
        $invoices = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $invoices[] = $row;
        }
        return $invoices;
    }

    // Get all invoices for a client
    public function allForClient($clientId) {
        $stmt = $this->db->prepare("SELECT * FROM invoices WHERE client_id = :client_id");
        $stmt->bindValue(':client_id', $clientId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $invoices = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $invoices[] = $row;
        }
        return $invoices;
    }
}
