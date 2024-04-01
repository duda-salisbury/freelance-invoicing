<?php
require_once 'InvoiceItem.php';

class Invoice {
    private $db;
    private $id;
    public $userId;
    public $clientId;
    public $invoiceDate;
    public $dueDate;
    public $total;
    public $status;

    // an array of InvoiceItem objects
    public $items;

    public function __construct($db, $id=0) {
        $this->db = $db;

        if ($id > 0) {
            $this->id = $id;
            $stmt = $this->db->prepare("SELECT * FROM invoices WHERE id = :id");
            $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
            $result = $stmt->execute();
            $row = $result->fetchArray(SQLITE3_ASSOC);
            $this->userId = 0;
            $this->clientId = $row['client_id'];
            $this->invoiceDate = $row['invoice_date'];
            $this->dueDate = $row['due_date'];
            $this->total = $row['total_amount'];
            $this->status = $row['status'];
            $this->items = $this->getItems();

        } else {
            $this->id = 0;
            $this->clientId = 0;
            $this->invoiceDate = '';
            $this->dueDate = '';
            $this->status = '';
            $this->total = 0;
            $this->userId = 0;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getClient() {
        return (new Client($this->db, $this->clientId));
    }

    // Get all items for an invoice
    public function getItems() {
        $stmt = $this->db->prepare("SELECT * FROM invoice_items WHERE invoice_id = :invoice_id");
 
        $stmt->bindValue(':invoice_id', $this->id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $items = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $items[] = new InvoiceItem($this->db, $row['id']);
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
            die("Exists");
            return $this->update();
        } else {
            return $this->create();
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
    private function create() {
        $invoiceDate = $this->invoiceDate;
        $dueDate = $this->dueDate;
        $total = $this->total;
        $clientId = $this->clientId;
        $userId = $this->userId;
        $status = $this->status;
        
        $sql = "INSERT INTO invoices (client_id, user_id, invoice_date, due_date, total_amount, status) 
                VALUES (:client_id, :user_id, :invoice_date, :due_date, :total, :status)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $this->userId, SQLITE3_INTEGER);
        $stmt->bindValue(':client_id', $clientId, SQLITE3_INTEGER);
        $stmt->bindValue(':invoice_date', $invoiceDate, SQLITE3_TEXT);
        $stmt->bindValue(':due_date', $dueDate, SQLITE3_TEXT);
        $stmt->bindValue(':status', $status, SQLITE3_TEXT);
        $stmt->bindValue(':total', $total, SQLITE3_FLOAT);

        // execute the statment and die if it fails
        if (!$stmt->execute()) {
            die("Error creating invoice");
        } else {
            return $this->db->lastInsertRowID();    
        }


        
    }

    // Update an invoice
    private function update() {
        $id = $this->id;
        $clientId = $this->clientId;
        $invoiceDate = $this->invoiceDate;
        $dueDate = $this->dueDate;
        $total = $this->total;
        $status = $this->status;
        $userId = 1;
        
        $query = "UPDATE invoices SET client_id = :client_id, user_id = :user_id, invoice_date = :invoice_date, due_date = :due_date, total_amount = :total, status = :status WHERE id = :id";
        $stmt = $this->db->prepare($query);


        $stmt->bindValue(':user_id', $userId, SQLITE3_INTEGER);
        $stmt->bindValue(':client_id', $clientId, SQLITE3_INTEGER);
        $stmt->bindValue(':invoice_date', $invoiceDate, SQLITE3_TEXT);
        $stmt->bindValue(':due_date', $dueDate, SQLITE3_TEXT);
        $stmt->bindValue(':total', $total, SQLITE3_FLOAT);
        $stmt->bindValue(':status', $status, SQLITE3_TEXT);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        return $stmt->execute();
    }

    // Delete an invoice
    public function delete() {
        $invoiceId = $this->id;
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
