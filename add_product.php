<?php 
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $caption = $_POST['caption'];  // New caption variable
    
    // Image upload handling
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Define the target directory
        $target_dir = "uploads/";
        // Generate a unique filename
        $target_file = $target_dir . uniqid() . "_" . basename($_FILES["image"]["name"]);
        // Move the uploaded file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        } else {
            echo "Error uploading image.";
            exit;
        }
    } else {
        $image_path = null;  // Set to null if no image is uploaded
    }

    // Insert product details into the database
    $stmt = $conn->prepare("INSERT INTO products (product_name, price, quantity, image, caption) VALUES (?, ?, ?, ?, ?)"); // Include caption
    $stmt->bind_param("sdiss", $product_name, $price, $quantity, $image_path, $caption);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <style>
    body {
        font-family: 'Roboto', sans-serif;
        background: linear-gradient(to right, #5A3F37, #8A6E5A); /* Soft brown and beige gradient */
        color: #E0E0E0; /* Light text for contrast */
        margin: 0;
        padding: 20px;
    }

    h2 {
        text-align: center;
        color: #F4D03F; /* Gold for headings */
        text-shadow: 0 0 8px rgba(255, 204, 0, 0.5); /* Neon glow effect */
    }

    form {
        background-color: #4E3B31; /* Dark brown background */
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(255, 204, 0, 0.2); /* Soft gold shadow */
        padding: 20px;
        max-width: 400px;
        margin: auto;
        color: #E0E0E0;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #A8A8A8; /* Subtle grey for labels */
    }

    input[type="text"],
    input[type="number"],
    input[type="file"],
    textarea {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border: 1px solid #333;
        border-radius: 5px;
        box-sizing: border-box;
        background-color: #2C2C2E; /* Dark input background */
        color: #E0E0E0; /* Light text */
        box-shadow: inset 0 0 5px rgba(255, 204, 0, 0.3); /* Soft inner glow */
    }

    input[type="text"]:focus,
    input[type="number"]:focus,
    input[type="file"]:focus,
    textarea:focus {
        outline: none;
        border-color: #F4D03F; /* Gold on focus */
        box-shadow: 0 0 8px rgba(255, 204, 0, 0.5); /* Outer glow on focus */
    }

    button {
        background-color: #F4D03F; /* Gold button */
        color: #121212; /* Dark text on button */
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
        font-weight: bold;
        font-size: 16px;
        box-shadow: 0 4px 10px rgba(255, 204, 0, 0.4); /* Soft gold glow */
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    button:hover {
        background-color: #B09A72; /* Lighter brown on hover */
        box-shadow: 0 6px 15px rgba(255, 204, 0, 0.6); /* Enhanced glow on hover */
    }
</style>

</head>
<body>
<!-- Add Product Form -->
<h2>Add Product</h2>
<form action="add_product.php" method="POST" enctype="multipart/form-data">
    <label for="product_name">Product Name:</label>
    <input type="text" name="product_name" required><br>

    <label for="price">Price:</label>
    <input type="number" step="0.01" name="price" required><br>

    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" required><br>

    <label for="caption">Product Caption:</label>
    <textarea name="caption" rows="4" cols="50" required placeholder="Enter product caption here..."></textarea><br> 
    
    <label for="image">Product Image:</label>
    <input type="file" name="image" accept="image/*"><br>

    <button type="submit" class="add-button">Add Product</button>
</form>

</body>
</html>
