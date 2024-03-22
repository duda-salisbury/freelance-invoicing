<?php

class InvoiceItem {
    private $db;
    // methods: __construct, save, find, exists, create, update, delete

    public function __construct($db) {
        $this->db = $db;
    }

    // Save or update an invoice item
    public function save($id, $invoiceId, $description, $quantity, $unitPrice, $total) {
        if ($this->exists($id)) {
            return $this->update($id, $description, $quantity, $unitPrice, $total);
        } else {
            return $this->create($invoiceId, $description, $quantity, $unitPrice, $total);
        }
    }

    // Find an invoice item by ID
    public function find($invoiceItemId) {
        $stmt = $this->db->prepare("SELECT * FROM invoice_items WHERE id = :id");
        $stmt->bindValue(':id', $invoiceItemId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC);
    }

    // Check if an invoice item exists for a user with a given name

    private function exists($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM invoice_items WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        return $result->fetchArray()[0] > 0;
    }


    // Create a new invoice item

    private function create($invoiceId, $description, $quantity, $unitPrice, $total) {
        $stmt = $this->db->prepare("INSERT INTO invoice_items (invoice_id, description, quantity, unit_price, total) VALUES (:invoice_id, :description, :quantity, :unit_price, :total)");
        $stmt->bindValue(':invoice_id', $invoiceId, SQLITE3_INTEGER);
        $stmt->bindValue(':description', $description, SQLITE3_TEXT);
        $stmt->bindValue(':quantity', $quantity, SQLITE3_INTEGER);
        $stmt->bindValue(':unit_price', $unitPrice, SQLITE3_FLOAT);
        $stmt->bindValue(':total', $total, SQLITE3_FLOAT);
        $stmt->execute();
        return $this->db->lastInsertRowID();
    }

    // Update an invoice item

    private function update($id, $description, $quantity, $unitPrice, $total) {
        $stmt = $this->db->prepare("UPDATE invoice_items SET description = :description, quantity = :quantity, unit_price = :unit_price, total = :total WHERE id = :id");
        $stmt->bindValue(':description', $description, SQLITE3_TEXT);
        $stmt->bindValue(':quantity', $quantity, SQLITE3_INTEGER);
        $stmt->bindValue(':unit_price', $unitPrice, SQLITE3_FLOAT);
        $stmt->bindValue(':total', $total, SQLITE3_FLOAT);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        return $stmt->execute();
    }

    // Delete an invoice item

    public function delete($invoiceItemId) {
        $stmt = $this->db->prepare("DELETE FROM invoice_items WHERE id = :id");
        $stmt->bindValue(':id', $invoiceItemId, SQLITE3_INTEGER);
        return $stmt->execute();
    }

    
  
}

?>
