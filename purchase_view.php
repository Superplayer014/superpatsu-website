<?php
session_start();
include 'db.php'; // Include your database connection file

// Initialize a message variable
$message = "";

// Handle deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    if (!empty($_POST['purchase_ids'])) {
        // Sanitize the input to prevent SQL injection
        $idsToDelete = implode(",", array_map('intval', $_POST['purchase_ids']));
        $deleteSql = "DELETE FROM purchases WHERE purchase_id IN ($idsToDelete)";
        
        if ($conn->query($deleteSql) === TRUE) {
            $message = "Purchases deleted successfully."; // Set success message
        } else {
            $message = "Error deleting purchases: " . $conn->error; // Set error message
        }
    } else {
        $message = "No purchases selected for deletion."; // Set no selection message
    }
}

// Fetch purchases
$sql = "
    SELECT 
        purchases.purchase_id,
        client.full_name,
        client.address,
        client.contact,
        products.product_name,
        products.price,
        purchases.quantity,
        purchases.date,
        client.client_id
    FROM 
        purchases
    INNER JOIN 
        client ON purchases.client_id = client.client_id
    INNER JOIN 
        products ON purchases.product_id = products.product_id
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase History</title>
    <style>
        /* General body style */
        body {
            background: linear-gradient(to right, #5A3F37, #8A6E5A); /* Soft Brown and Beige Gradient */
            color: #E0E0E0;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Header styling */
        header {
            background: linear-gradient(to right, #8A6E5A, #B09A72); /* Light brown gradient */
            color: #121212;
            padding: 20px;
            text-align: center;
            font-size: 28px;
            box-shadow: 0 4px 12px rgba(138, 110, 90, 0.5);
        }

        /* Navigation bar styling */
        nav {
            background-color: #3A2A1D; /* Dark brown */
            display: flex;
            justify-content: center;
            padding: 15px 0;
        }

        nav a {
            color: #F4D03F; /* Gold color for links */
            text-decoration: none;
            padding: 14px 25px;
            margin: 0 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        nav a:hover {
            color: #FFFFFF;
            background-color: #2F1D0D; /* Darker brown hover effect */
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(255, 204, 0, 0.5);
        }

        /* Table styling */
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #4E3B31; /* Dark brown */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        }

        th, td {
            border: 1px solid #333;
            padding: 14px;
            text-align: left;
            color: #E0E0E0;
        }

        th {
            background-color: #F4D03F; /* Gold for header */
            color: #121212;
            font-size: 18px;
            text-shadow: 0 0 10px rgba(255, 204, 0, 0.5);
        }

        /* Button styling */
        button[type="submit"] {
            background-color: #F4D03F; /* Gold for buttons */
            color: #121212;
            padding: 12px 25px;
            border: none;
            cursor: pointer;
            font-size: 18px;
            border-radius: 5px;
            transition: all 0.3s ease;
            box-shadow: 0 0 10px rgba(255, 204, 0, 0.5);
        }

        button[type="submit"]:hover {
            background-color: #B09A72; /* Light brown hover effect */
            box-shadow: 0 0 14px rgba(138, 110, 90, 0.7);
        }

        /* Table row hover effect */
        tr:hover {
            background-color: #2A2A2A; /* Darker row hover */
        }

        /* Footer styling */
        footer {
            background-color: #3A2A1D; /* Dark brown footer */
            color: #666;
            text-align: center;
            padding: 12px 0;
            font-size: 14px;
            box-shadow: 0 -1px 3px rgba(255, 204, 0, 0.2);
        }

        /* Select all checkbox styling */
        input[type="checkbox"] {
            width: 22px;
            height: 22px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                align-items: center;
            }

            nav a {
                margin: 5px 0;
                padding: 12px 20px;
            }

            table {
                width: 100%;
                margin: 10px;
            }

            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <header>Purchase History</header>
    <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="purchase_view.php">View Purchases</a>
        <a href="add_product.php" class="add-button">Add Product</a>
    </nav>

    <div>
        <form method="POST" action="">
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>Purchase ID</th>
                        <th>Client Name</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Date</th>
                        <th>Client Details</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><input type="checkbox" name="purchase_ids[]" value="<?= htmlspecialchars($row['purchase_id']) ?>"></td>
                            <td><?= htmlspecialchars($row['purchase_id']) ?></td>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td><?= htmlspecialchars($row['address']) ?></td>
                            <td><?= htmlspecialchars($row['contact']) ?></td>
                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                            <td>₱<?= number_format(htmlspecialchars($row['price']), 2) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td><?= htmlspecialchars($row['date']) ?></td>
                            <td><a href="costumer_info.php?client_id=<?= htmlspecialchars($row['client_id']) ?>" style="color: #F4D03F;">View Details</a></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
                <button type="submit" name="delete">Delete Selected</button>
            <?php else: ?>
                <p>No purchases found.</p>
            <?php endif; ?>
        </form>
    </div>

    <?php if (!empty($message)): ?>
        <script>
            alert("<?= addslashes($message) ?>");
        </script>
    <?php endif; ?>
    
    <script>
        // Confirm before deleting
        document.querySelector('form').addEventListener('submit', function(event) {
            if (document.querySelectorAll('input[name="purchase_ids[]"]:checked').length === 0) {
                alert("Please select at least one purchase to delete.");
                event.preventDefault();  // Prevent form submission
            }
        });

        // Select all checkboxes functionality
        document.getElementById('select-all').addEventListener('change', function(e) {
            let checkboxes = document.querySelectorAll('input[name="purchase_ids[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = e.target.checked);
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
