<?php

class Payment {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Save or update a payment
    public function save($id, $invoiceId, $paymentDate, $amount) {
        if ($this->exists($id)) {
            return $this->update($id, $paymentDate, $amount);
        } else {
            return $this->create($invoiceId, $paymentDate, $amount);
        }
    }

    // Find a payment by ID
    public function find($paymentId) {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE id = :id");
        $stmt->bindValue(':id', $paymentId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC);
    }

    // Check if a payment exists for a user with a given name
    private function exists($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM payments WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        return $result->fetchArray()[0] > 0;
    }

    // Create a new payment
    private function create($invoiceId, $paymentDate, $amount) {
        $stmt = $this->db->prepare("INSERT INTO payments (invoice_id, payment_date, amount) VALUES (:invoice_id, :payment_date, :amount)");
        $stmt->bindValue(':invoice_id', $invoiceId, SQLITE3_INTEGER);
        $stmt->bindValue(':payment_date', $paymentDate, SQLITE3_TEXT);
        $stmt->bindValue(':amount', $amount, SQLITE3_FLOAT);
        $stmt->execute();
        return $this->db->lastInsertRowID();
    }

    // Update a payment
    private function update($id, $paymentDate, $amount) {
        $stmt = $this->db->prepare("UPDATE payments SET payment_date = :payment_date, amount = :amount WHERE id = :id");
        $stmt->bindValue(':payment_date', $paymentDate, SQLITE3_TEXT);
        $stmt->bindValue(':amount', $amount, SQLITE3_FLOAT);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        return $stmt->execute();
    }

    // Delete a payment
    public function delete($paymentId) {
        $stmt = $this->db->prepare("DELETE FROM payments WHERE id = :id");
        $stmt->bindValue(':id', $paymentId, SQLITE3_INTEGER);
        return $stmt->execute();
    }

    // Get all payments for an invoice
    public function all($invoiceId) {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE invoice_id = :invoice_id");
        $stmt->bindValue(':invoice_id', $invoiceId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $payments = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $payments[] = $row;
        }
        return $payments;
    }
}