<?php
// In your InvoiceController.php
require_once 'models/Invoice.php';
require_once 'models/Client.php';
require_once 'models/InvoiceItem.php';

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

    public function store($id, $clientId, $invoiceDate, $dueDate, $total, $items) {


        $invoice = new Invoice($this->db, $id);
        $invoice->clientId = $clientId;
        $invoice->invoiceDate = $invoiceDate;
        $invoice->dueDate = $dueDate;
        $invoice->total = $total;
        $invoice->status = 'draft';
        $invoice->userId = "1";

        $id = $invoice->save();
            
        foreach ($items as $item) {
            $invoiceItem = new InvoiceItem($this->db);
            $invoiceItem->invoiceId = $id;
            $invoiceItem->description = $item['description'];
            $invoiceItem->quantity = $item['quantity'];
            $invoiceItem->unitPrice = $item['unit_price'];
            $invoiceItem->setTotal($invoiceItem->getTotal());
            $invoiceItem->save();
        }

        return $id;
        
    }

    public function delete($id) {
        $invoice = new Invoice($this->db, $id);
        $invoice->delete();
        header('Location: /client/show/' . $invoice->clientId);
    }

    public function show($id) {
        $invoice = new Invoice($this->db, $id);
        $invoiceItems = $invoice->getItems() ?? [];
        $client = $invoice->getClient();
        include 'views/invoice/show.php';
    }
}
