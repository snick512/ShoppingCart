<?php

include "db.php";

$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the JSON data sent from the client
$data = json_decode(file_get_contents('php://input'), true);

$name = $conn->real_escape_string($data['name']);
$address = $conn->real_escape_string($data['address']);
$zip = $conn->real_escape_string($data['zip']);
$cart = $data['cart'];

// Insert each product in the cart into the database
foreach ($cart as $item) {
    $sku = $conn->real_escape_string($item['sku']);
    $sql = "INSERT INTO orders (name, address, zip, sku) VALUES ('$name', '$address', '$zip', '$sku')";
    if (!$conn->query($sql)) {
        echo json_encode(["status" => "error", "message" => "Failed to save order"]);
        $conn->close();
        exit;
    }
}

// If successful
echo json_encode(["status" => "success", "message" => "Order placed successfully"]);

$conn->close();
?>
