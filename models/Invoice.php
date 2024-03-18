<?php

class Invoice {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Create a new invoice
    public function createInvoice($userId, $clientId, $invoiceNumber, $invoiceDate, $dueDate, $totalAmount, $status) {
        $stmt = $this->db->prepare("INSERT INTO invoices (user_id, client_id, invoice_number, invoice_date, due_date, total_amount, status) VALUES (:user_id, :client_id, :invoice_number, :invoice_date, :due_date, :total_amount, :status)");
        $stmt->bindValue(':user_id', $userId, SQLITE3_INTEGER);
        $stmt->bindValue(':client_id', $clientId, SQLITE3_INTEGER);
        $stmt->bindValue(':invoice_number', $invoiceNumber, SQLITE3_TEXT);
        $stmt->bindValue(':invoice_date', $invoiceDate, SQLITE3_TEXT);
        $stmt->bindValue(':due_date', $dueDate, SQLITE3_TEXT);
        $stmt->bindValue(':total_amount', $totalAmount, SQLITE3_FLOAT);
        $stmt->bindValue(':status', $status, SQLITE3_TEXT);
        $stmt->execute();
        return $this->db->lastInsertRowID();
    }

    // Retrieve an invoice by ID
    public function getInvoiceById($invoiceId) {
        $stmt = $this->db->prepare("SELECT * FROM invoices WHERE id = :id");
        $stmt->bindValue(':id', $invoiceId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC);
    }

    // Update an invoice's information
    public function updateInvoice($invoiceId, $invoiceNumber, $invoiceDate, $dueDate, $totalAmount, $status) {
        $stmt = $this->db->prepare("UPDATE invoices SET invoice_number = :invoice_number, invoice_date = :invoice_date, due_date = :due_date, total_amount = :total_amount, status = :status WHERE id = :id");
        $stmt->bindValue(':invoice_number', $invoiceNumber, SQLITE3_TEXT);
        $stmt->bindValue(':invoice_date', $invoiceDate, SQLITE3_TEXT);
        $stmt->bindValue(':due_date', $dueDate, SQLITE3_TEXT);
        $stmt->bindValue(':total_amount', $totalAmount, SQLITE3_FLOAT);
        $stmt->bindValue(':status', $status, SQLITE3_TEXT);
        $stmt->bindValue(':id', $invoiceId, SQLITE3_INTEGER);
        return $stmt->execute();
    }

    // Delete an invoice
    public function deleteInvoice($invoiceId) {
        $stmt = $this->db->prepare("DELETE FROM invoices WHERE id = :id");
        $stmt->bindValue(':id', $invoiceId, SQLITE3_INTEGER);
        return $stmt->execute();
    }
}

?>
