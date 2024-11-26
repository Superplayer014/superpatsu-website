<?php
session_start();
include 'functions.php';

// Check if client_id is set in the session
if (!isset($_SESSION['client_id'])) {
    die("Client ID not found. Please log in.");
}

$client_id = $_SESSION['client_id'];
$products = fetchProducts($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS -->
    <style type="text/css">
        body {
    font-family: Arial, sans-serif;
}

.product {
    border: 1px solid #ddd;
    padding: 10px;
    margin: 10px;
    text-align: center;
}

button {
    background-color: #28a745; /* Green color */
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
}

button:hover {
    background-color: #218838; /* Darker green on hover */
}

    </style>
</head>
<body>
    <h1>Shopping Dashboard</h1>
    <?php if ($products->num_rows > 0): ?>
        <?php while ($product = $products->fetch_assoc()): ?>
            <div class="product">
                <h3><?php echo $product['product_name']; ?></h3>
                <p>Price: <?php echo $product['price']; ?></p>
                <form method="POST" action="purchase.php">
                    <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
                    <input type="hidden" name="product_name" value="<?php echo $product['product_name']; ?>">
                    <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" min="1" value="1">
                    <button type="submit">Purchase</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No products available.</p>
    <?php endif; ?>
</body>
</html>
