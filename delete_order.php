<?php
include "db.php";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_POST['id'];

$sql = "DELETE FROM orders WHERE id = $id";
if ($conn->query($sql) === TRUE) {
    echo "Order deleted successfully.";
} else {
    echo "Error deleting order: " . $conn->error;
}

$conn->close();
?>
