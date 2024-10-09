<?php
include "db.php";

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="orders.csv"');

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$output = fopen('php://output', 'w');
fputcsv($output, array('Order ID', 'Name', 'Address', 'ZIP', 'SKU'));

$sql = "SELECT id, name, address, zip, sku FROM orders";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
}

fclose($output);
$conn->close();
?>
