<?php
// In your InvoiceController.php
require_once 'models/Invoice.php';

class InvoiceController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create($client) {
        $client = $client;
        include 'views/invoice/create.php';
    }
}
