<?php

class Invoice {
    private $db;
    private $id;
    public $clientId;
    public $invoiceDate;
    public $dueDate;
    public $total;
    public $items;

    public function __construct($db, $id=0) {
        $this->db = $db;

        if ($id > 0) {
            $this->id = $id;
            $stmt = $this->db->prepare("SELECT * FROM invoices WHERE id = :id");
            $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
            $result = $stmt->execute();
            $row = $result->fetchArray(SQLITE3_ASSOC);
            $this->clientId = $row['client_id'];
            $this->invoiceDate = $row['invoice_date'];
            $this->dueDate = $row['due_date'];
            $this->total = $row['total'];
            $this->items = $this->getItems();
        } else {
            $this->id = 0;
            $this->clientId = 0;
            $this->invoiceDate = '';
            $this->dueDate = '';
            $this->total = 0;
        }
    }

    public function getId() {
        return $this->id;
    }

    // Get all items for an invoice
    public function getItems() {
        $stmt = $this->db->prepare("SELECT * FROM invoice_items WHERE invoice_id = :invoice_id");
        $stmt->bindValue(':invoice_id', $this->id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $items = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $items[] = $row;
        }
        return $items;
    }

    // Save or update an invoice
    public function save() {
        $id = $this->id;
        $clientId = $this->clientId;
        $invoiceDate = $this->invoiceDate;
        $dueDate = $this->dueDate;
        $total = $this->total;
        $items = $this->items;
        if ($this->exists($id)) {
            return $this->update($id, $clientId, $invoiceDate, $dueDate, $total, $items);
        } else {
            echo "Creating invoice";

            return $this->create($clientId, $invoiceDate, $dueDate, $total, $items);
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
