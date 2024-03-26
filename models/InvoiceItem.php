<?php

class InvoiceItem {
    private $db;
    private $id;
    public $invoiceId;
    public $description;
    public $quantity;
    public $unitPrice;
    private $total;
    // methods: __construct, save, find, exists, create, update, delete

    public function __construct($db, $id=0) {
        $this->db = $db;
        if ($id > 0) {
            $this->id = $id;
            $stmt = $this->db->prepare("SELECT * FROM invoice_items WHERE id = :id");
            $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
            $result = $stmt->execute();
            $row = $result->fetchArray(SQLITE3_ASSOC);
            $this->invoiceId = $row['invoice_id'];
            $this->description = $row['description'];
            $this->quantity = $row['quantity'];
            $this->unitPrice = $row['unit_price'];
        } else {
            $this->id = 0;
            $this->invoiceId = 0;
            $this->description = '';
            $this->quantity = 0;
            $this->unitPrice = 0;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setTotal($total) {
        $this->total = $total;
    }

    public function getTotal() {
        return $this->quantity * $this->unitPrice;
    }

    // Save or update an invoice item
    public function save() {
        $id = $this->id;
        if ($this->exists($id)) {
            return $this->update();
        } else {
            return $this->create();
        }
    }


    // Check if an invoice item exists for a user with a given name

    private function exists($id) {
        
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM invoice_items WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        return $result->fetchArray()[0] > 0;
    }


    // Create a new invoice item

    private function create() {

        $invoiceId = $this->invoiceId;
        $description = $this->description;
        $quantity = $this->quantity;
        $unitPrice = $this->unitPrice;


        $stmt = $this->db->prepare("INSERT INTO invoice_items (invoice_id, description, quantity, unit_price, total) VALUES (:invoice_id, :description, :quantity, :unit_price, :total)");
        $stmt->bindValue(':invoice_id', $invoiceId, SQLITE3_INTEGER);
        $stmt->bindValue(':description', $description, SQLITE3_TEXT);
        $stmt->bindValue(':quantity', $quantity, SQLITE3_INTEGER);
        $stmt->bindValue(':unit_price', $unitPrice, SQLITE3_FLOAT);
        $stmt->bindValue(':total', $this->getTotal(), SQLITE3_FLOAT);
        $stmt->execute();

        // debug if the insert was successful by spitting out the last inserted row details

        return $this->db->lastInsertRowID();
    }

    // Update an invoice item

    private function update() {
        $id = $this->id;
        $description = $this->description;
        $quantity = $this->quantity;
        $unitPrice = $this->unitPrice;
        
        $stmt = $this->db->prepare("UPDATE invoice_items SET description = :description, quantity = :quantity, unit_price = :unit_price WHERE id = :id");
        $stmt->bindValue(':description', $description, SQLITE3_TEXT);
        $stmt->bindValue(':quantity', $quantity, SQLITE3_INTEGER);
        $stmt->bindValue(':unit_price', $unitPrice, SQLITE3_FLOAT);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        return $stmt->execute();
    }

    // Delete an invoice item

    public function delete($invoiceItemId) {
        $stmt = $this->db->prepare("DELETE FROM invoice_items WHERE id = :id");
        $stmt->bindValue(':id', $invoiceItemId, SQLITE3_INTEGER);
        return $stmt->execute();
    }

    // Get all invoice items for an invoice
    public function all($invoiceId) {
        $stmt = $this->db->prepare("SELECT * FROM invoice_items WHERE invoice_id = :invoice_id");
        $stmt->bindValue(':invoice_id', $invoiceId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $invoiceItems = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $invoiceItems[] = $row;
        }
        return $invoiceItems;
    }
}

?>
