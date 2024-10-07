<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h1 class="mt-4">Shopping Cart</h1>

    <!-- Demo Products -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Product 1</h5>
                    <p class="card-text">Price: $10</p>
                    <button class="btn btn-primary add-to-cart" data-sku="PROD1" data-name="Product 1" data-price="10">Add to Cart</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Product 2</h5>
                    <p class="card-text">Price: $20</p>
                    <button class="btn btn-primary add-to-cart" data-sku="PROD2" data-name="Product 2" data-price="20">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart -->
    <h2 class="mt-5">Your Cart</h2>
    <table class="table table-bordered" id="cartTable">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <!-- Checkout Form -->
    <h2 class="mt-5">Checkout</h2>
    <form id="checkoutForm">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" required>
        </div>
        <div class="mb-3">
            <label for="zip" class="form-label">ZIP Code</label>
            <input type="text" class="form-control" id="zip" required>
        </div>
        <button type="submit" class="btn btn-success">Submit Order</button>
    </form>
</div>

<script>
// JavaScript for Shopping Cart Functionality
let cart = [];

document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', () => {
        const sku = button.getAttribute('data-sku');
        const name = button.getAttribute('data-name');
        const price = button.getAttribute('data-price');
        
        cart.push({ sku, name, price });
        updateCartTable();
    });
});

function updateCartTable() {
    const cartTableBody = document.querySelector('#cartTable tbody');
    cartTableBody.innerHTML = '';

    cart.forEach((item, index) => {
        const row = `<tr>
            <td>${item.name}</td>
            <td>$${item.price}</td>
            <td><button class="btn btn-danger" onclick="removeFromCart(${index})">Remove</button></td>
        </tr>`;
        cartTableBody.innerHTML += row;
    });
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCartTable();
}

document.getElementById('checkoutForm').addEventListener('submit', async (event) => {
    event.preventDefault();

    const name = document.getElementById('name').value;
    const address = document.getElementById('address').value;
    const zip = document.getElementById('zip').value;

    const orderData = {
        name, 
        address, 
        zip, 
        cart
    };

    // Send order to the server
    const response = await fetch('process_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(orderData)
    });

    if (response.ok) {
        alert('Order placed successfully!');
        cart = [];
        updateCartTable();
        document.getElementById('checkoutForm').reset();
    } else {
        alert('Failed to place order');
    }
});
</script>

</body>
</html>
