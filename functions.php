<?php
include 'db.php';

function fetchProducts($conn) {
    $sql = "SELECT * FROM products"; // Assume you have a products table
    $result = $conn->query($sql);
    return $result;
}

function createOrder($client_id, $product_name, $price, $quantity, $conn) {
    $sql = "INSERT INTO orders (client_id, product_name, price, quantity) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("isdi", $client_id, $product_name, $price, $quantity);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            return "Order created successfully!";
        } else {
            return "Error creating order: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        return "Error preparing statement: " . $conn->error;
    }
}
?>
