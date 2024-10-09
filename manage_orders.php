<?php

include "db.php";  // Include the database connection

// Create a connection to the MySQL database
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all orders from the `orders` table
$sql = "SELECT id, name, address, zip, sku FROM orders";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <h2>Manage Orders</h2>
    
    <!-- Export Orders -->
    <button class="btn btn-primary mb-3" onclick="exportOrders()">Export Orders to CSV</button>
    <!-- Delete All Orders -->
    <button class="btn btn-danger mb-3" onclick="deleteAllOrders()">Delete All Orders</button>

    <!-- Table to display orders -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Name</th>
                <th>Address</th>
                <th>ZIP</th>
                <th>SKU</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="ordersTable">
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr data-id='{$row['id']}'>
                        <td>{$row['id']}</td>
                        <td contenteditable='true' class='editable' data-field='name'>{$row['name']}</td>
                        <td contenteditable='true' class='editable' data-field='address'>{$row['address']}</td>
                        <td contenteditable='true' class='editable' data-field='zip'>{$row['zip']}</td>
                        <td contenteditable='true' class='editable' data-field='sku'>{$row['sku']}</td>
                        <td>
                            <button class='btn btn-danger btn-sm' onclick='deleteOrder({$row['id']})'>Delete</button>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No orders found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    // Update order when field is edited
    $('.editable').on('blur', function() {
        var id = $(this).closest('tr').data('id');
        var field = $(this).data('field');
        var value = $(this).text();
        
        $.ajax({
            url: 'update_order.php',
            method: 'POST',
            data: { id: id, field: field, value: value },
            success: function(response) {
                alert(response);
            }
        });
    });

    // Delete a specific order
    function deleteOrder(id) {
        if (confirm('Are you sure you want to delete this order?')) {
            $.ajax({
                url: 'delete_order.php',
                method: 'POST',
                data: { id: id },
                success: function(response) {
                    alert(response);
                    location.reload();
                }
            });
        }
    }

    // Delete all orders
    function deleteAllOrders() {
        if (confirm('Are you sure you want to delete all orders?')) {
            $.ajax({
                url: 'delete_all_orders.php',
                method: 'POST',
                success: function(response) {
                    alert(response);
                    location.reload();
                }
            });
        }
    }

    // Export orders to CSV
    function exportOrders() {
        window.location.href = 'export_orders.php';
    }
</script>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
