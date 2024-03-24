<?php

/** DashboardController */
require_once 'models/Client.php';
require_once 'models/Invoice.php';
require_once 'models/InvoiceItem.php';

require_once 'controllers/ClientController.php';
require_once 'controllers/InvoiceController.php';
require_once 'controllers/InvoiceItemController.php';


class DashboardController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index()
    {
        $client = new Client($this->db);
        $invoice = new Invoice($this->db);

        $clients = $client->all();
        $invoices = $invoice->all();

        include 'views/dashboard/index.php';
    }
}