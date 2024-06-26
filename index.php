<?php

// Include necessary files
require_once 'controllers/ClientController.php';
require_once 'controllers/InvoiceController.php';
require_once 'controllers/InvoiceItemController.php';
require_once 'controllers/DashboardController.php';
require_once 'models/Client.php';
require_once 'models/Invoice.php';

// Initialize SQLite database
$db = new SQLite3('invoices.db');

// Initialize ClientController
$clientController = new ClientController($db);

// Get the requested URL
$requestUri = $_SERVER['REQUEST_URI'];

// Remove query string from the URL
$requestUri = strtok($requestUri, '?');

// Remove leading/trailing slashes and explode URL into segments
$segments = explode('/', trim($requestUri, '/'));

// Define custom routes
switch ($segments[0]) {
    case '':
        // Handle home route
        $dashboardController = new DashboardController($db);
        $dashboardController->index();
        
        break;

    case 'client':
        // Handle client routes
        switch ($segments[1]) {
            case 'list':
                $clientController->index();
                break;
            case 'create':
                $clientController->create();
                break;
            case 'store':
                    $id = $_POST['id'] ?? 0;
                    $user_id = $_POST['user_id'] ?? 1;
                    $name = $_POST['name'];
                    $email = $_POST['email'];
                    $billing_address = $_POST['billing_address'];

                    $clientController->store($id,$user_id, $name, $email, $billing_address);

                break;
            
            case 'show':
                $clientController->show($segments[2]);
                break;
            case 'edit':
                $clientController->edit($segments[2]);
                break;
            case 'destroy':
                $clientController->destroy($segments[2]);
                break;
            default:
                // Handle unknown client routes
                header("HTTP/1.0 404 Not Found");
                echo '404 Not Found';
                break;
        }
        break;
    case 'invoice':
        // Handle invoice routes
        switch ($segments[1]) {
            case 'create':
                $clientId = $segments[2];
                $client = new Client($db, $clientId);
                $invoiceController = new InvoiceController($db);
                $invoiceController->create($client);
                break;
            
            case 'destroy':
                $id = $segments[2];
                $invoiceController = new InvoiceController($db);
                $invoiceController->delete($id);
                break;

            case 'store':
                $id = $_POST['id'] ?? 0;
                $clientId = $_POST['client_id'];
                $invoiceDate = $_POST['invoice_date'];
                $dueDate = $_POST['due_date'];
                $total = $_POST['total_amount'];

               

                $items = [];

                for ($i = 0; $i < count($_POST['description']); $i++) {
                    $items[] = [
                        'item_id' => $_POST['item_id'][$i],
                        'description' => $_POST['description'][$i],
                        'quantity' => $_POST['quantity'][$i],
                        'unit_price' => $_POST['unit_price'][$i],
                    ];
                }

                $invoiceController = new InvoiceController($db);
                $success = $invoiceController->store($id, $clientId, $invoiceDate, $dueDate, $total, $items);




                // Redirect to the invoice show page
                if ($success) {
                    header("Location: /invoice/show/$success");
                }

                break;

            case 'show':
                $invoiceController = new InvoiceController($db);
                // load the view
                $invoiceController->show($segments[2]);
                break;
            case 'edit':
                $invoiceController = new InvoiceController($db);
                $invoiceController->edit($segments[2]);
                break;
                
            default:
                // Handle unknown invoice routes
                header("HTTP/1.0 404 Not Found");
                echo '404 Not Found';
                break;
        }
        break;
    case 'invoiceItem':
        // Handle invoice item routes
        switch ($segments[1]) {
            case 'create':
                $invoiceId = $segments[2];
                $invoiceItemController = new InvoiceItemController($db);
                $invoiceItemController->create($invoiceId);
                break;
            case 'store':
                $id = $_POST['id'] ?? 0;
                $invoiceId = $_POST['invoice_id'];
                $description = $_POST['description'];
                $quantity = $_POST['quantity'];
                $unitPrice = $_POST['unit_price'];
                $total = $_POST['total_amount'];

                $invoiceItemController = new InvoiceItemController($db);
                $invoiceItemController->store($id, $invoiceId, $description, $quantity, $unitPrice, $total);
                break;
            case 'edit':
                $id = $segments[2];
                $invoiceItemController = new InvoiceItemController($db);
                $invoiceItemController->edit($id);
                break;
            case 'update':
                $id = $_POST['id'];
                $invoiceId = $_POST['invoice_id'];
                $description = $_POST['description'];
                $quantity = $_POST['quantity'];
                $unitPrice = $_POST['unit_price'];
                $total = $_POST['total'];

                $invoiceItemController = new InvoiceItemController($db);
                $invoiceItemController->update($id, $invoiceId, $description, $quantity, $unitPrice, $total);
                break;
            case 'destroy':
                $id = $segments[2];
                $invoiceId = $segments[3];
                $invoiceItemController = new InvoiceItemController($db);
                $invoiceItemController->destroy($id, $invoiceId);
                break;
            default:
                // Handle unknown invoice item routes
                header("HTTP/1.0 404 Not Found");
                echo '404 Not Found';
                break;
        }
        break;


    default:
        // Handle unknown routes
        header("HTTP/1.0 404 Not Found");
        echo '404 Not Found';
        break;
}

// Close database connection
$db->close();
