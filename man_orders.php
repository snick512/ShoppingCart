<?php

include "db.php";

header('Content-Type: application/json; charset=utf-8');

/* Weds Oct 9, 2024 3.03 am

View all orders:
    
    curl -X GET https://teamclapback.com/man_orders.php


View specific order: 

    curl -X GET https://teamclapback.com/man_orders.php?id=1


Add new order (POST): 
    
    curl -X POST -H "Content-Type: application/json" \
-d '{
    "name": "John Doe",
    "address": "123 Main St",
    "zip": "12345",
    "cart": [{"sku": "prod123"}, {"sku": "prod456"}]
}' https://teamclapback.com/man_orders.php


Update an existing order: 

    curl -X PUT -H "Content-Type: application/json" \
-d '{
    "name": "Jane Doe",
    "address": "456 New St",
    "zip": "98765",
    "sku": "prod789"
}' https://teamclapback.com/man_orders.php?id=1


Delete: 

    curl -X DELETE https://teamclapback.com/man_orders.php?id=1





*/
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Handling GET, POST, PUT, DELETE requests
switch ($method) {
    case 'GET':
        // Fetch all orders or a specific order by ID
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = "SELECT * FROM orders WHERE id = $id";
        } else {
            $sql = "SELECT * FROM orders";
        }

        $result = $conn->query($sql);
        $orders = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
        }

        echo json_encode(["status" => "success", "data" => $orders]);
        break;

    case 'POST':
        // Add a new order
        $data = json_decode(file_get_contents('php://input'), true);

        $name = $conn->real_escape_string($data['name']);
        $address = $conn->real_escape_string($data['address']);
        $zip = $conn->real_escape_string($data['zip']);
        $cart = $data['cart'];

        foreach ($cart as $item) {
            $sku = $conn->real_escape_string($item['sku']);
            $sql = "INSERT INTO orders (name, address, zip, sku) VALUES ('$name', '$address', '$zip', '$sku')";
            if (!$conn->query($sql)) {
                echo json_encode(["status" => "error", "message" => "Failed to save order"]);
                $conn->close();
                exit;
            }
        }

        echo json_encode(["status" => "success", "message" => "Order added successfully"]);
        break;

    case 'PUT':
        // Update an existing order
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $data = json_decode(file_get_contents('php://input'), true);

            $name = $conn->real_escape_string($data['name']);
            $address = $conn->real_escape_string($data['address']);
            $zip = $conn->real_escape_string($data['zip']);
            $sku = $conn->real_escape_string($data['sku']);

            $sql = "UPDATE orders SET name='$name', address='$address', zip='$zip', sku='$sku' WHERE id = $id";

            if ($conn->query($sql)) {
                echo json_encode(["status" => "success", "message" => "Order updated successfully"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to update order"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Order ID is required for updating"]);
        }
        break;

    case 'DELETE':
        // Delete an order by ID
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = "DELETE FROM orders WHERE id = $id";

            if ($conn->query($sql)) {
                echo json_encode(["status" => "success", "message" => "Order deleted successfully"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to delete order"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Order ID is required for deletion"]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Invalid request method"]);
        break;
}

$conn->close();
?>
