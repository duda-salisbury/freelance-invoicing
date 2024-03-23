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

    public function store($id, $clientId, $invoiceDate, $dueDate, $total) {
        $invoice = new Invoice($this->db);
        $invoice->save($id, $clientId, $invoiceDate, $dueDate, $total);

        // loop through the invoice items and save them
        $invoiceItemController = new InvoiceItemController($this->db);
        

        header('Location: /client/show/' . $clientId);
    }

    public function show($id) {
        $invoice = (new Invoice($this->db))->find($id);
        $invoiceItems = (new InvoiceItem($this->db))->all($id);
        include 'views/invoice/show.php';
    }
}
