<?php 

/** InvoiceItemController */

require_once 'models/InvoiceItem.php';

class InvoiceItemController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create($invoice) {
        $invoice = $invoice;
        include 'views/invoice_item/create.php';
    }

    public function store($id, $invoiceId, $description, $quantity, $unitPrice, $total) {
        $invoiceItem = new InvoiceItem($this->db);
        $invoiceItem->save($id, $invoiceId, $description, $quantity, $unitPrice, $total);
        header('Location: /invoice/show/' . $invoiceId);
    }

    public function edit($id) {
        $invoiceItem = new InvoiceItem($this->db);
        $invoiceItem = $invoiceItem->find($id);
        include 'views/invoice_item/edit.php';
    }

    public function update($id, $invoiceId, $description, $quantity, $unitPrice, $total) {
        $invoiceItem = new InvoiceItem($this->db);
        $invoiceItem->save($id, $invoiceId, $description, $quantity, $unitPrice, $total);
        header('Location: /invoice/show/' . $invoiceId);
    }

    public function destroy($id, $invoiceId) {
        $invoiceItem = new InvoiceItem($this->db);
        $invoiceItem->delete($id);
        header('Location: /invoice/show/' . $invoiceId);
    }
}