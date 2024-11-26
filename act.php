<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Orders and Payments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dbact";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to join 'client', 'orders', and 'payment' tables
        $sql = "SELECT client.full_name, client.contact, client.address, 
                       orders.order_id, orders.product_name, orders.price, orders.quantity,
                       payment.total, payment.cash, payment.`change`
                FROM client
                INNER JOIN orders ON client.client_id = orders.client_id
                LEFT JOIN payment ON orders.order_id = payment.order_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Full Name</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Cash</th>
                        <th>Change</th>
                    </tr>";

            // Output data and calculate total, cash, and change
            while($row = $result->fetch_assoc()) {
                $price = $row['price'];
                $quantity = $row['quantity'];
                $total = $price * $quantity;
                $cash = isset($row['cash']) ? $row['cash'] : 0;
                $change = $cash - $total;

                // Display results with peso symbol
                echo "<tr>
                        <td>{$row['full_name']}</td>
                        <td>{$row['contact']}</td>
                        <td>{$row['address']}</td>
                        <td>{$row['product_name']}</td>
                        <td>₱" . number_format($price, 2) . "</td>
                        <td>{$quantity}</td>
                        <td>₱" . number_format($total, 2) . "</td>
                        <td>₱" . number_format($cash, 2) . "</td>
                        <td>₱" . number_format($change, 2) . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No results found</p>";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
