<?php
include 'db.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No product ID provided for deletion.";
}
?>
