<?php 
include 'db.php'; // Ensure the path to 'db.php' is correct

// Get sorting option from URL or default to sorting by product name
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'product_name';
$order = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'desc' : 'asc';

// Fetch products with dynamic sorting
$sql = "SELECT * FROM products ORDER BY $sort_by $order";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Fetch recently signed-up clients (if necessary)
$sql_clients = "SELECT * FROM client ORDER BY signup_date DESC LIMIT 1000";
$result_clients = $conn->query($sql_clients);

if (!$result_clients) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products Dashboard</title>
    <style>
        /* General body styling with new gradient colors */
        body {
            background: linear-gradient(to right, #5A3F37, #8A6E5A); /* Soft Brown and Beige Gradient */
            color: #E0E0E0;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Header Styling with new gradient */
        header {
            background: linear-gradient(to right, #8A6E5A, #B09A72); /* Light brown gradient */
            color: #121212;
            padding: 20px;
            text-align: center;
            font-size: 28px;
            box-shadow: 0 4px 12px rgba(138, 110, 90, 0.5);
        }

        /* Sidebar Navigation */
        nav {
            background-color: #3A2A1D;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 40px;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.6);
        }

        nav a {
            color: #F4D03F; /* Gold color for links */
            text-decoration: none;
            font-size: 18px;
            padding: 16px;
            width: 100%;
            text-align: center;
            border-bottom: 1px solid #444;
            transition: 0.3s ease;
        }

        nav a:hover {
            background-color: #2F1D0D; /* Darker brown hover effect */
            color: white;
            box-shadow: 0 4px 12px rgba(255, 204, 0, 0.6);
        }

        /* Main content styling */
        .main-content {
            margin-left: 250px;
            padding: 40px;
        }

        /* Sorting Controls */
        .sort-controls {
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-end;
        }

        .sort-controls select {
            padding: 8px;
            margin-left: 10px;
            font-size: 16px;
            background-color: #3A2A1D;
            color: #F4D03F;
            border: 1px solid #444;
        }

        /* Product Cards Styling */
        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }

        .product-card {
            background-color: #4E3B31;
            border-radius: 8px;
            width: 250px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(255, 204, 0, 0.8);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-info {
            padding: 15px;
            text-align: center;
        }

        .product-info h4 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .product-info p {
            margin: 5px 0;
        }

        .product-caption {
            color: #D1D1D1; /* Light gray for captions */
            font-size: 14px;
        }

        .stock-quantity {
            color: #F4D03F;
            font-weight: bold;
        }

        /* Buttons Styling */
        .product-card button {
            width: 100%;
            padding: 10px;
            border: none;
            color: #fff;
            background-color: #F4D03F;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s ease;
            border-radius: 4px;
        }

        .product-card button:hover {
            background-color: #B09A72;
        }

        .edit-button {
            background-color: #B09A72; /* Edit button */
        }

        .delete-button {
            background-color: #F44336; /* Delete button */
        }

        /* Add stock form */
        .add-stock-form {
            margin-top: 10px;
            text-align: center;
        }

        .add-stock-form input {
            width: 80%;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #444;
            background-color: #4E3B31;
            color: #E0E0E0;
        }

        .add-stock-form button {
            background-color: #F4D03F;
            padding: 10px;
            width: 100%;
            border: none;
            color: #121212;
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .add-stock-form button:hover {
            background-color: #B09A72;
        }

        /* Table Styling for Clients */
        .clients-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .clients-table th, .clients-table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #444;
        }

        .clients-table th {
            background-color: #F4D03F;
            color: #121212;
            font-size: 18px;
        }

        .clients-table td {
            background-color: #4E3B31;
        }

        .clients-table tr:hover {
            background-color: #2F1D0D;
        }

    </style>
</head>
<body>
    <!-- Header -->
    <header>
        Dashboard
    </header>

    <!-- Navigation Bar -->
    <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="purchase_view.php">View Purchases</a> <!-- Link to view purchases -->
        <a href="add_product.php">Add Product</a>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Sorting Controls -->
        <div class="sort-controls">
            <label for="sort_by">Sort by: </label>
            <select id="sort_by" onchange="window.location.href = this.value;">
                <option value="?sort_by=product_name&order=asc" <?= ($sort_by == 'product_name' && $order == 'asc') ? 'selected' : '' ?>>Name (A-Z)</option>
                <option value="?sort_by=product_name&order=desc" <?= ($sort_by == 'product_name' && $order == 'desc') ? 'selected' : '' ?>>Name (Z-A)</option>
                <option value="?sort_by=price&order=asc" <?= ($sort_by == 'price' && $order == 'asc') ? 'selected' : '' ?>>Price (Low to High)</option>
                <option value="?sort_by=price&order=desc" <?= ($sort_by == 'price' && $order == 'desc') ? 'selected' : '' ?>>Price (High to Low)</option>
                <option value="?sort_by=quantity&order=asc" <?= ($sort_by == 'quantity' && $order == 'asc') ? 'selected' : '' ?>>Stock (Low to High)</option>
                <option value="?sort_by=quantity&order=desc" <?= ($sort_by == 'quantity' && $order == 'desc') ? 'selected' : '' ?>>Stock (High to Low)</option>
            </select>
        </div>

        <!-- Product Cards -->
        <div class="product-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
                    <div class="product-info">
                        <h4><?= htmlspecialchars($row['product_name']) ?></h4>
                        <p>Price: ₱<?= number_format(htmlspecialchars($row['price']), 2) ?></p>
                        <p class="stock-quantity">Remaining Stock: <?= htmlspecialchars($row['quantity']) ?></p>
                        <p class="product-caption"><?= htmlspecialchars($row['caption']) ?></p>

                        <div>
                            <a href="edit_product.php?id=<?= $row['product_id'] ?>">
                                <button class="edit-button">Edit</button>
                            </a>
                            <a href="delete_product.php?id=<?= $row['product_id'] ?>" onclick="return confirm('Are you sure you want to delete this product?')">
                                <button class="delete-button">Delete</button>
                            </a>
                        </div>

                        <!-- Add Stock Form -->
                        <form action="add_stock.php" method="POST" class="add-stock-form">
                            <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                            <input type="number" name="stock_quantity" min="1" placeholder="Add Stock" required>
                            <button type="submit">Add Stock</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
