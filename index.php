<?php

// Include necessary files
require_once 'controllers/ClientController.php';

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
    default:
        // Handle unknown routes
        header("HTTP/1.0 404 Not Found");
        echo '404 Not Found';
        break;
}

// Close database connection
$db->close();
