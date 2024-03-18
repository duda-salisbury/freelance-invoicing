<?php

class InvoiceItem {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Add a new invoice item
    public function addInvoiceItem($invoiceId, $description, $unitPrice, $quantity, $total) {
        $stmt = $this->db->prepare("INSERT INTO invoice_items (invoice_id, description, unit_price, quantity, total) VALUES (:invoice_id, :description, :unit_price, :quantity, :total)");
        $stmt->bindValue(':invoice_id', $invoiceId, SQLITE3_INTEGER);
        $stmt->bindValue(':description', $description, SQLITE3_TEXT);
        $stmt->bindValue(':unit_price', $unitPrice, SQLITE3_FLOAT);
        $stmt->bindValue(':quantity', $quantity, SQLITE3_INTEGER);
        $stmt->bindValue(':total', $total, SQLITE3_FLOAT);
        $stmt->execute();
        return $this->db->lastInsertRowID();
    }

    // Retrieve all invoice items for an invoice
    public function getInvoiceItems($invoiceId) {
        $stmt = $this->db->prepare("SELECT * FROM invoice_items WHERE invoice_id = :invoice_id");
        $stmt->bindValue(':invoice_id', $invoiceId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $invoiceItems = array();
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $invoiceItems[] = $row;
        }
        return $invoiceItems;
    }

    // Update an invoice item
    public function updateInvoiceItem($itemId, $description, $unitPrice, $quantity, $total) {
        $stmt = $this->db->prepare("UPDATE invoice_items SET description = :description, unit_price = :unit_price, quantity = :quantity, total = :total WHERE id = :id");
        $stmt->bindValue(':description', $description, SQLITE3_TEXT);
        $stmt->bindValue(':unit_price', $unitPrice, SQLITE3_FLOAT);
        $stmt->bindValue(':quantity', $quantity, SQLITE3_INTEGER);
        $stmt->bindValue(':total', $total, SQLITE3_FLOAT);
        $stmt->bindValue(':id', $itemId, SQLITE3_INTEGER);
        return $stmt->execute();
    }

    // Delete an invoice item
    public function deleteInvoiceItem($itemId) {
        $stmt = $this->db->prepare("DELETE FROM invoice_items WHERE id = :id");
        $stmt->bindValue(':id', $itemId, SQLITE3_INTEGER);
        return $stmt->execute();
    }
}

?>
