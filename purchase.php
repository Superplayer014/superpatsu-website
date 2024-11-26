<?php
session_start();
include 'db.php'; // Include your database connection file

// Check if the client is logged in; otherwise, redirect to login page
if (!isset($_SESSION['client_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

// Retrieve client ID from session
$client_id = $_SESSION['client_id'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy']) && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    // Validate the quantity
    if ($quantity > 0) {
        // Check if the product exists and fetch its available quantity
        $sql_product = "SELECT quantity FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($sql_product);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->bind_result($available_quantity);
        $stmt->fetch();
        $stmt->close();

        // Check if there's enough stock available
        if ($quantity <= $available_quantity) {
            // Insert the purchase into the purchases table
            $sql_insert = "INSERT INTO purchases (client_id, product_id, quantity, date) VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param("iii", $client_id, $product_id, $quantity);

            if ($stmt->execute()) {
                // Update the product quantity in the products table
                $new_quantity = $available_quantity - $quantity;
                $sql_update = "UPDATE products SET quantity = ? WHERE product_id = ?";
                $stmt = $conn->prepare($sql_update);
                $stmt->bind_param("ii", $new_quantity, $product_id);
                $stmt->execute();
                $stmt->close();

                // Redirect to product dashboard with a success message
                echo "<script>alert('Purchase successful!'); window.location.href='product_dashboard.php';</script>";
            } else {
                echo "<script>alert('Error processing purchase. Please try again.'); window.location.href='product_dashboard.php';</script>";
            }
        } else {
            echo "<script>alert('Insufficient stock. Available quantity: " . $available_quantity . "'); window.location.href='product_dashboard.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid quantity.'); window.location.href='product_dashboard.php';</script>";
    }
} else {
    // Redirect to the product dashboard if accessed directly
    header('Location: product_dashboard.php');
    exit();
}

$conn->close();
?>
