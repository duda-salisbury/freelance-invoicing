<?php

require_once 'models/Client.php';

class ClientController {
    private $db;
    private $clientModel;

    public function __construct($db) {
        $this->db = $db;
        $this->clientModel = new Client($db);
    }

    // List all clients
    public function index() {
        $stmt = $this->db->prepare("SELECT * FROM clients");
        $result = $stmt->execute();
        $clients = array();
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $clients[] = $row;
        }

        include 'views/client/list.php';
    }

    // Create a new client
    public function create() {
        include 'views/client/create.php';
    }

    public function store($id=0,$user_id, $name, $email, $billing_address){
        $client = new Client($this->db, $id);
        $client->userId = $user_id;
        $client->name = $name;
        $client->email = $email;
        $client->billingAddress = $billing_address;
        $client->save();


        header('Location: /client/list');
    }

    // Show a specific client
    public function show($clientId) {
        $client = new Client($this->db, $clientId);
        $invoices = $client->getInvoices();
        include 'views/client/show.php';
    }

    // Update a client
    public function edit($id) {
        $clientId = $id;
        $client = new Client($this->db, $clientId);

        
        include 'views/client/edit.php';
    }

    // Delete a client
    public function destroy($clientId) {
        $this->clientModel->delete($clientId);
        header('Location: /client/list');
    }
}

?>
