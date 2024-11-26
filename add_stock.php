<?php 
include 'db.php'; // Ensure the path to 'db.php' is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $stock_quantity = $_POST['stock_quantity'];

    // Update the stock in the database
    $sql = "UPDATE products SET quantity = quantity + ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $stock_quantity, $product_id);

    if ($stmt->execute()) {
        echo "Stock added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: admin_dashboard.php"); // Redirect back to products dashboard
    exit();
}
?>
