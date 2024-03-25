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


        $invoice = new Invoice($this->db, $id);
        $invoice->clientId = $clientId;
        $invoice->invoiceDate = $invoiceDate;
        $invoice->dueDate = $dueDate;
        $invoice->total = $total;
        $invoice->status = 'draft';
        $invoice->userId = "1";



        return $invoice->save($id);


    }

    public function show($id) {
        $invoice = new Invoice($this->db, $id);
        $invoiceItems = $invoice->getItems();
        $client = $invoice->getClient();
        include 'views/invoice/show.php';
    }
}
