-- Create database 
CREATE DATABASE shopping_cart;

-- Create orders table to store the user’s information and order details
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    zip VARCHAR(10) NOT NULL,
    sku VARCHAR(50) NOT NULL
);
