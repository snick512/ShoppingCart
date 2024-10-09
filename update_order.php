<?php
include "db.php";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_POST['id'];
$field = $_POST['field'];
$value = $_POST['value'];

$sql = "UPDATE orders SET $field = '$value' WHERE id = $id";
if ($conn->query($sql) === TRUE) {
    echo "Order updated successfully.";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
