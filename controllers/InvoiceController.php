<?php
// In your InvoiceController.php
require_once 'models/Invoice.php';

class InvoiceController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create($client = null) {
        $client = $client;
        $clients = (new Client($this->db))->all();
        include 'views/invoice/create.php';
    }

    public function store($id, $clientId, $invoiceDate, $dueDate, $total) {
        $invoice = new Invoice($this->db);
        $invoice->save($id, $clientId, $invoiceDate, $dueDate, $total);

        // redirect to the invoice show page
        header('Location: /invoice/show/' . $invoice->id);
    }

    public function show($id) {
        $invoice = (new Invoice($this->db))->find($id);
        $invoiceItems = (new InvoiceItem($this->db))->all($id);
        include 'views/invoice/show.php';
    }
}
