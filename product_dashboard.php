<?php 
session_start();
include 'db.php';

// Check if the client is logged in; otherwise, redirect to the login page
if (!isset($_SESSION['client_id'])) {
    header("Location: signup.php");
    exit();
}

// Retrieve client ID from session
$client_id = $_SESSION['client_id'];

// Fetch client information
$sql_client = "SELECT * FROM client WHERE client_id = $client_id";
$result_client = $conn->query($sql_client);

// Check if client data was fetched successfully
if ($result_client && $result_client->num_rows > 0) {
    $client = $result_client->fetch_assoc();
    $client_name = htmlspecialchars($client['full_name']);
} else {
    $client_name = "Guest"; // Default name if client data not found
}

// Sorting logic
$sort_option = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';

// Build the SQL query based on the selected sort option
switch ($sort_option) {
    case 'price_asc':
        $sql_products = "SELECT * FROM products ORDER BY price ASC";
        break;
    case 'price_desc':
        $sql_products = "SELECT * FROM products ORDER BY price DESC";
        break;
    case 'name_asc':
        $sql_products = "SELECT * FROM products ORDER BY product_name ASC";
        break;
    case 'name_desc':
        $sql_products = "SELECT * FROM products ORDER BY product_name DESC";
        break;
    default:
        $sql_products = "SELECT * FROM products ORDER BY product_name ASC";
        break;
}

$result_products = $conn->query($sql_products);

if (!$result_products) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Dashboard</title>
    <style>
    /* General body styling */
    body {
        font-family: 'Roboto', sans-serif;
        background: linear-gradient(to right, #5A3F37, #8A6E5A); /* Soft brown to beige */
        color: #E0E0E0;
        margin: 0;
        padding: 0;
    }

    /* Header styling */
    header {
        background: linear-gradient(to right, #8A6E5A, #B09A72); /* Light brown to beige */
        color: #121212;
        padding: 20px;
        text-align: center;
        font-size: 24px;
        box-shadow: 0 4px 10px rgba(138, 110, 90, 0.5);
    }

    /* Navigation bar styling */
    nav {
        background-color: #3A2A1D; /* Deep brown */
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
    }

    nav a {
        float: left;
        display: block;
        color: #F4D03F; /* Gold color for links */
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        transition: color 0.3s, background-color 0.3s;
    }

    nav a:hover {
        background-color: #2F1D0D; /* Darker brown on hover */
        color: white;
    }

    /* Sorting form styling */
    form {
        margin: 20px auto;
        text-align: center;
        font-size: 16px;
    }

    select, button {
        padding: 8px;
        margin: 8px 10px;
        border-radius: 4px;
        background-color: #3A2A1D;
        color: #F4D03F;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    select:focus, button:hover {
        background-color: #B09A72;
    }

    /* Container for product cards */
    .product-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin: 20px auto;
        max-width: 1200px;
        justify-content: center;
    }

    /* Style for individual product cards */
    .product-card {
        width: 240px;
        border: 1px solid #333;
        border-radius: 8px;
        overflow: hidden;
        text-align: center;
        background-color: #4E3B31; /* Dark brown background */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(255, 204, 0, 0.8); /* Gold hover effect */
    }

    /* Image styling */
    .product-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-bottom: 1px solid #333;
    }

    /* Product details styling */
    .product-info {
        padding: 15px;
    }

    /* Button styling */
    .product-card button {
        width: 100%;
        padding: 10px;
        margin: 5px 0;
        border: none;
        color: white;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    .buy-button {
        background-color: #F4D03F; /* Yellow gold */
    }

    .buy-button:hover {
        background-color: #B09A72; /* Darker beige */
    }

    /* Footer styling */
    footer {
        background-color: #3A2A1D;
        color: #9E9E9E;
        text-align: center;
        padding: 10px;
        position: relative;
        bottom: 0;
        width: 100%;
        box-shadow: 0 -1px 10px rgba(0, 0, 0, 0.6);
    }

    /* Input styling */
    input {
        width: 90%;
        padding: 8px;
        margin: 8px 0;
        background-color: #4E3B31; /* Dark brown */
        border: 1px solid #333;
        color: #E0E0E0;
        text-align: center;
        border-radius: 4px;
        transition: background-color 0.3s, box-shadow 0.3s;
    }

    input:focus {
        background-color: #3A2A1D; /* Darker brown on focus */
        box-shadow: 0 0 8px rgba(255, 204, 0, 0.3); /* Glow effect */
    }
</style>
</head>
<body>
    <header>
        Welcome, <?= $client_name ?>!
    </header>

    <!-- Navigation bar -->
    <nav>
        <a href="contact_info.php?client_id=<?= $client_id ?>">Contact Information</a>
        <a href="product_dashboard.php">Product Dashboard</a>
        <a href="get_started.php">Logout</a>
    </nav>

    <!-- Sorting dropdown -->
    <form method="GET" action="">
        <label for="sort">Sort by: </label>
        <select name="sort" id="sort">
            <option value="price_asc" <?= isset($_GET['sort']) && $_GET['sort'] == 'price_asc' ? 'selected' : '' ?>>Price (Low to High)</option>
            <option value="price_desc" <?= isset($_GET['sort']) && $_GET['sort'] == 'price_desc' ? 'selected' : '' ?>>Price (High to Low)</option>
            <option value="name_asc" <?= isset($_GET['sort']) && $_GET['sort'] == 'name_asc' ? 'selected' : '' ?>>Name (A-Z)</option>
            <option value="name_desc" <?= isset($_GET['sort']) && $_GET['sort'] == 'name_desc' ? 'selected' : '' ?>>Name (Z-A)</option>
        </select>
        <button type="submit">Sort</button>
    </form>

    <!-- Product cards container -->
    <div class="product-container">
        <?php while ($row = $result_products->fetch_assoc()): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
                <div class="product-info">
                    <h4><?= htmlspecialchars($row['product_name']) ?></h4>
                    <p><?= htmlspecialchars($row['caption']) ?></p>
                    <p>Price: ₱<?= number_format(htmlspecialchars($row['price']), 2) ?></p>
                    <form action="purchase.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity" min="1" value="1" required>
                        <button type="submit" name="buy" class="buy-button">Buy Now</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
