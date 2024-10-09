<?php
include "db.php";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "DELETE FROM orders";
if ($conn->query($sql) === TRUE) {
    echo "All orders deleted successfully.";
} else {
    echo "Error deleting orders: " . $conn->error;
}

$conn->close();
?>
